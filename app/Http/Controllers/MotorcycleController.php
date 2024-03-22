<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Motorcycle;

class MotorcycleController extends Controller
{
    public function index(Request $request)
	{
		$search = $request->input('search');
		$perPage = $request->input('per_page', 5);
		$motorcycles = Motorcycle::query();
		
		$searchableFields = ['siglas', 'marca', 'modelo', 'color', 'placa', 'chasis', 'dependecia'];

		if ($search) {
			$motorcycles->where(function($query) use ($searchableFields, $search) {
				foreach ($searchableFields as $field) {
					$query->orWhere($field, 'LIKE', '%' . $search . '%');
				}
			});
		}

		$motorcycles = $motorcycles->paginate($perPage);

		return response()->json($motorcycles);
	}
	
	public function show($id)
	{
		$motorcycle = Motorcycle::findOrFail($id);
		
		return response()->json($motorcycle);
	}
	
	public function store(Request $request)
	{
		$data = $request->validate([
			'siglas' => 'required|unique:motorcycles,siglas|string',
			'marca' => 'required|string',
			'modelo' => 'required|string',
			'color' => 'required|string',
			'placa' => 'required|unique:motorcycles,placa|string',
			'chasis' => 'required|unique:motorcycles,chasis|string',
			'dependencia' => 'required|string'
		]);
		
		$motorcycle = Motorcycle::create($data);
		
		return response()->json(['message' => 'Motocicleta creada con exito',  'motorcycle' => $motorcycle], 201);
	}
	
	public function update(Request $request, $id)
	{
		$motorcycle = Motorcycle::find($id);
		
		$data = $request->validate([
			'siglas' => 'required|unique:motorcycles,siglas,' . $motorcycle->id,
			'marca' => 'required|string',
			'modelo' => 'required|string',
			'color' => 'required|string',
			'placa' => 'required|unique:motorcycles,placa,' . $motorcycle->id,
			'chasis' => 'required|unique:motorcycles,chasis,' . $motorcycle->id,
			'dependencia' => 'required|string'
		]);
		
		$motorcycle->update($data);
		
		return response()->json(['message' => 'Motocicleta actualizada con exito',  'motorcycle' => $motorcycle]);
	}
	
	public function destroy ($id)
	{
		motorcycle::findOrFail($id)->delete();
		
		return response()->json(['message' => 'Motocicleta eliminada con exíto']);
	}
}
