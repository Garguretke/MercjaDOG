<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'quantity',
    ];

	protected function serializeDate(\DateTimeInterface $date){
		return $date->format('Y-m-d H:i:s');
	}
}
