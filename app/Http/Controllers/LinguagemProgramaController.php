<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Auth;

use App\LinguagemPrograma;
class LinguagemProgramaController extends Controller
{
    /*
     *@var linguagem_programa
     */
    private $linguagem_programa;

    public function __construct(LinguagemPrograma $linguagem_programa)
    {
    	$this->linguagem_programa = $linguagem_programa;
    }

    public function cadastraLinguagemProgramaView()
    {
        if(Auth::user()->perfil == 2)
        {
        	$linguagem_programa = $this->linguagem_programa->orderBy('created_at', 'DESC')->get();
        	return view('programa.linguagem-programa.cadastra-linguagem-programa',['linguagem_programa' => $linguagem_programa]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function listarLinguagemPrograma()
    {
        if(Auth::user()->perfil == 2)
        {
        	//$usuarios = User::orderBy('created_at','desc')->get();
        	$linguagem_programas = $this->linguagem_programa->orderBy('created_at', 'DESC')->get();
        	return view('programa.linguagem-programa.listar-linguagem-programas',['linguagem_programas' => $linguagem_programas]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function cadastraLinguagemPrograma(Request $request)
    {
        if(Auth::user()->perfil == 2)
        {
            $linguagem_programa = $this->linguagem_programa;

        	$linguagem_programa->nome = $request["nome"];

            $linguagem_programa->created_by = Auth::user()->id;

        	$linguagem_programa->save();

        	//Auth::login($user);

        	return redirect()->route('listar-linguagem-programas');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editLinguagemProgramaView($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$linguagem_programa = $this->linguagem_programa->find($id);

        	return view('programa.linguagem-programa.editar-linguagem-programa',['linguagem_programa' => $linguagem_programa]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editLinguagemPrograma(Request $request,$id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$linguagem_programa = $this->linguagem_programa->find($id);

        	$linguagem_programa->nome = $request["nome"];

            $linguagem_programa->changed_by = Auth::user()->id;

        	$linguagem_programa->update();

        	return redirect()->route('listar-linguagem-programas');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function deleteLinguagemPrograma($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$linguagem_programa = $this->linguagem_programa->find($id);
            $linguagem_programa->deleted_by = Auth::user()->id;
            $linguagem_programa->update();
        	$linguagem_programa->delete();

        	return redirect()->route('listar-linguagem-programas');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }
}
