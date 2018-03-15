<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Atividade extends Model
{
    use SoftDeletes;
    

    protected $table = 'atividades';

    protected $fillable = ['nome', 'data'];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    //ESSE DATES É BOM PRA O LARAVEL SABER QUAL CAMPO É DO TIPO DATA E AI DA PRA FAZER O FORMAT DIRETO NO CAMPO!
    protected $dates = ['deleted_at'];

    public function plano()
    {
    	return $this->belongsTo('App\Plano');
    }

    public function programa()
    {
    	return $this->belongsTo('App\Programa');
    }

	public function tipo_publico()
    {
         return $this->belongsTo('App\TipoPublico','tipo_publico_id','id');
    }

    public function realizador()
    {
        return $this->belongsTo('App\Realizador','realizador_id','id');
    }

    public function linguagem_programa()
    {
    	return $this->belongsTo('App\LinguagemPrograma','linguagem_programa_id','id');
    }

    public function municipio()
    {
    	return $this->belongsTo('App\Municipio','municipio_id','id');
    }

    public function tipo_evento()
    {
    	return $this->belongsTo('App\TipoEvento','tipo_evento_id','id');
    }

    // criando query de busca por nome
    public function scopeName($query, $name)
    {
        if(trim($name) != "")
        {
            $query->where('atividades.nome','like' ,'%' . $name .'%' );
        }
    }
    public function scopeDate($query, $date)
    {
        if(trim($date) != "")
        {
            $query->where('atividades.data', 'Like', '%' . $date . '%');
        }
    }

    public function scopeMunicipio($query, $municipio)
    {
        if(trim($municipio) != "")
        {
            $query
            ->join('municipios','municipios.id','=','atividades.municipio_id')
            ->where('municipios.nome','like','%' . $municipio . '%');
        }
    }
}
