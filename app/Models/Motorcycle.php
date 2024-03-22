<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motorcycle extends Model
{
    use HasFactory;
	
	protected $fillable = [
		'siglas',
		'marca',
		'modelo',
		'color',
		'placa',
		'chasis',
		'dependencia'
	];
	
	public function tickets()
	{
		return $this->hasMany(Ticket::class);
	}
}
