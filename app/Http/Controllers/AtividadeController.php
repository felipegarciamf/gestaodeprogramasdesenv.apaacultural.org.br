<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

use League\Csv\Writer;

use App\Atividade;
use App\Plano;
use App\Programa;
use App\TipoPublico;
use App\Realizador;
use App\LinguagemPrograma;
use App\PermissoesUsuario;
//use App\GeneroLinguagem;


use App\Municipio;
use App\TipoEvento;
use DateTime;
use DB;


class AtividadeController extends Controller
{
	private $atividade;
	private $plano;
	private $programa;
	private $tipo_publico;
	private $realizador;
	private $linguagem_programa;
	private $permissoes_usuario;
	//private $genero_linguagem;
	private $municipio;
	private $tipo_evento;

	public function __construct(Atividade $atividade, Plano $plano, Programa $programa, TipoPublico $tipo_publico, Realizador $realizador, LinguagemPrograma $linguagem_programa, PermissoesUsuario $permissoes_usuario, Municipio $municipio, TipoEvento $tipo_evento)
	{
		$this->atividade = $atividade;
		$this->plano = $plano;
		$this->programa = $programa;
		$this->tipo_publico = $tipo_publico;
		$this->realizador = $realizador;
		$this->linguagem_programa = $linguagem_programa;
		$this->permissoes_usuario = $permissoes_usuario;
		//$this->genero_linguagem = $genero_linguagem;
		$this->municipio = $municipio;
		$this->tipo_evento = $tipo_evento;
	}

	//requisição ajax
	public function ajaxAtividadeFetchProgramasByPlano(Request $request)
	{
	    $programas = $this->programa->where('plano_id',$request->input('plano_id'))->get();

	    if($request->ajax()){
	        return response()->json([
	            'programas' => $programas
	        ]);
	    }
	}

	public function cadastraAtividadeView($id = null)
	{
		if(Auth::user()->perfil == 2)
		{
			$planos = $this->plano->orderBy('created_at', 'DESC')->get();
			$programas = $this->programa->orderBy('created_at', 'DESC')->get();
			$tipo_publicos = $this->tipo_publico->orderBy('created_at', 'DESC')->get();
			$realizadores = $this->realizador->orderBy('created_at', 'DESC')->get();
			$linguagens_programa = $this->linguagem_programa->orderBy('created_at', 'DESC')->get();
			//$generos_linguagem = $this->genero_linguagem->orderBy('created_at', 'DESC')->get();
			$municipios = $this->municipio->orderBy('nome', 'ASC')->get();
			$tipos_evento = $this->tipo_evento->orderBy('created_at', 'DESC')->get();
			return view('atividade.cadastra-atividade',['planos' => $planos,'programas' => $programas,'tipo_publicos' => $tipo_publicos,'realizadores' => $realizadores,'linguagens_programa' => $linguagens_programa,'municipios' => $municipios,'tipos_evento' => $tipos_evento]);
		}
		elseif(Auth::user()->perfil == 1)
		{
			if($id != null)
			{
				$programa = $this->programa->find($id);
				$tipo_publicos = $this->tipo_publico->orderBy('created_at', 'DESC')->get();
				$realizadores = $this->realizador->orderBy('created_at', 'DESC')->get();
				$linguagens_programa = $this->linguagem_programa->orderBy('created_at', 'DESC')->get();
				//$generos_linguagem = $this->genero_linguagem->orderBy('created_at', 'DESC')->get();
				$municipios = $this->municipio->orderBy('created_at', 'DESC')->get();
				$tipos_evento = $this->tipo_evento->orderBy('created_at', 'DESC')->get();

				return view('atividade.cadastra-atividade',['programa' => $programa,'tipo_publicos' => $tipo_publicos,'realizadores' => $realizadores,'linguagens_programa' => $linguagens_programa,'municipios' => $municipios,'tipos_evento' => $tipos_evento]);
			}
		}
	}

	public function listarAtividade(Request $request)
	{
		$atividades = $this->atividade->dateIni($request->get('data'))->name($request->get('nome'))->municipio($request->get('municipio'))->get();
		//transforma formato da data para o brazuka para mostrar na view
		foreach ($atividades as $atividade) {
		 $date = DateTime::createFromFormat("Y-m-d H:i:s",$atividade->data);
		 $atividade->data = $date->format('d/m/Y');
		}
		return view('atividade.listar-atividades',['atividades' => $atividades]);
	}

