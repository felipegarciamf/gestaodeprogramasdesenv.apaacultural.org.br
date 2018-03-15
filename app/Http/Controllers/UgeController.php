<?php

namespace App\Http\Controllers;

use App\Uge;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;

class UgeController extends Controller
{
    /*
     *@var Uge
     */
    private $objeto;

    public function __construct(Uge $uge)
    {
    	$this->uge = $uge;
    }

    public function cadastraUgeView()
    {
        if(Auth::user()->perfil == 2)
        {
    	   return view('plano.uge.cadastra-uge');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function listarUge()
    {
        if(Auth::user()->perfil == 2)
        {
        	//$usuarios = User::orderBy('created_at','desc')->get();
        	$uges = $this->uge->orderBy('created_at', 'DESC')->get();
        	return view('plano.uge.lista-uges')->with(['uges' => $uges]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function cadastraUge(Request $request)
    {
        if(Auth::user()->perfil == 2)
        {
        	$nome = $request['nome'];

        	$uge = $this->uge;

        	$uge->nome = $nome;
            $uge->created_by = Auth::user()->id;

        	$uge->save();

        	//Auth::login($user);

        	return redirect()->route('listar-uges');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editUgeView($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$uge = $this->uge->find($id);

        	return view('plano.uge.editar-uges',['uge' => $uge]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editUge(Request $request,$id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$uge = $this->uge->find($id);

        	$nome = $request['nome'];

        	$uge->nome = $nome;
            $uge->changed_by = Auth::user()->id;

        	$uge->update();

        	return redirect()->route('listar-uges');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function deleteUge($id)
    {

        if(Auth::user()->perfil == 2)
        {
        	$uge = $this->uge->find($id);
            $uge->deleted_by = Auth::user()->id;
            $uge->update();
        	$uge->delete();

        	return redirect()->route('listar-uges');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }
}
