<?php

namespace App\Http\Controllers;

use App\Os;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;

class OsController extends Controller
{
    /*
     *@var Os
     */
    private $os;

    public function __construct(Os $os)
    {
    	$this->os = $os;
    }

    public function cadastraOsView()
    {
        if(Auth::user()->perfil == 2)
        {
    	   return view('plano.os.cadastra-os');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function listarOs()
    {
        if(Auth::user()->perfil == 2)
        {
        	//$usuarios = User::orderBy('created_at','desc')->get();
        	$oss = $this->os->orderBy('created_at', 'DESC')->get();
        	return view('plano.os.lista-oss',['oss' => $oss]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function cadastraOs(Request $request)
    {
        if(Auth::user()->perfil == 2)
        {
            $oss = $this->os;

        	$oss->nome = $request["nome"];
            $oss->created_by = Auth::user()->id;

        	$oss->save();

        	//Auth::login($user);

        	return redirect()->route('listar-oss');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editOsView($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$os = $this->os->find($id);

        	return view('plano.os.editar-oss',['os' => $os]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editOs(Request $request,$id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$os = $this->os->find($id);

        	$nome = $request['nome'];

        	$os->nome = $nome;
            $os->changed_by = Auth::user()->id;

        	$os->update();

        	return redirect()->route('listar-oss');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function deleteOs($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$os = $this->os->find($id);
            $os->deleted_by = Auth::user()->id;
            $os->update();
        	$os->delete();

        	return redirect()->route('listar-oss');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }
}
