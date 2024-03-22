<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;
	
	protected $fillable = [
		'ticket_id',
		'herramientas',
		'espejos',
		'llaves',
		'tapasLaterales',
		'cocas',
		'tapasGas',
		'guardacadenas',
		'lucesDireccional',
		'stop',
		'pito',
		'bateria',
		'luzFarola',
		'luzFreno',
		'lucesPiloto',
		'tanqueGasolina',
		'tapas',
		'guardabarro',
		'sillin',
		'manijas',
		'exosto',
		'farola'
	];
	
	public function ticket()
	{
		return $this->belongsTo(Ticket::class);
	}
	
}
