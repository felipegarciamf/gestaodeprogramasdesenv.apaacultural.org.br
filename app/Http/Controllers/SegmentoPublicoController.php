<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\SegmentoPublico;
use Illuminate\Support\Facades\Auth;

class SegmentoPublicoController extends Controller
{
    /*
     *@var segmentopublico
     */
    private $segmentopublico;

    public function __construct(SegmentoPublico $segmentopublico)
    {
    	$this->segmentopublico = $segmentopublico;
    }

    public function cadastraSegmentoPublicoView()
    {
        if(Auth::user()->perfil == 2)
        {
    	   return view('programa.segmento-publico.cadastra-segmento-publico');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function listarSegmentoPublico()
    {
        if(Auth::user()->perfil == 2)
        {
        	//$usuarios = User::orderBy('created_at','desc')->get();
        	$segmentopublicos = $this->segmentopublico->orderBy('created_at', 'DESC')->get();
        	return view('programa.segmento-publico.listar-segmento-publicos')->with(['segmentopublicos' => $segmentopublicos]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function cadastraSegmentoPublico(Request $request)
    {
        if(Auth::user()->perfil == 2)
        {
        	$nome = $request['nome'];

        	$segmentopublico = $this->segmentopublico;

        	$segmentopublico->nome = $nome;
            $segmentopublico->created_by = Auth::user()->id;

        	$segmentopublico->save();

        	//Auth::login($user);

        	return redirect()->route('listar-segmento-publicos');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editSegmentoPublicoView($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$segmentopublico = $this->segmentopublico->find($id);

        	return view('programa.segmento-publico.editar-segmento-publico',['segmentopublico' => $segmentopublico]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editSegmentoPublico(Request $request,$id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$segmentopublico = $this->segmentopublico->find($id);

        	$nome = $request['nome'];

        	$segmentopublico->nome = $nome;
            $segmentopublico->changed_by = Auth::user()->id;

        	$segmentopublico->update();

        	return redirect()->route('listar-segmento-publicos');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function deleteSegmentoPublico($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$segmentopublico = $this->segmentopublico->find($id);

            $segmentopublico->deleted_by = Auth::user()->id;
            $segmentopublico->update();
        	$segmentopublico->delete();

        	return redirect()->route('listar-segmento-publicos');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }
}
