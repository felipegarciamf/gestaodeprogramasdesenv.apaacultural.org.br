<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;

use App\Plano;
use App\Cg;
use App\Objeto;
use App\Os;
use App\TipoObjeto;
use App\Uge;
use DateTime;
use App\Programa;
use App\Indicador;
use App\Acao;
use App\Atividade;

use DB;

class PlanoController extends Controller
{
	/*
	 *@var plano, @var cgs, @var objetos, @var oss, @var tipoobjetos, @var uges
	 */
	private $plano;
	private $cg;
	private $objeto;
	private $os;
	private $tipoobjeto;
	private $uge;
	private $atividade;
	private $acao;
	private $indicador;
	private $programa;

	public function __construct(Plano $plano, Cg $cg, Objeto $objeto, Os $os, TipoObjeto $tipoobjeto, Uge $uge, Atividade $atividade, Acao $acao, Indicador $indicador, Programa $programa)
	{
		$this->plano = $plano;
		$this->cg = $cg;
		$this->objeto = $objeto;
		$this->os = $os;
		$this->tipoobjeto = $tipoobjeto;
		$this->uge = $uge;
		$this->atividade = $atividade;
		$this->acao = $acao;
		$this->indicador = $indicador;
		$this->programa = $programa;
	}

	public function cadastraPlanoView()
	{
		$cgs = $this->cg->orderBy('created_at', 'DESC')->get();
		$objetos = $this->objeto->orderBy('created_at', 'DESC')->get();
		$oss = $this->os->orderBy('created_at', 'DESC')->get();
		$tipoobjetos = $this->tipoobjeto->orderBy('created_at', 'DESC')->get();
		$uges = $this->uge->orderBy('created_at', 'DESC')->get();
		
		return view('plano.cadastra-plano',['cgs' => $cgs,'objetos' => $objetos,'oss' => $oss,'tipoobjetos' => $tipoobjetos,'uges' => $uges]);
	}

	
	public function cadastraPlano(Request $request)
	{
		if(Auth::user()->perfil == 2) 
		{
			$planos = $this->plano;
			

			
			$planos->nome = $request["nome"];
			$planos->uge_id = intval($request["uge"]);
			$planos->cg_id = intval($request["cg"]);
			$planos->objeto_id = intval($request["objeto"]);
			$planos->os_id = intval($request["os"]);
			$planos->tipoobjeto_id = intval($request["tipo_objeto"]);

			$date = DateTime::createFromFormat('d/m/Y',$request["data_limite"]);
			
	    	$planos->data_limite = $date->format("Y-m-d H:i:s");

		    $planos->created_by = Auth::user()->id;



			$planos->save();


			//Auth::login($user);

			return redirect()->route('listar-planos');
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	public function listarPlano()
	{
		if(Auth::user()->perfil == 2)
		{
			//$usuarios = User::orderBy('created_at','desc')->get();
			$planos = $this->plano->orderBy('created_at', 'DESC')->get();
			//transforma formato da data para o brazuka para mostrar na view
			foreach ($planos as $plano) {
			 $date = DateTime::createFromFormat("Y-m-d H:i:s",$plano->data_limite);
			 $plano->data_limite = $date->format('d/m/Y');   
			}
			return view('plano.lista-planos',['planos' => $planos]);
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	public function editPlanoView($id)
	{
		if(Auth::user()->perfil == 2)
		{
			$plano = $this->plano->find($id);

			//transforma formato da data para o brazuka para mostrar na view
	        $date = DateTime::createFromFormat("Y-m-d H:i:s",$plano->data_limite);
	        $plano->data_limite = $date->format('d/m/Y');

			$cgs = $this->cg->orderBy('created_at', 'DESC')->get();
			$objetos = $this->objeto->orderBy('created_at', 'DESC')->get();
			$oss = $this->os->orderBy('created_at', 'DESC')->get();
			$tipoobjetos = $this->tipoobjeto->orderBy('created_at', 'DESC')->get();
			$uges = $this->uge->orderBy('created_at', 'DESC')->get();
			$planos = $this->plano->orderBy('id','DESC')->get();
			return view('plano.editar-planos',['plano' => $plano,'cgs' => $cgs,'objetos' => $objetos,'oss' => $oss,'tipoobjetos' => $tipoobjetos,'uges' => $uges, 'planos' => $planos]);
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	public function editPlano(Request $request,$id)
	{
		if(Auth::user()->perfil == 2)
		{

			$plano = $this->plano->find($id);

			// puxando escolha de plano a ser copiado da view
			$planocopia = $request["plano-anterior"];
			
			// criando cópia de dados do plano selecionado
			if($planocopia != 0)
			{
				$copiaprogramas = DB::select('insert into programas select "",nome, '.$plano->id.' as plano_id, tipagem_id, status, descricao, created_at, updated_at, deleted_at, deleted_by, changed_by, created_by  from programas atividades where plano_id ='.$planocopia);
				//dd($copiaprogramas);

				

				$copiaacoes = DB::select('insert into acoes select "", codigo_acao, nome, '.$plano->id.' as plano_id, programa_id, especie_acao_id, linguagem_acao_id, funcao_acao_id, status, regiao_acao, created_at, updated_at, deleted_at, deleted_by, changed_by, created_by from acoes where plano_id = ' . $planocopia);
				//dd($copiaacoes);


				$copiaindicadores = DB::select('insert into indicadores select "", nome_indicador, acao_id, '.$plano->id.' as plano_id, regra_id, status, meta_1_tri, meta_2_tri, meta_3_tri, meta_4_tri, created_at, updated_at, deleted_at, deleted_by, changed_by, created_by from indicadores where plano_id = ' . $planocopia);
				//dd($copiaindicadores);

				
				$copiaatividade = DB::select('insert into atividades select "",data,horario,data_fim,nome,observacoes,local,capacidade,num_total_pessoas,num_total_tecnicos,num_total_artistas,inteira,meia,morador_entorno,prom,total_pagantes,convite_prod,convite_apaa,educativo_producao,educativo_apaa,atend_social_producao,atend_social_apaa,sessao_acessivel,acessibilidade_acompanhante,bilheteria,porcent_bilheteria_apaa,artista, '.$plano->id.' as plano_id, programa_id,tipo_publico_id,realizador_id,linguagem_programa_id,municipio_id,tipo_evento_id,created_at,updated_at,deleted_at,deleted_by,changed_by,created_by 
 from atividades where plano_id = ' .$planocopia);
				//dd($copiaatividade);/*
				
				//dd($teste);

			/*	Insert Into Tabela(Periodo, Classe, Centro, Tipo, Idcusto) Select Periodo, Classe, Centro, Tipo, '2' As Idcusto From OutraTabela */

			$plano->nome = $request["nome"];
			$plano->uge_id = $request["uge"];
			$plano->cg_id = $request["cg"];
			$plano->objeto_id = $request["objeto"];
			$plano->os_id = $request["os"];
			$plano->tipoobjeto_id = $request["tipo_objeto"];
 

			$date = DateTime::createFromFormat('d/m/Y',$request["data_limite"]);
			
	    	$plano->data_limite = $date->format("Y-m-d H:i:s");

		    $plano->changed_by = Auth::user()->id;

			$plano->update();
			}
			else
			{


			$plano->nome = $request["nome"];
			$plano->uge_id = $request["uge"];
			$plano->cg_id = $request["cg"];
			$plano->objeto_id = $request["objeto"];
			$plano->os_id = $request["os"];
			$plano->tipoobjeto_id = $request["tipo_objeto"];

			$date = DateTime::createFromFormat('d/m/Y',$request["data_limite"]);
			
	    	$plano->data_limite = $date->format("Y-m-d H:i:s");

		    $plano->changed_by = Auth::user()->id;

			$plano->update();
		}

			return redirect()->route('listar-planos');
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	public function deletePlano($id)
	{
		if(Auth::user()->perfil == 2)
		{
			$plano = $this->plano->find($id);
		    $plano->deleted_by = Auth::user()->id;
		    $plano->update();
			$plano->delete();

			return redirect()->route('listar-planos');
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}
}