	public function listarPlanoAtividade()
	{
		// permite nivel dois ver todos os dados
		 if(Auth::user()->perfil == 2)
        {
            $planos = $this->plano->orderBy('created_at','desc')->get();
        }
        // trava por permissao
        else
        {

		$usuario = Auth::user()->id;
		$permissoes = $this->permissoes_usuario->select(DB::raw('plano_id,programa_id'))->where('user_id',$usuario)->groupBy('plano_id','programa_id')->get();

		$planos_id = array();
		$ultimoPlano = -1;
		foreach($permissoes as $permissao)
		{
			if($permissao->plano_id != $ultimoPlano)
			{
				array_push($planos_id,$permissao->plano_id);
				$ultimoPlano = $permissao->plano_id;
			}
		}
		//dd($planos_id);
		//die();

		$planos = $this->plano->whereIn('id',$planos_id)->orderBy('created_at','desc')->get();

		}

		return view('atividade.listar-planos-atividades',['planos' => $planos]);
	}



	public function listarProgramaAtividade(Request $request, $id)
	{

		if(Auth::user()->perfil == 2)
        {
            $programas = $this->programa->name($request->get('nome'))->where('plano_id',$id)->orderBy('created_at','desc')->get();
        }
        else
        {
		$usuario = Auth::user()->id;
		$permissoes = $this->permissoes_usuario->select(DB::raw('plano_id,programa_id'))->where('user_id',$usuario)->groupBy('plano_id','programa_id')->get();

		$programas_id = array();
		$ultimoPrograma = -1;
		foreach($permissoes as $permissao)
		{
			if($permissao->programa_id != $ultimoPrograma)
			{
				array_push($programas_id,$permissao->programa_id);
				$ultimoPrograma = $permissao->programa_id;
			}
		}
		//dd($programas_id);
		//die();
		$programas = $this->programa->name($request->get('nome'))->where('plano_id',$id)->whereIn('id',$programas_id)->orderBy('created_at','desc')->get();
		}


		return view('atividade.listar-programas-atividades',['programas' => $programas]);
	}
	// lista de atividades por programa
	public function listarAtividadesPorPrograma(Request $request, $id)
	{
		// teste do request
		//dd($request->get('nome'));
		//dd($request->get('date'));

		// name(request) esta buscando o nome e encaminhando a query para página
		$atividades = $this->atividade
		->dateini($request->get('data'))
		->name($request->get('nome'))
		->where('programa_id',$id)
		->municipio($request->get('municipio'))->get();

		// faz a formatação o date para dd/MM/YYYY
		foreach ($atividades as $atividade) {
		 $date = DateTime::createFromFormat("Y-m-d H:i:s",$atividade->data);
		 $atividade->data = $date->format('d/m/Y');
		}


		return view('atividade.listar-atividades-por-programa',['atividades' => $atividades,'programa_id' => $id]);
	}

	// lista relatorio de atividades totais
	public function listarRelatorioProgramasAtividade(Request $request)
	{

		$atividades = $this->atividade
		->dateIni($request->get('dataini'))
		->dateFim($request->get('datafim'))
		->name($request->get('nome'))
		->municipio($request->get('municipio'))
		->orderBy('data','ASC')->get();

		//dd($atividades);


		return view('atividade.listar-atividade-relatorio-por-plano', ['atividades' => $atividades]);
	}

