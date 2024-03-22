<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
	
	protected $fillable = [
		'ticket_id',
		'descripcion',
		'estado'
	];
	
	public function ticket()
	{
		return $this->belongsTo(Ticket::class);
	}
}
