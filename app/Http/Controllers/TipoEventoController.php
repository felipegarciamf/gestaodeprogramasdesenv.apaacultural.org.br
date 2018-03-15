<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\TipoEvento;
use Illuminate\Support\Facades\Auth;

class TipoEventoController extends Controller
{
    /*
     *@var tipoevento
     */
    private $tipoevento;

    public function __construct(TipoEvento $tipoevento)
    {
    	$this->tipoevento = $tipoevento;
    }

    public function cadastraTipoEventoView()
    {
        if(Auth::user()->perfil == 2)
        {
    	   return view('programa.tipo-evento.cadastra-tipo-evento');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function listarTipoEvento()
    {
        if(Auth::user()->perfil == 2)
        {
        	//$usuarios = User::orderBy('created_at','desc')->get();
        	$tipoeventos = $this->tipoevento->orderBy('created_at', 'DESC')->get();
        	return view('programa.tipo-evento.lista-tipo-eventos')->with(['tipoeventos' => $tipoeventos]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function cadastraTipoEvento(Request $request)
    {
        if(Auth::user()->perfil == 2)
        {
        	$nome = $request['nome'];

        	$tipoevento = $this->tipoevento;

        	$tipoevento->nome = $nome;
            $tipoevento->created_by = Auth::user()->id;

        	$tipoevento->save();

        	//Auth::login($user);

        	return redirect()->route('listar-tipo-eventos');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editTipoEventoView($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$tipoevento = $this->tipoevento->find($id);

        	return view('programa.tipo-evento.editar-tipo-evento',['tipoevento' => $tipoevento]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editTipoEvento(Request $request,$id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$tipoevento = $this->tipoevento->find($id);

        	$nome = $request['nome'];

        	$tipoevento->nome = $nome;
            $tipoevento->changed_by = Auth::user()->id;

        	$tipoevento->update();

        	return redirect()->route('listar-tipo-eventos');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function deleteTipoEvento($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$tipoevento = $this->tipoevento->find($id);

            $tipoevento->deleted_by = Auth::user()->id;
            $tipoevento->update();
        	$tipoevento->delete();

        	return redirect()->route('listar-tipo-eventos');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }
}
