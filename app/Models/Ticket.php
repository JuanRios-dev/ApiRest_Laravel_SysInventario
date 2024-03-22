<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
	
	protected $fillable = [
		'customer_id',
		'user_id',
		'motorcycle_id',
		'fecha',
		'hora',
		'tipoTrabajo',
		'gasolina',
		'kilometraje',
		'observaciones',
		'estado',
		'firma_cliente'
	];
	
	public function customer()
	{
		return $this->belongsTo(Customer::class);
	}
	
	public function user()
	{
		return $this->belongsTo(User::class);
	}
	
	public function motorcycle()
	{
		return $this->belongsTo(Motorcycle::class);
	}
	
	public function record()
	{
		return $this->hasOne(Record::class);
	}
	
	public function tasks()
	{
		return $this->hasMany(Task::class);
	}
	
	public function externaltasks()
	{
		return $this->hasMany(ExternalTask::class);
	}
	
	public function inspectionTicket()
    {
        return $this->belongsToMany(Inspection::class)
            ->withPivot('fecha_hora')
            ->withTimestamps();
    }
	
	public function productTicket()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('cantidad')
            ->withTimestamps();
    }
}
