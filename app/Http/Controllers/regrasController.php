<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\JsonResponse;

use App\Indicador;
use App\Acao;
use App\Plano;
use App\Regras;
use DB;

class regrasController extends Controller
{

    /*
     * @var regra
     */

    private $indicador;
    private $acao;
    private $plano;
    private $regra;

    public function __construct(Regras $regra,Plano $plano,Acao $acao, Indicador $indicador)
    {
    	$this->regra = $regra;
        $this->plano = $plano;
        $this->acao = $acao;
        $this->indicador = $indicador;
    }
	// rota cadastro de regra Silas
    public function cadastraRegraView()
    {
        if(Auth::user()->perfil == 2)
        {
            $planos = $this->plano->orderBy('created_at','DESC')->get();
            return view('regra.cadastra-regra',['planos' => $planos]);
        }
        else
        {
            return redirect()->route('dashboard');
        }

    }


    // Cadastrarr regra funcao
    public function cadastraRegra(Request $request)
    {
        if(Auth::user()->perfil == 2)
        {
            $regra = $this->regra;

            $regra->id = intval($request["id"]);
            $regra->codigo = $request["codigo_regra"];
            $regra->descricao = $request["descricao"];
            $regra->plano_id = intval($request["plano"]);


            $regra->created_by = Auth::user()->id;

            $regra->save();

            //Auth::login($user);

            return redirect()->route('listar-regras');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }


    // rota lista de regra Silas
    public function listaRegra(Request $request){
        if(Auth::user()->perfil == 2)
        {
            $regras = $this->regra->name($request->get('nome'))->orderBy('codigo', 'ASC', 'plano_id', 'ASC')->get();
            
            return view('regra.listar-regras',['regras' => $regras]);
        }
        else{
            return redirect()->route('dashboard');
        }
    }

    // editar regra View Silas
    public function editRegraView($id)
    {
        if(Auth::user()->perfil == 2)
        {
            $regra = $this->regra->find($id);
            $planos = $this->plano->orderBy('created_at','DESC')->get();

            return view('regra.editar-regra',['regra' => $regra, 'planos' => $planos]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    //editar regra (funcao para editar) Silas
    public function editRegra(Request $request,$id)
    {
        if(Auth::user()->perfil == 2)
        {
            $regra = $this->regra->find($id);


            $regra->codigo = $request["codigo_regra"];
            $regra->descricao = $request["descricao"];
            $regra->plano_id = intval($request["plano"]);

            $regra->changed_by = Auth::user()->id;
            $regra->update();

            //Auth::login($user);
            return redirect()->route('listar-regras');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }

    // deletar regra Silas
    public function deleteRegra($id)
    {
        if(Auth::user()->perfil == 2)
        {
            $regras = $this->regra->find($id);
            $regras->deleted_by = Auth::user()->id;
            $regras->update();
            $regras->delete();

            return redirect()->route('listar-regras');
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }
    

}
