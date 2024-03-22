<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;
	
	protected $fillable = [
		'nombre',
		'descripcion'
	];
	
	public function ticketInspection()
    {
        return $this->belongsToMany(Ticket::class)
            ->withPivot('fecha_hora')
            ->withTimestamps();
    }
}
