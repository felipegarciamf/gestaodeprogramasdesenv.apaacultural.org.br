<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements Authenticatable
{
	/*PARA FAZER MASSASSIGNMENT
		protected $fillable = [
			'campo',
			'campo2'
		];
	*/


	/*Fazer relacionamento de um para muitos
		public function nomedatabelaquevcquerrelacionar()
		{
			return $this->hasMany('App\NomeDaModelQueVcQuerRelacionar');
		}
	*/

	/*Fazer relacionamento de um para um
	*	public function nomedatabelaquevcquerrelacionar()
	*	{
	*		 return $this->belongsTo('App/NomeDaModelQueVcQuerRelacionar');
	*	}
	*/

    use \Illuminate\Auth\Authenticatable, SoftDeletes;

    //metodo usado para alterar o nome de password default do laravel que é "password" para o que vc tiver no seu banco
    public function getAuthPassword()
	{
	     return $this->attributes['senha'];//change the 'passwordFieldinYourTable' with the name of your field in the table
	}

	/**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