	// extraindo relatorio em csv de atividade
	public function extrairRelatorioProgramaAtividade(Request $request){

		$atividades = atividade::join('planos', 'planos.id', '=', 'atividades.plano_id')
			->join('programas','programas.id', '=', 'atividades.programa_id')
			->join('municipios', 'municipios.id', '=', 'atividades.municipio_id')
			->join('tipo_eventos','tipo_eventos.id','=','atividades.tipo_evento_id')
			->join('realizadores', 'realizadores.id','=','atividades.realizador_id')
			->select('atividades.nome as nome_atividade', 'planos.nome as nome_plano','programas.nome as programa_nome','atividades.data', 'atividades.data_fim','atividades.horario', 'atividades.artista', 'municipios.nome as municipio_nome', 'atividades.local', 'atividades.num_total_pessoas', 'tipo_eventos.nome as nome_evento','atividades.num_total_artistas', 'realizadores.nome as realizador_nome')
			->orderBy( 'atividades.plano_id', 'DESC')->get();


		// puxando csv de relatório
		//dd(Schema::getColumnListing('atividades'));
		$csv = Writer::createFromFileObject(new \SplTempFileObject());
		$csv->insertOne(['Nome','Plano', 'Programa', 'Data', 'Data Fim','Horario', 'Artista', 'Municipio', 'Local', 'Numero Pessoas', 'Tipo de Evento', 'Numero de Artistas', 'Realizador']);
		
		foreach($atividades as $atividade){
		$csv->insertOne($atividade->toArray());
		}


		$csv->output('atividades_'.Carbon::now().'.csv');
	}

	/*public function cadastrarAtividadesPorProgramaView($id)
	{
		$programa = $this->programa->find($id);
		$tipo_publicos = $this->tipo_publico->orderBy('created_at', 'DESC')->get();
		$realizadores = $this->realizador->orderBy('created_at', 'DESC')->get();
		$linguagens_programa = $this->linguagem_programa->orderBy('created_at', 'DESC')->get();
		//$generos_linguagem = $this->genero_linguagem->orderBy('created_at', 'DESC')->get();
		$municipios = $this->municipio->orderBy('created_at', 'DESC')->get();
		$tipos_evento = $this->tipo_evento->orderBy('created_at', 'DESC')->get();

		return view('atividade.cadastrar-atividades-por-programa',['programa' => $programa,'tipo_publicos' => $tipo_publicos,'realizadores' => $realizadores,'linguagens_programa' => $linguagens_programa,'municipios' => $municipios,'tipos_evento' => $tipos_evento]);
	}

	public function cadastrarAtividadesPorPrograma(Request $request)
	{
		$atividade = $this->atividade;


		$atividade->data = $request["nome"];

		$dateData = DateTime::createFromFormat('d/m/Y',$request["data"]);
    	$atividade->data = $dateData->format("Y-m-d H:i:s");

		$dateData = DateTime::createFromFormat('d/m/Y',$request["data"]);
    	$atividade->data = $dateData->format("Y-m-d H:i:s");

		$atividade->horario = $request["horario"];
		$atividade->dia_semana = intval($request["dia_semana"]);

		$dateDataFim = DateTime::createFromFormat('d/m/Y',$request["data_fim"]);
    	$atividade->data_fim = $dateDataFim->format("Y-m-d H:i:s");

		$atividade->local = $request["local"];
		$atividade->capacidade = intval($request["capacidade"]);
		$atividade->num_total_pessoas = intval($request["num_total_pessoas"]);
		$atividade->num_total_tecnicos = intval($request["num_total_tecnicos"]);
		$atividade->num_total_artistas = intval($request["num_total_artistas"]);
		$atividade->inteira = intval($request["inteiras"]);
		$atividade->meia = intval($request["meias"]);
		$atividade->morador_entorno = intval($request["moradores_entorno"]);
		$atividade->prom = intval($request["prom"]);
		$atividade->total_pagantes = intval($request["total_pagantes"]);
		$atividade->convite_prod = intval($request["convites_prod"]);
		$atividade->convite_apaa = intval($request["convites_apaa"]);
		$atividade->educativo_producao = intval($request["educativo_producao"]);
		$atividade->educativo_apaa = intval($request["educativo_apaa"]);
		$atividade->atend_social_producao = intval($request["atend_social_producao"]);
		$atividade->atend_social_apaa = intval($request["atend_social_apaa"]);
		$atividade->sessao_acessivel = $request["sessao_acessivel"];
		$atividade->acessibilidade_acompanhante = $request["acessibilidade_acompanhante"];
		$atividade->bilheteria = $request["bilheteria"];
		$atividade->porcent_bilheteria_apaa = $request["porcentagem_bilheteria"];
		$atividade->artista = $request["artista"];

		$programaFind = $this->programa->find($request["programa"]);
		$atividade->programa_id = $programaFind->id;
		$atividade->plano_id = $programaFind->plano->id;
		$atividade->tipo_publico_id = intval($request["tipo_publico"]);
		$atividade->realizador_id = intval($request["realizador"]);
		$atividade->linguagem_programa_id = intval($request["linguagem_programa"]);
		//$atividade->genero_linguagem_id = intval($request["genero_linguagem"]);
		$atividade->municipio_id = intval($request["municipio"]);
		$atividade->tipo_evento_id = intval($request["tipo_evento"]);
		$atividade->observacoes = $request["observacoes"];

	    $atividade->created_by = Auth::user()->id;

		$atividade->save();

		return redirect()->route('listar-atividades-por-programa',['id' =>$programaFind->id]);
	}*/

