<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Realizador extends Model
{
    use SoftDeletes;
	/**
	* The database table used by the model.
	*
	* @var string
	*/
	protected $table = 'realizadores';

	/**
	* The attributes that should be mutated to dates.
	*
	* @var array
	*/
	protected $dates = ['deleted_at'];

	//Fazer relacionamento de um para um
    public function municipio()
    {
         return $this->belongsTo('App\Municipio');
    }
}
