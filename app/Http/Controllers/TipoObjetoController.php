<?php

namespace App\Http\Controllers;

use App\TipoObjeto;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;

class TipoObjetoController extends Controller
{
    /*
     *@var TipoObjeto
     */
    private $tipoobjeto;

    public function __construct(TipoObjeto $tipoobjeto)
    {
    	$this->tipoobjeto = $tipoobjeto;
    }

    public function cadastraTipoObjetoView()
    {
        if(Auth::user()->perfil == 2)
        {
    	   return view('plano.tipo-objeto.cadastra-tipo-objeto');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function listarTipoObjeto()
    {
        if(Auth::user()->perfil == 2)
        {
    	   //$usuarios = User::orderBy('created_at','desc')->get();
    	   $tipoobjeto = $this->tipoobjeto->orderBy('created_at', 'DESC')->get();
    	   return view('plano.tipo-objeto.lista-tipo-objetos')->with(['tipoobjeto' => $tipoobjeto]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function cadastraTipoObjeto(Request $request)
    {
        if(Auth::user()->perfil == 2)
        {
        	$nome = $request['nome'];

        	$tipoobjeto = $this->tipoobjeto;

        	$tipoobjeto->nome = $nome;

            $tipoobjeto->created_by = Auth::user()->id;

        	$tipoobjeto->save();

        	//Auth::login($user);

        	return redirect()->route('listar-tipo-objetos');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editTipoObjetoView($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$tipoobjeto = $this->tipoobjeto->find($id);

        	return view('plano.tipo-objeto.editar-tipo-objetos',['tipoobjeto' => $tipoobjeto]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editTipoObjeto(Request $request,$id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$tipoobjeto = $this->tipoobjeto->find($id);

        	$nome = $request['nome'];

        	$tipoobjeto->nome = $nome;
            $tipoobjeto->changed_by = Auth::user()->id;

        	$tipoobjeto->update();

        	return redirect()->route('listar-tipo-objetos');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function deleteTipoObjeto($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$tipoobjeto = $this->tipoobjeto->find($id);
            $tipoobjeto->deleted_by = Auth::user()->id;
            $tipoobjeto->update();
        	$tipoobjeto->delete();

        	return redirect()->route('listar-tipo-objetos');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }
}
