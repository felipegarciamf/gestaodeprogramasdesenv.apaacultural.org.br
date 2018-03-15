<?php

namespace App\Http\Controllers;

use App\Objeto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;

class ObjetoController extends Controller
{
    /*
     *@var Objeto
     */
    private $objeto;

    public function __construct(Objeto $objeto)
    {
    	$this->objeto = $objeto;
    }

    public function cadastraObjetoView()
    {
        if(Auth::user()->perfil == 2)
        {
    	   return view('plano.objeto.cadastra-objeto');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function listarObjeto()
    {
        if(Auth::user()->perfil == 2)
        {
        	//$usuarios = User::orderBy('created_at','desc')->get();
        	$objetos = $this->objeto->orderBy('created_at', 'DESC')->get();
        	return view('plano.objeto.lista-objetos')->with(['objetos' => $objetos]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function cadastraObjeto(Request $request)
    {
        if(Auth::user()->perfil == 2)
        {
        	$nome = $request['nome'];

        	$objeto = $this->objeto;

        	$objeto->nome = $nome;
            $objeto->created_by = Auth::user()->id;

        	$objeto->save();

        	//Auth::login($user);

        	return redirect()->route('listar-objetos');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editObjetoView($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$objeto = $this->objeto->find($id);

        	return view('plano.objeto.editar-objetos',['objeto' => $objeto]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editObjeto(Request $request,$id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$objeto = $this->objeto->find($id);

        	$nome = $request['nome'];

        	$objeto->nome = $nome;
            $objeto->changed_by = Auth::user()->id;

        	$objeto->update();

        	return redirect()->route('listar-objetos');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function deleteObjeto($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$objeto = $this->objeto->find($id);
            $objeto->deleted_by = Auth::user()->id;
            $objeto->update();
        	$objeto->delete();

        	return redirect()->route('listar-objetos');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }
}
