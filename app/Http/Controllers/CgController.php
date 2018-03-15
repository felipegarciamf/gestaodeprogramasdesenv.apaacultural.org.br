<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Cg;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class CgController extends Controller
{
    /*
     *@var Cg
     */
    private $cg;

    public function __construct(Cg $cg)
    {
    	$this->cg = $cg;
    }

    public function cadastraCgView()
    {
        if(Auth::user()->perfil == 2)
        {
    	   return view('plano.cg.cadastra-cg');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function listarCg()
    {
        if(Auth::user()->perfil == 2)
        {
        	//$usuarios = User::orderBy('created_at','desc')->get();
        	$cgs = $this->cg->orderBy('created_at', 'DESC')->get();
        	return view('plano.cg.lista-cgs')->with(['cgs' => $cgs]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function cadastraCg(Request $request)
    {
        if(Auth::user()->perfil == 2)
        {
        	$nome = $request['nome'];

        	$cg = $this->cg;

        	$cg->nome = $nome;
            $cg->created_by = Auth::user()->id;

        	$cg->save();

        	//Auth::login($user);

        	return redirect()->route('listar-cgs');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editCgView($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$cg = $this->cg->find($id);

        	return view('plano.cg.editar-cgs',['cg' => $cg]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editCg(Request $request,$id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$cg = $this->cg->find($id);

        	$nome = $request['nome'];

        	$cg->nome = $nome;
            $cg->changed_by = Auth::user()->id;

        	$cg->update();

        	return redirect()->route('listar-cgs');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function deleteCg($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$cg = $this->cg->find($id);

            $cg->deleted_by = Auth::user()->id;
            $cg->update();
        	$cg->delete();

        	return redirect()->route('listar-cgs');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }
}
