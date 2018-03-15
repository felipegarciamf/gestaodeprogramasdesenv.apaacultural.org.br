<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Indicador extends Model
{
    use SoftDeletes;

    protected $table = "indicadores";

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

    public function acao()
    {
         return $this->belongsTo('App\Acao','acao_id','id');
    }
    public function regra()
    {
        return $this->belongsTo('App\Regras','regra_id','id');
    }
    // select de nome de indicadores puxando de regras
    public function scopeName($query, $regra_nome)
    {
        if(trim($regra_nome) != "")
        {
            $query
            ->join('regra', 'regra.id', '=', 'indicadores.regra_id')
            ->where('regra.descricao','like' , '%' .$regra_nome .'%');
        }
    }
    public function scopePlano($query, $plano)
    {
        if(trim($plano) != "")
        {
            $query
            ->join('planos','planos.id', '=', 'indicadores.plano_id')
            ->where('planos.nome','like', '%' . $plano . '%');
        }
    }
    

}
