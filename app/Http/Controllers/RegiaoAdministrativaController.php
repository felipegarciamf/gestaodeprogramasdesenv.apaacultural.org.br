<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\RegiaoAdministrativa;
use Illuminate\Support\Facades\Auth;

class RegiaoAdministrativaController extends Controller
{
    /*
     *@var engajamentopublico
     */
    private $regiaoadministrativa;

    public function __construct(RegiaoAdministrativa $regiaoadministrativa)
    {
    	$this->regiaoadministrativa = $regiaoadministrativa;
    }

    public function cadastraRegiaoAdministrativaView()
    {
        if(Auth::user()->perfil == 2)
        {
    	   return view('programa.regiao-administrativa.cadastra-regiao-administrativa');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function listarRegiaoAdministrativa()
    {
        if(Auth::user()->perfil == 2)
        {
        	//$usuarios = User::orderBy('created_at','desc')->get();
        	$regiaoadministrativas = $this->regiaoadministrativa->orderBy('created_at', 'DESC')->get();
        	return view('programa.regiao-administrativa.lista-regiao-administrativas')->with(['regiaoadministrativas' => $regiaoadministrativas]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function cadastraRegiaoAdministrativa(Request $request)
    {
        if(Auth::user()->perfil == 2)
        {
        	$nome = $request['nome'];

        	$regiaoadministrativa = $this->regiaoadministrativa;

        	$regiaoadministrativa->nome = $nome;
            $regiaoadministrativa->created_by = Auth::user()->id;

        	$regiaoadministrativa->save();

        	//Auth::login($user);

        	return redirect()->route('listar-regiao-administrativas');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editRegiaoAdministrativaView($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$regiaoadministrativa = $this->regiaoadministrativa->find($id);

        	return view('programa.regiao-administrativa.editar-regiao-administrativa',['regiaoadministrativa' => $regiaoadministrativa]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function editRegiaoAdministrativa(Request $request,$id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$regiaoadministrativa = $this->regiaoadministrativa->find($id);

        	$nome = $request['nome'];

        	$regiaoadministrativa->nome = $nome;
            $regiaoadministrativa->changed_by = Auth::user()->id;

        	$regiaoadministrativa->update();

        	return redirect()->route('listar-regiao-administrativas');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    public function deleteRegiaoAdministrativa($id)
    {
        if(Auth::user()->perfil == 2)
        {
        	$regiaoadministrativa = $this->regiaoadministrativa->find($id);

            $regiaoadministrativa->deleted_by = Auth::user()->id;
            $regiaoadministrativa->update();
        	$regiaoadministrativa->delete();

        	return redirect()->route('listar-regiao-administrativas');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }
}
