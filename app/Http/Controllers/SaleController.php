<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Cellar;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
	{
		$search = $request->input('search');
		$perPage = $request->input('per_page', 5);
		$sales = Sale::query();

		if ($search) {
			$searchableFields = ['codigo', 'fechaEmision'];

			$sales->where(function($query) use ($searchableFields, $search) {
				foreach ($searchableFields as $field) {
					$query->orWhere($field, 'LIKE', '%' . $search . '%');
				}
			});
		}

		$sales = $sales->orderBy('created_at', 'desc')->paginate($perPage);

		return response()->json($sales);
	}
	
	public function store(Request $request)
	{
		//VALIDACIONES
		$request->validate([
            'customer_id' => 'required|exists:customers,id',
            'codigo' => 'required|unique:sales,codigo',
            'fechaEmision' => 'required|date',
            'metodoPago' => 'required|in:Efectivo,Nequi,Tarjeta,Credito',
            'subTotal' => 'required|numeric|gte:0',
            'impuestos' => 'required|numeric|gte:0',
			'total' => 'required|numeric|gte:0',
            'descuento_global' => 'required|numeric|gte:0',
			'valor_descuentoGlobal' => 'required|numeric|gte:0',
            'descuento_total' => 'required|numeric|gte:0',
            
			//CLAVE PRODUCTS
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.cantidad' => 'required|numeric|gt:0',
            'products.*.precio_unitario' => 'required|numeric|gte:0',
            'products.*.descuento' => 'required|numeric|gte:0',
            'products.*.valor_descuento' => 'required|numeric|gte:0',
			'products.*.subtotal' => 'required|numeric|gte:0',
            'products.*.impuestos' => 'required|numeric|gte:0',
            'products.*.precio_total' => 'required|numeric|gte:0',
        ]);
		
		$cellar = Cellar::where('predeterminada', 1)->first();
		
		if($cellar === null)
		{
			return response()->json(['error' => 'No existen bódegas, agrega una']);
		}
		
		DB::beginTransaction();
		
		try {
			$sales = new Sale($request->except('products'));
			if ($request->metodoPago === 'Credito') {
				$sales->estadoFactura = 0; // Pendiente
				$sales->deuda = $request->total;
			} else {
				$sales->estadoFactura = 1; // Pagada
				$sales->deuda = 0;
			}
			$sales->save();
			
			//ACTUALIZACIÓN DE PRESUPUESTO
			$cellar->company->presupuesto += $request->total;
			$cellar->company->save();
			
			$products = $request->input('products');
			
			foreach ($products as $productData) {
                $product = Product::find($productData['product_id']);
				$productInCellar = $cellar->products()->where('product_id', $product->id)->first();
				
				if ($productInCellar) {
					$cantidadDisponible = $productInCellar->pivot->cantidad;
				} else {
					throw new \Exception('No hay stock del producto en la bodega predeterminada');
				}
				
				if ($cantidadDisponible < $productData['cantidad']) {
					throw new \Exception('La cantidad a vender del producto '.$product->nombre.' es mayor que la cantidad disponible en la bodega.');
				}
				
                $sales->productSale()->attach($product, $productData);
				$cellar->products()->syncWithoutDetaching([$product->id => ['cantidad' => \DB::raw('cantidad - ' . $productData['cantidad'])]]);
            }
			
			DB::commit();

            return response()->json(['message' => 'Venta realizada exitosamente'], 201);
		} catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 500);
        }
	}
	
	public function show($id)
	{
		$sale = Sale::with('customer', 'productSale')->findOrFail($id);
        
        return response()->json(['sale' => $sale], 200);
	}

}
