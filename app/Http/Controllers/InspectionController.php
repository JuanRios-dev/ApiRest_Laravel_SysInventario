<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inspection;


class InspectionController extends Controller
{
    public function index(Request $request)
	{
		$search = $request->input('search');
		$perPage = $request->input('per_page', 5);
		$inspections = Inspection::query();
		
		$searchableFields = ['nombre', 'descripcion'];

		if ($search) {
			$inspections->where(function($query) use ($searchableFields, $search) {
				foreach ($searchableFields as $field) {
					$query->orWhere($field, 'LIKE', '%' . $search . '%');
				}
			});
		}

		$inspections = $inspections->paginate($perPage);

		return response()->json($inspections);
	}
	
	public function store(Request $request)
	{
		$data = $request->validate([
			'nombre' => 'required|string',
			'descripcion' => 'required|string',
		]);
		
		$inspection = Inspection::create($data);
		
		return response()->json(['message' => 'Inspección creada con exito',  'inspection' => $inspection], 201);
	}
	
	public function update(Request $request)
	{
		$inspection = Inspection::find($id);
		
		$data = $request->validate([
			'nombre' => 'required|string',
			'descripcion' => 'required|string',
		]);
		
		$inspection->update($data);
		
		return response()->json(['message' => 'Inspección actualizada con exito',  'inspection' => $inspection]);
	}
	
	public function destroy ($id)
	{
		Inspection::findOrFail($id)->delete();
		
		return response()->json(['message' => 'Inspección eliminada con exíto']);
	}
}
