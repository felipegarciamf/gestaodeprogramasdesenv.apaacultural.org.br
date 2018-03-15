<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Programa extends Model
{
    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    //Fazer relacionamento de um para um
    public function plano()
    {
         return $this->belongsTo('App\Plano');
    }

    public function tipagem()
    {
         return $this->belongsTo('App\Tipagem');
    }

    public function scopeName($query, $name){

        if(trim($name != ""))
        {
            $query->where('nome','like','%' . $name . '%');
        }

    }
}
