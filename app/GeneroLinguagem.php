<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneroLinguagem extends Model
{
    use SoftDeletes;

    protected $table = "generos_linguagem";
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function linguagem_programa()
    {
         return $this->belongsTo('App\LinguagemPrograma');
    }
}
