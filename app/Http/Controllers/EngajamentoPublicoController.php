<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\EngajamentoPublico;
use Illuminate\Support\Facades\Auth;

class EngajamentoPublicoController extends Controller
{
    /*
     *@var engajamentopublico
     */
    private $engajamentopublico;

    public function __construct(EngajamentoPublico $engajamentopublico)
    {
    	$this->engajamentopublico = $engajamentopublico;
    }

    public function cadastraEngajamentoPublicoView()
    {
        if(Auth::user()->perfil == 2)
        {
    	   return view('programa.engajamento-publico.cadastra-engajamento-publico');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function listarEngajamentoPublico()
    {
        if(Auth::user()->perfil == 2)
        {
        	//$usuarios = User::orderBy('created_at','desc')->get();
        	$engajamentopublicos = $this->engajamentopublico->orderBy('created_at', 'DESC')->get();
        	return view('programa.engajamento-publico.lista-engajamento-publicos')->with(['engajamentopublicos' => $engajamentopublicos]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function cadastraEngajamentoPublico(Request $request)
    {
        if(Auth::user()->perfil == 2)
        {
        	$nome = $request['nome'];

        	$engajamentopublico = $this->engajamentopublico;

        	$engajamentopublico->nome = $nome;
            $engajamentopublico->created_by = Auth::user()->id;

        	$engajamentopublico->save();

        	//Auth::login($user);

        	return redirect()->route('listar-engajamento-publicos');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editEngajamentoPublicoView($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$engajamentopublico = $this->engajamentopublico->find($id);

        	return view('programa.engajamento-publico.editar-engajamento-publico',['engajamentopublico' => $engajamentopublico]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editEngajamentoPublico(Request $request,$id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$engajamentopublico = $this->engajamentopublico->find($id);

        	$nome = $request['nome'];

        	$engajamentopublico->nome = $nome;
            $engajamentopublico->changed_by = Auth::user()->id;

        	$engajamentopublico->update();

        	return redirect()->route('listar-engajamento-publicos');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function deleteEngajamentoPublico($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$engajamentopublico = $this->engajamentopublico->find($id);

            $engajamentopublico->deleted_by = Auth::user()->id;
            $engajamentopublico->update();
        	$engajamentopublico->delete();

        	return redirect()->route('listar-engajamento-publicos');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }
}
