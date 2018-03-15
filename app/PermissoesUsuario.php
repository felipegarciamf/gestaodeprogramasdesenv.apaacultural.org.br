<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissoesUsuario extends Model
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
    public function plano()
    {
         return $this->belongsTo('App\Plano');
    }

    //Fazer relacionamento de um para um
    public function programa()
    {
         return $this->belongsTo('App\Programa');
    }

    //Fazer relacionamento de um para um
    public function user()
    {
         return $this->belongsTo('App\User');
    }
}
