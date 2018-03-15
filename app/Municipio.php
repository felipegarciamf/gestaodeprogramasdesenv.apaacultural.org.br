<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipio extends Model
{
    	use SoftDeletes;
		/**
		* The database table used by the model.
		*
		* @var string
		*/
		// protected $table = 'oss';

		/**
		* The attributes that should be mutated to dates.
		*
		* @var array
		*/
		protected $dates = ['deleted_at'];

		//Fazer relacionamento de um para um
		public function regiao_administrativa()
		{
		 return $this->belongsTo('App\RegiaoAdministrativa','regiaoadministrativa_id','id');
		}
}
