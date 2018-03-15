<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Auth;

use App\LinguagemAcao;

class LinguagemAcaoController extends Controller
{
    /*
     *@var linguagem_acao
     */
    private $linguagem_acao;

    public function __construct(LinguagemAcao $linguagem_acao)
    {
    	$this->linguagem_acao = $linguagem_acao;
    }

    public function cadastraLinguagemAcaoView()
    {
        if(Auth::user()->perfil == 2)
        {
        	$linguagem_acao = $this->linguagem_acao->orderBy('created_at', 'DESC')->get();
        	return view('acao.linguagem-acao.cadastra-linguagem-acao',['linguagem_acao' => $linguagem_acao]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function listarLinguagemAcao()
    {
        if(Auth::user()->perfil == 2)
        {
        	//$usuarios = User::orderBy('created_at','desc')->get();
        	$linguagem_acoes = $this->linguagem_acao->orderBy('created_at', 'DESC')->get();
        	return view('acao.linguagem-acao.listar-linguagem-acao',['linguagem_acoes' => $linguagem_acoes]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function cadastraLinguagemAcao(Request $request)
    {
        if(Auth::user()->perfil == 2)
        {
            $linguagem_acao = $this->linguagem_acao;

        	$linguagem_acao->nome = $request["nome"];

            $linguagem_acao->created_by = Auth::user()->id;

        	$linguagem_acao->save();

        	//Auth::login($user);

        	return redirect()->route('listar-linguagem-acao');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editLinguagemAcaoView($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$linguagem_acao = $this->linguagem_acao->find($id);

        	return view('acao.linguagem-acao.editar-linguagem-acao',['linguagem_acao' => $linguagem_acao]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editLinguagemAcao(Request $request,$id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$linguagem_acao = $this->linguagem_acao->find($id);

        	$linguagem_acao->nome = $request["nome"];

            $linguagem_acao->changed_by = Auth::user()->id;

        	$linguagem_acao->update();

        	return redirect()->route('listar-linguagem-acao');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function deleteLinguagemAcao($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$linguagem_acao = $this->linguagem_acao->find($id);
            $linguagem_acao->deleted_by = Auth::user()->id;
            $linguagem_acao->update();
        	$linguagem_acao->delete();

        	return redirect()->route('listar-linguagem-acao');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }
}
