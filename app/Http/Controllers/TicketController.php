<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index(Request $request)
	{
		$search = $request->input('search');
		$perPage = $request->input('per_page', 5);
		$tickets = Ticket::query();
		
		$searchableFields = ['customer_id', 'user_id', 'motorcycle_id', 'fecha', 'hora', 'tipoTrabajo', 'gasolina', 'kilometraje', 'estado'];

		if ($search) {
			$tickets->where(function($query) use ($searchableFields, $search) {
				foreach ($searchableFields as $field) {
					$query->orWhere($field, 'LIKE', '%' . $search . '%');
				}
			});
		}

		$tickets = $tickets->paginate($perPage);

		return response()->json($tickets);
	}
	
	public function store(Request $request)
	{
		$validatedData = $request->validate([
			// Campos del ticket
			'ticket.customer_id' => 'required|integer|exists:customers,id',
			'ticket.user_id' => 'required|integer|exists:users,id',
			'ticket.motorcycle_id' => 'required|integer|exists:motorcycles,id',
			'ticket.fecha' => 'required|date',
			'ticket.hora' => 'required|date_format:H:i',
			'ticket.tipoTrabajo' => 'required|string',
			'ticket.gasolina' => 'required|numeric',
			'ticket.kilometraje' => 'required|numeric',
			'ticket.observaciones' => 'nullable|text',
			'ticket.estado' => 'required|string',
			'ticket.firma_cliente' => 'nullable|text',

			// Campos del registro (record)
			'record.herramientas' => 'required|boolean',
			'record.espejos' => 'required|boolean',
			'record.llaves' => 'required|boolean',
			'record.tapasLaterales' => 'required|boolean',
			'record.cocas' => 'required|boolean',
			'record.tapasGas' => 'required|boolean',
			'record.guardacadenas' => 'required|boolean',
			'record.lucesDireccional' => 'required|boolean',
			'record.stop' => 'required|boolean',
			'record.pito' => 'required|boolean',
			'record.bateria' => 'required|boolean',
			'record.luzFarola' => 'required|boolean',
			'record.luzFreno' => 'required|boolean',
			'record.lucesPiloto' => 'required|boolean',
			'record.tanqueGasolina' => 'required|boolean',
			'record.tapas' => 'required|boolean',
			'record.guardabarro' => 'required|boolean',
			'record.sillin' => 'required|boolean',
			'record.manijas' => 'required|boolean',
			'record.exosto' => 'required|boolean',
			'record.farola' => 'required|boolean',

			// Campos del task (tarea)
			'task' => 'required|array',
			'task.descripcion.*' => 'required|string',
			'task.estado.*' => 'required|boolean',
		]);
		
		DB::beginTransaction();
		
		try {
			
			$ticket = new Ticket($validatedData['ticket']);
			$ticket->save();

			$record = $ticket->record()->create($validatedData['record']);
			
			foreach ($validatedData['task'] as $taskData) {
                $task = $ticket->tasks()->create($taskData);
            }
			
			DB::commit();

			return response()->json(['message' => 'Ticket creado exitosamente'], 201);
		} catch (\Exception $e) {
			
			DB::rollBack();

			return response()->json(['message' => $e->getMessage()], 500);
		}
	}
	
	public function show($id)
	{
		$ticket = Ticket::with(['record', 'tasks', 'externalTasks'])->find($id);
    
		if (!$ticket) {
			return response()->json(['message' => 'Ticket no encontrado'], 404);
		}
		
		return response()->json(['ticket' => $ticket], 200);
	}
}
