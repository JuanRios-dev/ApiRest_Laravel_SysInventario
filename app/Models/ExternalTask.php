<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalTask extends Model
{
    use HasFactory;
	
	protected $fillable = [
		'ticket_id',
		'pieza',
		'descripcion',
		'valor', 10, 2,
		'codigoFactura'
	];
	
	public function ticket()
	{
		return $this->belongsTo(Ticket::class);
	}
	
}
