<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EspecieAcao extends Model
{
	use SoftDeletes;
	/**
	* The database table used by the model.
	*
	* @var string
	*/
	protected $table = 'especie_acoes';

	/**
	* The attributes that should be mutated to dates.
	*
	* @var array
	*/
	protected $dates = ['deleted_at'];
}
