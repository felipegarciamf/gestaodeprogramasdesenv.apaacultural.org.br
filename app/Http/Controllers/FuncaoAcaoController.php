<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Auth;

use App\FuncaoAcao;

class FuncaoAcaoController extends Controller
{
    /*
     *@var funcao_acao
     */
    private $funcao_acao;

    public function __construct(FuncaoAcao $funcao_acao)
    {
    	$this->funcao_acao = $funcao_acao;
    }

    public function cadastraFuncaoAcaoView()
    {
        if(Auth::user()->perfil == 2)
        {
        	$funcao_acao = $this->funcao_acao->orderBy('created_at', 'DESC')->get();
        	return view('acao.funcao-acao.cadastra-funcao-acao',['funcao_acao' => $funcao_acao]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function listarFuncaoAcao()
    {
        if(Auth::user()->perfil == 2)
        {
        	//$usuarios = User::orderBy('created_at','desc')->get();
        	$funcao_acoes = $this->funcao_acao->orderBy('created_at', 'DESC')->get();
        	return view('acao.funcao-acao.listar-funcao-acao',['funcao_acoes' => $funcao_acoes]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function cadastraFuncaoAcao(Request $request)
    {
        if(Auth::user()->perfil == 2)
        {
            $funcao_acao = $this->funcao_acao;

        	$funcao_acao->nome = $request["nome"];

            $funcao_acao->created_by = Auth::user()->id;

        	$funcao_acao->save();

        	//Auth::login($user);

        	return redirect()->route('listar-funcao-acao');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editFuncaoAcaoView($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$funcao_acao = $this->funcao_acao->find($id);

        	return view('acao.funcao-acao.editar-funcao-acao',['funcao_acao' => $funcao_acao]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editFuncaoAcao(Request $request,$id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$funcao_acao = $this->funcao_acao->find($id);

        	$funcao_acao->nome = $request["nome"];

            $funcao_acao->changed_by = Auth::user()->id;

        	$funcao_acao->update();

        	return redirect()->route('listar-funcao-acao');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function deleteFuncaoAcao($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$funcao_acao = $this->funcao_acao->find($id);
            $funcao_acao->deleted_by = Auth::user()->id;
            $funcao_acao->update();
        	$funcao_acao->delete();

        	return redirect()->route('listar-funcao-acao');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }
}
