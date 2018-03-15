<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regras extends Model
{
    	
    use SoftDeletes;

    protected $table = "regra";
   
    /**
	* The attributes that should be mutated to dates.
	*
	* @var array
	*/
	protected $dates = ['deleted_at'];

	public function plano()
    {
         return $this->belongsTo('App\Plano');
    }

    public function scopeName($query, $name)
    {
        if(trim($name) != "")
        {
            $query->where('codigo','like','%'. $name .'%');
        }
    }
    //
}
