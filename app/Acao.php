<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Acao extends Model
{
    use SoftDeletes;

    protected $table = "acoes";

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

    public function programa()
    {
         return $this->belongsTo('App\Programa');
    }

    public function regra()
    {
        return $this->belongsTo('App\Regras');
    }

    public function especie_acao()
    {
         return $this->belongsTo('App\EspecieAcao','especie_acao_id','id');
    }

    public function linguagem_acao()
    {
         return $this->belongsTo('App\LinguagemAcao','linguagem_acao_id','id');
    }

    public function funcao_acao()
    {
         return $this->belongsTo('App\FuncaoAcao','funcao_acao_id','id');
    }

    public function scopeName($query, $name)
    {
        if(trim($name) != ""){
            $query->where('acoes.nome','like', '%'. $name . '%');
        }
    }
    public function scopePrograma_id($query, $programa_id)
    {
        if(trim($programa_id) != "")
        {
            $query
            ->join('programas','programas.id','=','acoes.programa_id')
            ->where('programas.nome','like', '%'. $programa_id . '%');
        }
    }
    public function scopeCodigo($query, $codigo)
    {
        if(trim($codigo) != "")
        {
            $query->where('acoes.codigo_acao','=', $codigo);
        }
    }
}