	public function cadastraAtividade(Request $request)
	{
		/*if(Auth::user()->perfil == 2)
		{*/
			$atividade = $this->atividade;

		    $atividade->nome = $request["nome"];

			$dateData = DateTime::createFromFormat('d/m/Y',$request["data"]);
	    	$atividade->data = $dateData->format("Y-m-d H:i:s");

			$atividade->horario = $request["horario"];


			$dateDataFim = DateTime::createFromFormat('d/m/Y',$request["data_fim"]);
	    	$atividade->data_fim = $dateDataFim->format("Y-m-d H:i:s");

			$atividade->local = $request["local"];
			$atividade->capacidade = intval($request["capacidade"]);
			$atividade->num_total_pessoas = intval($request["num_total_pessoas"]);
			$atividade->num_total_tecnicos = intval($request["num_total_tecnicos"]);
			$atividade->num_total_artistas = intval($request["num_total_artistas"]);
			$atividade->inteira = intval($request["inteiras"]);
			$atividade->meia = intval($request["meias"]);
			$atividade->morador_entorno = intval($request["moradores_entorno"]);
			$atividade->prom = intval($request["prom"]);
			$atividade->total_pagantes = intval($request["total_pagantes"]);
			$atividade->convite_prod = intval($request["convites_prod"]);
			$atividade->convite_apaa = intval($request["convites_apaa"]);
			$atividade->educativo_producao = intval($request["educativo_producao"]);
			$atividade->educativo_apaa = intval($request["educativo_apaa"]);
			$atividade->atend_social_producao = intval($request["atend_social_producao"]);
			$atividade->atend_social_apaa = intval($request["atend_social_apaa"]);
			$atividade->sessao_acessivel = $request["sessao_acessivel"];
			$atividade->acessibilidade_acompanhante = $request["acessibilidade_acompanhante"];
			$atividade->bilheteria = $request["bilheteria"];
			$atividade->porcent_bilheteria_apaa = $request["porcentagem_bilheteria"];
			$atividade->artista = $request["artista"];
			$atividade->plano_id = intval($request["plano"]);
			$atividade->programa_id = intval($request["programa"]);
			$atividade->tipo_publico_id = intval($request["tipo_publico"]);
			$atividade->realizador_id = intval($request["realizador"]);
			$atividade->linguagem_programa_id = intval($request["linguagem_programa"]);
			//$atividade->genero_linguagem_id = intval($request["genero_linguagem"]);
			$atividade->municipio_id = intval($request["municipio"]);
			$atividade->tipo_evento_id = intval($request["tipo_evento"]);
			$atividade->observacoes = $request["observacoes"];

		    $atividade->created_by = Auth::user()->id;

			$atividade->save();
			//Auth::login($user);

			if(Auth::user()->perfil == 2)
			{
				return redirect()->route('listar-atividades');
			}
			elseif(Auth::user()->perfil == 1)
			{
				return redirect()->route('listar-atividades-por-programa',['id' => intval($request["programa"])]);
			}

		/*}
		elseif(Auth::user()->perfil == 1)
		{
			$atividade = $this->atividade;

		    $atividade->nome = $request["nome"];

			$dateData = DateTime::createFromFormat('d/m/Y',$request["data"]);
	    	$atividade->data = $dateData->format("Y-m-d H:i:s");

			$atividade->horario = $request["horario"];
			$atividade->dia_semana = intval($request["dia_semana"]);

			$dateDataFim = DateTime::createFromFormat('d/m/Y',$request["data_fim"]);
	    	$atividade->data_fim = $dateDataFim->format("Y-m-d H:i:s");

			$atividade->local = $request["local"];
			$atividade->capacidade = intval($request["capacidade"]);
			$atividade->num_total_pessoas = intval($request["num_total_pessoas"]);
			$atividade->num_total_tecnicos = intval($request["num_total_tecnicos"]);
			$atividade->num_total_artistas = intval($request["num_total_artistas"]);
			$atividade->inteira = intval($request["inteiras"]);
			$atividade->meia = intval($request["meias"]);
			$atividade->morador_entorno = intval($request["moradores_entorno"]);
			$atividade->prom = intval($request["prom"]);
			$atividade->total_pagantes = intval($request["total_pagantes"]);
			$atividade->convite_prod = intval($request["convites_prod"]);
			$atividade->convite_apaa = intval($request["convites_apaa"]);
			$atividade->educativo_producao = intval($request["educativo_producao"]);
			$atividade->educativo_apaa = intval($request["educativo_apaa"]);
			$atividade->atend_social_producao = intval($request["atend_social_producao"]);
			$atividade->atend_social_apaa = intval($request["atend_social_apaa"]);
			$atividade->sessao_acessivel = $request["sessao_acessivel"];
			$atividade->acessibilidade_acompanhante = $request["acessibilidade_acompanhante"];
			$atividade->bilheteria = $request["bilheteria"];
			$atividade->porcent_bilheteria_apaa = $request["porcentagem_bilheteria"];
			$atividade->artista = $request["artista"];
			$atividade->tipo_publico_id = intval($request["tipo_publico"]);
			$atividade->realizador_id = intval($request["realizador"]);
			$atividade->linguagem_programa_id = intval($request["linguagem_programa"]);
			//$atividade->genero_linguagem_id = intval($request["genero_linguagem"]);
			$atividade->municipio_id = intval($request["municipio"]);
			$atividade->tipo_evento_id = intval($request["tipo_evento"]);
			$atividade->observacoes = $request["observacoes"];

		    $atividade->created_by = Auth::user()->id;

			$atividade->save();
			//Auth::login($user);

			return redirect()->route('listar-atividades-por-programa',['id' => $atividade->programa->id]);
		}*/
	}

