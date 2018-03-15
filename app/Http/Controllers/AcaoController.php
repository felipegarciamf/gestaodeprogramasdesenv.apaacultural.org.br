<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use Illuminate\Http\JsonResponse;

use App\Acao;
use App\Plano;
use App\Programa;
use App\Indicador;
use App\EspecieAcao;
use App\LinguagemAcao;
use App\FuncaoAcao;
class AcaoController extends Controller
{
    	/*
    	 *@var plano, @var cgs, @var objetos, @var oss, @var tipoobjetos, @var uges
    	 */
    	private $acao;
    	private $plano;
        private $programa;
    	private $especie_acao;
    	private $linguagem_acao;
    	private $funcao_acao;


    	public function __construct(Acao $acao,Plano $plano,Programa $programa, EspecieAcao $especie_acao, LinguagemAcao $linguagem_acao, FuncaoAcao $funcao_acao)
    	{
    		$this->acao = $acao;
            $this->plano = $plano;
			$this->programa = $programa;
			$this->especie_acao = $especie_acao;
			$this->linguagem_acao = $linguagem_acao;
			$this->funcao_acao = $funcao_acao;
    	}

        public function ajaxAcaoFetchProgramasByPlano(Request $request)
        {
            if(Auth::user()->perfil == 2)
            {
                $programas = $this->programa->where('plano_id',$request->input('plano_id'))->get();

                if($request->ajax()){
                    return response()->json([
                        'programas' => $programas
                    ]);
                }
            }
            else
            {
                return redirect()->route('dashboard');
            }
        }

    	public function cadastraAcaoView()
    	{
            if(Auth::user()->perfil == 2)
            {
        		$planos = $this->plano->orderBy('created_at', 'DESC')->get();
        		$especies_acao = $this->especie_acao->orderBy('created_at', 'DESC')->get();
        		$linguagens_acao = $this->linguagem_acao->orderBy('created_at', 'DESC')->get();
        		$funcoes_acao = $this->funcao_acao->orderBy('created_at', 'DESC')->get();
        		return view('acao.cadastra-acao',['planos' => $planos,'especies_acao' => $especies_acao,'linguagens_acao' => $linguagens_acao,'funcoes_acao' => $funcoes_acao]);
            }
            else
            {
                return redirect()->route('dashboard');
            }
    	}

    	public function listarAcao(Request $request)
    	{
            if(Auth::user()->perfil == 2)
            {
        		$acoes = $this->acao->codigo($request->get('codigo'))->name($request->get('nome'))->programa_id($request->get('programa_id'))->orderBy('plano_id', 'DESC')->orderBy('id','ASC')->get();
        		
        		return view('acao.listar-acoes',['acoes' => $acoes]);
            }
            else
            {
                return redirect()->route('dashboard');
            }
    	}
        

    	public function cadastraAcao(Request $request)
    	{
            if(Auth::user()->perfil == 2)
            {
        	    $acao = $this->acao;

        	    $acao->codigo_acao = intval($request["codigo"]);
        	    $acao->nome = $request["nome"];
                $acao->plano_id = intval($request["plano"]);
        	    $acao->programa_id = intval($request["programa"]);
        	    $acao->especie_acao_id = intval($request["especie"]);
        	    $acao->linguagem_acao_id = intval($request["linguagem"]);
        	    $acao->funcao_acao_id = intval($request["funcao"]);
        	    $acao->regiao_acao = $request["regiao"];

        	    $acao->created_by = Auth::user()->id;

        		$acao->save();

        		//Auth::login($user);

        		return redirect()->route('listar-acoes');
            }
            else
            {
                return redirect()->route('dashboard');
            }
    	}

    	public function editAcaoView($id)
    	{
            if(Auth::user()->perfil == 2)
            {
        		$acao = $this->acao->find($id);
        		$planos = $this->plano->orderBy('created_at', 'DESC')->get();
                $programas = $this->programa->where('plano_id', $acao->plano->id)->get();
        		$especies_acao = $this->especie_acao->orderBy('created_at', 'DESC')->get();
        		$linguagens_acao = $this->linguagem_acao->orderBy('created_at', 'DESC')->get();
        		$funcoes_acao = $this->funcao_acao->orderBy('created_at', 'DESC')->get();
        		
        		return view('acao.editar-acao',['planos' => $planos,'especies_acao' => $especies_acao,'linguagens_acao' => $linguagens_acao,'programas' => $programas,'funcoes_acao' => $funcoes_acao,'acao' => $acao]);
            }
            else
            {
                return redirect()->route('dashboard');
            }
    	}

    	public function editAcao(Request $request,$id)
    	{
            if(Auth::user()->perfil == 2)
            {
        		$acao = $this->acao->find($id);

        		$acao->codigo_acao = intval($request["codigo"]);
        	    $acao->nome = $request["nome"];
        	    $acao->plano_id = intval($request["plano"]);
                $acao->programa_id = intval($request["programa"]);
        	    $acao->especie_acao_id = intval($request["especie"]);
        	    $acao->linguagem_acao_id = intval($request["linguagem"]);
        	    $acao->funcao_acao_id = intval($request["funcao"]);
        	    $acao->regiao_acao = $request["regiao"];

        	    $acao->changed_by = Auth::user()->id;

        		$acao->update();

        		return redirect()->route('listar-acoes');
            }
            else
            {
                return redirect()->route('dashboard');
            }
    	}

    	public function deleteAcao($id)
    	{
            if(Auth::user()->perfil == 2)
            {
        		$acao = $this->acao->find($id);
        	    $acao->deleted_by = Auth::user()->id;
        	    $acao->update();
        		$acao->delete();

        		return redirect()->route('listar-acoes');
            }
            else
            {
                return redirect()->route('dashboard');
            }
    	}
}
