<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plano extends Model
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
	/*public function users()
	{
		 return $this->hasOne('App/User');
	}*/

    //Fazer relacionamento de um para um
    public function uge()
    {
         return $this->belongsTo('App\Uge');
    }

    //Fazer relacionamento de um para um
    public function os()
    {
         return $this->belongsTo('App\Os');
    }

    //Fazer relacionamento de um para um
    public function cg()
    {
         return $this->belongsTo('App\Cg');
    }

    //Fazer relacionamento de um para um
    public function objeto()
    {
         return $this->belongsTo('App\Objeto');
    }

    //Fazer relacionamento de um para um
    public function tipo_objeto()
    {
         return $this->belongsTo('App\TipoObjeto','tipoobjeto_id','id');
    }

}