	public function editAtividadeView($id)
	{
		if(Auth::user()->perfil == 2)
		{
			$atividade = $this->atividade->find($id);
			//transforma formato da data para o brazuka para mostrar na view
			$date = DateTime::createFromFormat("Y-m-d H:i:s",$atividade->data);
			$date2 = DateTime::createFromFormat("Y-m-d H:i:s",$atividade->data_fim);
			$atividade->data = $date->format('d/m/Y');
			$atividade->data_fim = $date2->format('d/m/Y');
			$planos = $this->plano->orderBy('created_at', 'DESC')->get();
		    $programas = $this->programa->orderBy('created_at', 'DESC')->get();
			$tipos_publico = $this->tipo_publico->orderBy('created_at', 'DESC')->get();
			$realizadores = $this->realizador->orderBy('created_at', 'DESC')->get();
			$linguagens_programa = $this->linguagem_programa->orderBy('created_at', 'DESC')->get();
			$municipios = $this->municipio->orderBy('created_at', 'DESC')->get();
			$tipos_evento = $this->tipo_evento->orderBy('created_at', 'DESC')->get();

			return view('atividade.editar-atividade',['planos' => $planos,'programas' => $programas,'tipos_publico' => $tipos_publico,'realizadores' => $realizadores,'linguagens_programa' => $linguagens_programa,'municipios' => $municipios,'tipos_evento' => $tipos_evento,'atividade' => $atividade]);
		}
		elseif(Auth::user()->perfil == 1)
		{
			$atividade = $this->atividade->find($id);
			//transforma formato da data para o brazuka para mostrar na view
			$date = DateTime::createFromFormat("Y-m-d H:i:s",$atividade->data);
			$date2 = DateTime::createFromFormat("Y-m-d H:i:s",$atividade->data_fim);
			$atividade->data = $date->format('d/m/Y');
			$atividade->data_fim = $date2->format('d/m/Y');
			//$planos = $this->plano->orderBy('created_at', 'DESC')->get();
		    //$programas = $this->programa->orderBy('created_at', 'DESC')->get();
			$tipos_publico = $this->tipo_publico->orderBy('created_at', 'DESC')->get();
			$realizadores = $this->realizador->orderBy('created_at', 'DESC')->get();
			$linguagens_programa = $this->linguagem_programa->orderBy('created_at', 'DESC')->get();
			$municipios = $this->municipio->orderBy('created_at', 'DESC')->get();
			$tipos_evento = $this->tipo_evento->orderBy('created_at', 'DESC')->get();

			return view('atividade.editar-atividade',['tipos_publico' => $tipos_publico,'realizadores' => $realizadores,'linguagens_programa' => $linguagens_programa,'municipios' => $municipios,'tipos_evento' => $tipos_evento,'atividade' => $atividade]);
		}
	}

