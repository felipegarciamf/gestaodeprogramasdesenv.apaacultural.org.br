<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

use DateTime;

use App\Rotina;
use App\Plano;

class RotinaController extends Controller
{
    	/*
    	 *@var plano, @var cgs, @var objetos, @var oss, @var tipoobjetos, @var uges
    	 */
    	private $plano;
    	private $rotina;

    	public function __construct(Rotina $rotina, Plano $plano)
    	{
    		$this->plano = $plano;
    		$this->rotina = $rotina;
    	}

    	public function cadastraRotinaView()
    	{
            if(Auth::user()->perfil == 2)
            {
        		$planos = $this->plano->orderBy('created_at', 'DESC')->get();
        		return view('plano.rotinas.cadastra-rotina',['planos' => $planos,]);
            }
            else
            {
                return redirect()->route('dashboard');
            }
    	}

    	public function listarRotina()
    	{
            if(Auth::user()->perfil == 2)
            {
        		//$usuarios = User::orderBy('created_at','desc')->get();
        		$rotinas = $this->rotina->orderBy('created_at', 'DESC')->get();
                
                //transforma formato da data para o brazuka para mostrar na view
                foreach ($rotinas as $rotina) {
                 $date = DateTime::createFromFormat("Y-m-d H:i:s",$rotina->data_limite);
                 $rotina->data_limite = $date->format('d/m/Y');   
                }
        		return view('plano.rotinas.lista-rotinas',['rotinas' => $rotinas]);
            }
            else
            {
                return redirect()->route('dashboard');
            }
    	}

    	public function cadastraRotina(Request $request)
    	{
            if(Auth::user()->perfil == 2)
            {
        	    $rotinas = $this->rotina;
        		$rotinas->nome = $request["nome"];
        		$rotinas->plano_id = intval($request["plano"]);
                $rotinas->realizada = 0;
        		$date = DateTime::createFromFormat('d/m/Y',$request["data_limite"]);
        		
            	$rotinas->data_limite = $date->format("Y-m-d H:i:s");

        	    $rotinas->created_by = Auth::user()->id;

        		$rotinas->save();

        		//Auth::login($user);

        		return redirect()->route('listar-rotinas');
            }
            else
            {
                return redirect()->route('dashboard');
            }
    	}

    	public function editRotinaView($id)
    	{
            if(Auth::user()->perfil == 2)
            {
        		$rotina = $this->rotina->find($id);
        		$planos = $this->plano->orderBy('created_at', 'DESC')->get();

                //transforma formato da data para o brazuka para mostrar na view
                $date = DateTime::createFromFormat("Y-m-d H:i:s",$rotina->data_limite);
                $rotina->data_limite = $date->format('d/m/Y');

        		return view('plano.rotinas.editar-rotinas',['rotina' => $rotina,'planos' => $planos]);
            }
            else
            {
                return redirect()->route('dashboard');
            }
    	}

    	public function editRotina(Request $request,$id)
    	{
            if(Auth::user()->perfil == 2)
            {
        		$rotina = $this->rotina->find($id);

                $rotina->nome = $request["nome"];
                $rotina->plano_id = intval($request["plano"]);
                $rotina->realizada = intval($request["realizada"]);
                $date = DateTime::createFromFormat('d/m/Y',$request["data_limite"]);
                        
                $rotina->data_limite = $date->format("Y-m-d H:i:s");

        	    $rotina->changed_by = Auth::user()->id;

        		$rotina->update();

        		return redirect()->route('listar-rotinas');
            }
            else
            {
                return redirect()->route('dashboard');
            }
    	}

    	public function deleteRotina($id)
    	{
            if(Auth::user()->perfil == 2)
            {
        		$rotina = $this->rotina->find($id);
        	    $rotina->deleted_by = Auth::user()->id;
        	    $rotina->update();
        		$rotina->delete();

        		return redirect()->route('listar-rotinas');
            }
            else
            {
                return redirect()->route('dashboard');
            }
    	}
}
