<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\EspecieAcao;
use Illuminate\Support\Facades\Auth;

class EspecieAcaoController extends Controller
{
    /*
     *@var especie_acao
     */
    private $especie_acao;

    public function __construct(EspecieAcao $especie_acao)
    {
    	$this->especie_acao = $especie_acao;
    }

    public function cadastraEspecieAcaoView()
    {
        if(Auth::user()->perfil == 2)
        {
    	   return view('acao.especie-acao.cadastra-especie-acao');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function listarEspecieAcao()
    {
        if(Auth::user()->perfil == 2)
        {
        	//$usuarios = User::orderBy('created_at','desc')->get();
        	$especie_acoes = $this->especie_acao->orderBy('created_at', 'DESC')->get();
        	return view('acao.especie-acao.lista-especie-acao')->with(['especie_acoes' => $especie_acoes]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function cadastraEspecieAcao(Request $request)
    {
        if(Auth::user()->perfil == 2)
        {
        	$nome = $request['nome'];

        	$especie_acao = $this->especie_acao;

        	$especie_acao->nome = $nome;
            $especie_acao->created_by = Auth::user()->id;

        	$especie_acao->save();

        	//Auth::login($user);

        	return redirect()->route('listar-especie-acao');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editEspecieAcaoView($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$especie_acao = $this->especie_acao->find($id);

        	return view('acao.especie-acao.editar-especie-acao',['especie_acao' => $especie_acao]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editEspecieAcao(Request $request,$id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$especie_acao = $this->especie_acao->find($id);

        	$nome = $request['nome'];

        	$especie_acao->nome = $nome;
            $especie_acao->changed_by = Auth::user()->id;

        	$especie_acao->update();

        	return redirect()->route('listar-especie-acao');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function deleteEspecieAcao($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$especie_acao = $this->especie_acao->find($id);

            $especie_acao->deleted_by = Auth::user()->id;
            $especie_acao->update();
        	$especie_acao->delete();

        	return redirect()->route('listar-especie-acao');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }
}
