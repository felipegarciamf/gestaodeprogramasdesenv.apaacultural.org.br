<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\TipoPublico;
use Illuminate\Support\Facades\Auth;

class TipoPublicoController extends Controller
{
    /*
     *@var tipopublico
     */
    private $tipopublico;

    public function __construct(TipoPublico $tipopublico)
    {
    	$this->tipopublico = $tipopublico;
    }

    public function cadastraTipoPublicoView()
    {
        if(Auth::user()->perfil == 2)
        {
    	   return view('programa.tipo-publico.cadastra-tipo-publico');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function listarTipoPublico()
    {
        if(Auth::user()->perfil == 2)
        {
        	//$usuarios = User::orderBy('created_at','desc')->get();
        	$tipopublicos = $this->tipopublico->orderBy('created_at', 'DESC')->get();
        	return view('programa.tipo-publico.lista-tipo-publicos')->with(['tipopublicos' => $tipopublicos]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function cadastraTipoPublico(Request $request)
    {
        if(Auth::user()->perfil == 2)
        {
        	$nome = $request['nome'];

        	$tipopublico = $this->tipopublico;

        	$tipopublico->nome = $nome;
            $tipopublico->created_by = Auth::user()->id;

        	$tipopublico->save();

        	//Auth::login($user);

        	return redirect()->route('listar-tipo-publicos');
        }
        else
        {
            return redirect()->route('dashboard');
        }
        
    }

    public function editTipoPublicoView($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$tipopublico = $this->tipopublico->find($id);

        	return view('programa.tipo-publico.editar-tipo-publico',['tipopublico' => $tipopublico]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editTipoPublico(Request $request,$id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$tipopublico = $this->tipopublico->find($id);

        	$nome = $request['nome'];

        	$tipopublico->nome = $nome;
            $tipopublico->changed_by = Auth::user()->id;

        	$tipopublico->update();

        	return redirect()->route('listar-tipo-publicos');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function deleteTipoPublico($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$tipopublico = $this->tipopublico->find($id);

            $tipopublico->deleted_by = Auth::user()->id;
            $tipopublico->update();
        	$tipopublico->delete();

        	return redirect()->route('listar-tipo-publicos');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }
}
