<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Auth;

use App\Municipio;
use App\RegiaoAdministrativa;

class MunicipioController extends Controller
{
    	/*
    	 *@var plano, @var cgs, @var objetos, @var oss, @var tipoobjetos, @var uges
    	 */
    	private $municipio;
    	private $regiaoadministrativa;


    	public function __construct(Municipio $municipio, RegiaoAdministrativa $regiaoadministrativa)
    	{
    		$this->municipio = $municipio;
    		$this->regiaoadministrativa = $regiaoadministrativa;
    	}

    	public function cadastraMunicipioView()
    	{
            if(Auth::user()->perfil == 2)
            {
        		$regiaoadministrativas = $this->regiaoadministrativa->orderBy('created_at', 'DESC')->get();
        		return view('programa.municipio.cadastra-municipio',['regiaoadministrativas' => $regiaoadministrativas]);
            }
            else
            {
                return redirect()->route('dashboard');
            }
    	}

    	public function listarMunicipio()
    	{
            if(Auth::user()->perfil == 2)
            {
        		//$usuarios = User::orderBy('created_at','desc')->get();
        		$municipios = $this->municipio->orderBy('created_at', 'DESC')->get();
        		return view('programa.municipio.lista-municipios',['municipios' => $municipios]);
            }
            else
            {
                return redirect()->route('dashboard');
            }
    	}

    	public function cadastraMunicipio(Request $request)
    	{
            if(Auth::user()->perfil == 2)
            {
        	    $municipio = $this->municipio;

        		$municipio->nome = $request["nome"];
        		$municipio->regiaoadministrativa_id = intval($request["regiao_administrativa"]);
        		$municipio->distancia = intval($request["distancia"]);

        	    $municipio->created_by = Auth::user()->id;

        		$municipio->save();

        		//Auth::login($user);

        		return redirect()->route('listar-municipios');
            }
            else
            {
                return redirect()->route('dashboard');
            }
    	}

    	public function editMunicipioView($id)
    	{
            if(Auth::user()->perfil == 2)
            {
        		$municipio = $this->municipio->find($id);

        		$regiaoadministrativas = $this->regiaoadministrativa->orderBy('created_at', 'DESC')->get();    		
        		return view('programa.municipio.editar-municipio',['municipio' => $municipio,'regiaoadministrativas' => $regiaoadministrativas]);
            }
            else
            {
                return redirect()->route('dashboard');
            }
    	}

    	public function editMunicipio(Request $request,$id)
    	{
            if(Auth::user()->perfil == 2)
            {
        		$municipio = $this->municipio->find($id);

        		$municipio->nome = $request["nome"];
        		$municipio->regiaoadministrativa_id = intval($request["regiao_administrativa"]);
        		$municipio->distancia = intval($request["distancia"]);

        	    $municipio->changed_by = Auth::user()->id;

        		$municipio->update();

        		return redirect()->route('listar-municipios');
            }
            else
            {
                return redirect()->route('dashboard');
            }
    	}

    	public function deleteMunicipio($id)
    	{
            if(Auth::user()->perfil == 2)
            {
        		$municipio = $this->municipio->find($id);
        	    $municipio->deleted_by = Auth::user()->id;
        	    $municipio->update();
        		$municipio->delete();

        		return redirect()->route('listar-municipios');
            }
            else
            {
                return redirect()->route('dashboard');
            }
    	}
}
