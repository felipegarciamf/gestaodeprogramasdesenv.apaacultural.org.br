<?php

namespace App\Http\Controllers;

use App\Tipagem;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;

class TipagemController extends Controller
{
    /*
     *@var Tipagem
     */
    private $tipagem;

    public function __construct(Tipagem $tipagem)
    {
    	$this->tipagem = $tipagem;
    }

    public function cadastraTipagemView()
    {
        if(Auth::user()->perfil == 2)
        {
    	   return view('programa.tipagem.cadastra-tipagem');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function listarTipagem()
    {
        if(Auth::user()->perfil == 2)
        {
        	//$usuarios = User::orderBy('created_at','desc')->get();
        	$tipagens = $this->tipagem->orderBy('created_at', 'DESC')->get();
        	return view('programa.tipagem.lista-tipagem')->with(['tipagens' => $tipagens]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function cadastraTipagem(Request $request)
    {
        if(Auth::user()->perfil == 2)
        {
        	$nome = $request['nome'];

        	$tipagem = $this->tipagem;

        	$tipagem->nome = $nome;
            $tipagem->created_by = Auth::user()->id;

        	$tipagem->save();

        	//Auth::login($user);

        	return redirect()->route('listar-tipagens');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editTipagemView($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$tipagem = $this->tipagem->find($id);

        	return view('programa.tipagem.editar-tipagem',['tipagem' => $tipagem]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editTipagem(Request $request,$id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$tipagem = $this->tipagem->find($id);

        	$nome = $request['nome'];

        	$tipagem->nome = $nome;
            $tipagem->changed_by = Auth::user()->id;

        	$tipagem->update();

        	return redirect()->route('listar-tipagens');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function deleteTipagem($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$tipagem = $this->tipagem->find($id);
            $tipagem->deleted_by = Auth::user()->id;
            $tipagem->update();
        	$tipagem->delete();

        	return redirect()->route('listar-tipagens');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }
}