	public function editAtividade(Request $request,$id)
	{
		$atividade = $this->atividade->find($id);

		//dd($request->all());
		//die();

		$atividade->nome = $request["nome"];

		$dateData = DateTime::createFromFormat('d/m/Y',$request["data"]);
    	$atividade->data = $dateData->format("Y-m-d H:i:s");

		$atividade->horario = $request["horario"];

		$dateDataFim = DateTime::createFromFormat('d/m/Y',$request["data_fim"]);
    	$atividade->data_fim = $dateDataFim->format("Y-m-d H:i:s");

		$atividade->local = $request["local"];
		$atividade->capacidade = intval($request["capacidade"]);
		$atividade->num_total_pessoas = intval($request["num_total_pessoas"]);
		$atividade->num_total_tecnicos = intval($request["num_total_tecnicos"]);
		$atividade->num_total_artistas = intval($request["num_total_artistas"]);
		$atividade->inteira = intval($request["inteiras"]);
		$atividade->meia = intval($request["meias"]);
		$atividade->morador_entorno = intval($request["moradores_entorno"]);
		$atividade->prom = intval($request["prom"]);
		$atividade->total_pagantes = intval($request["total_pagantes"]);
		$atividade->convite_prod = intval($request["convites_prod"]);
		$atividade->convite_apaa = intval($request["convites_apaa"]);
		$atividade->educativo_producao = intval($request["educativo_producao"]);
		$atividade->educativo_apaa = intval($request["educativo_apaa"]);
		$atividade->atend_social_producao = intval($request["atend_social_producao"]);
		$atividade->atend_social_apaa = intval($request["atend_social_apaa"]);
		$atividade->sessao_acessivel = $request["sessao_acessivel"];
		$atividade->acessibilidade_acompanhante = $request["acessibilidade_acompanhante"];
		$atividade->bilheteria = $request["bilheteria"];
		$atividade->porcent_bilheteria_apaa = $request["porcentagem_bilheteria_apaa"];
		$atividade->artista = $request["artista"];
		if(Auth::user()->perfil == 2)
		{
			$atividade->plano_id = intval($request["plano"]);
			$atividade->programa_id = intval($request["programa"]);
		}
		$atividade->tipo_publico_id = intval($request["tipo_publico"]);
		$atividade->realizador_id = intval($request["realizador"]);
		$atividade->linguagem_programa_id = intval($request["linguagem"]);
		//$atividade->genero_linguagem_id = intval($request["genero_linguagem"]);
		$atividade->municipio_id = intval($request["municipio"]);
		$atividade->tipo_evento_id = intval($request["tipo_evento"]);
		$atividade->observacoes = $request["observacoes"];

	    $atividade->changed_by = Auth::user()->id;

		$atividade->update();

		if(Auth::user()->perfil == 2)
		{
			return redirect()->route('listar-atividades');
		}
		elseif(Auth::user()->perfil == 1)
		{
			return redirect()->route('listar-atividades-por-programa',['id' => $atividade->programa_id]);
		}
	}

	public function deleteAtividade($id)
	{

		$atividade = $this->atividade->find($id);
	    $atividade->deleted_by = Auth::user()->id;
	    $atividade->update();
		$atividade->delete();

		return redirect()->route('listar-atividades');
	}
}
