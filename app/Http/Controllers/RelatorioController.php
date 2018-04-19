<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Auth;



use App\Atividade;
use App\Acao;
use App\Programa;
use App\Indicador;
use App\Plano;
use App\Regras;
use App\PermissoesUsuario;
use Datetime;
//use DB;
use Illuminate\Support\Facades\DB;


//CARBON É USADO PARA DATETIMES E TAL
//use Carbon\Carbon;

class RelatorioController extends Controller {
    private $atividade;
    private $acao;
    private $programa;
    private $indicador;
    private $plano;
    private $regra;
    private $permissoes_usuario;

    public function __construct(Plano $plano,Atividade $atividade, Acao $acao, Programa $programa, Indicador $indicador, Regras $regra, PermissoesUsuario $permissoes_usuario)   {
    	$this->atividade = $atividade;
    	$this->acao = $acao;
    	$this->programa = $programa;
    	$this->indicador = $indicador;
    	$this->plano = $plano;
        $this->regra = $regra;
        $this->permissoes_usuario = $permissoes_usuario;
    }

    public function editIndicador(Request $request,$id)  {
        
            $indicador = $this->indicador->find($id);


            $indicador->regra_id = intval($request["regra"]);
            $indicador->nome_indicador = $request["nome_indicador"];
            $indicador->plano_id = intval($request["plano"]);
            $indicador->acao_id = intval($request["acao"]);
            $indicador->meta_1_tri = $request["meta_1_tri"];
            $indicador->meta_2_tri = $request["meta_2_tri"];
            $indicador->meta_3_tri = $request["meta_3_tri"];
            $indicador->meta_4_tri = $request["meta_4_tri"];
            $indicador->justificativa = $request["justificativa"];
            $indicador->justificativa2 = $request["justificativa2"];


            $indicador->changed_by = Auth::user()->id;

            $indicador->update();

            return redirect()->route('relatorio-trimestral');
    }






    public function PlanoslistarRelatorio(){

       if(Auth::user()->perfil == 2)
        {
            $planos = $this->plano->orderBy('created_at','DESC')->get();
            return view('relatorio.lista-relatorio',['planos' => $planos]);
        }
        else
        {
            return redirect()->route('dashboard');
        }

    }
    // Extração de relatório trimestral (terminando)
    public function extraiRelatorioTrimestral(Request $request, $id){

        if(Auth::user()->perfil == 2)
        {
            $csv = Writer::createFromFileObject(new \SplTempFileObject());
            $csv->insertOne(['Programa','Ação','Regras', 'Meta','1º Trimestre','Meta','2º Trimestre','Meta','3º Trimestre','Meta','4º Trimestre','Resultado']);


        }

    }

    public function listarRelatorio(Request $request, $id) {

        if(Auth::user()->perfil == 2)
        {
            //dd($request->get('nome'));

            $acoes = $this->acao->programa_id($request->get('programa'))->get();
            $programas = $this->programa->where('plano_id',$id)->orderBy('created_at','ASC')->get();
            $regras = $this->regra->where('plano_id',$id)->orderBy('created_at','ASC');
            $indicadores = $this->indicador->where('plano_id',$id)->orderBy('created_at','ASC')->get();
            $atividades = $this->atividade->where('plano_id',$id)->orderBy('created_at','ASC')->get();
            $planos = $this->plano->where('id',$id)->orderBy('created_at','ASC')->get();
            $arrayInfo = array(array());
            $count = 0;


            // array de acoes transformando variavel ação para dentro do foreach
            foreach($acoes as $acao)
            {

                if($acao->codigo_acao != ''){


                // se o codigo da ação for igual aos cases
                while($acao->codigo_acao != 0)
                {

                        $campos = array(array());
                        /*$campos[0]["nome"] = 0;
                        $campos[0]["alcancado"] = 0;
                        $campos[0]["meta"] = 0;*/
                        $countCampos = 0;


                        // cria lista de array de indicadores transformando em variavel indicador dentro do foreach

                        foreach($indicadores as $indicador)
                        {

                            // se o codigo de ação do indicador for o mesmo do codigo de ação da ação faça

                            if($indicador->acao->codigo_acao == $acao->codigo_acao)
                            {

                                // variavel do nome do indicador para os casos

                                switch(strtoupper($indicador->regra->descricao))
                                {

                                    // caso com um nome que seja semelhante a variavel nome do indicador


                                    case strtoupper("Número de municípios"):


                                        $ultimoIdMunicipio = -1;
                                        //$numeroDeMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;

                                        // lista de atividades transformando na variavel atividade para dentro do foreach

                                        foreach($atividades as $atividade)
                                        {

                                            // se ID de municipio dentro de atividade for diferente do ultimo municipio e se o ID do programa da ação for igual ao ID da atividade faça

                                            if($atividade->municipio->id != $ultimoIdMunicipio && $acao->programa->id == $atividade->programa->id)
                                            {

                                                // transformando a data da atividade em unidade de data para utilizar apenas os números do mês e
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimoIdMunicipio = $atividade->municipio->id;
                                                //$numeroDeMunicipio++;
                                            }
                                        }
                                        //echo "Numero de Municipios: ".$numeroDeMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de apresentações realizadas diretamente pela OS"):



                                        //$totalApresentacoesOs = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoesOs++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoesOs;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de apresentações realizadas em parceria com os municípios e instituições"):
                                        //$totalApresentacoesOutrasOss = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id != 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoesOutrasOss++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                            }
                                        }
                                        //echo "Número de Apresentações por outras OSs: ".$totalApresentacoesOutrasOss."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoesOutrasOss;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    //INDICADOR PARA A PORCENTAGEM DE ARTISTAS QUE NÃO SE APRESENTARAM NA ULTIMA EDICAO
                                    //SERÁ UM CAMPO ABERTO NO RELATÓRIO
                                    case strtoupper("Porcentagem de artistas que não se apresentaram na última edição"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;


                                    case strtoupper("Número Total de Público"):

                                       //itamar

                                      // var_dump("$indicador");
                                      // var_dump("$acao");

                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id != 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPessoas += $ativadade->num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;
                                    case strtoupper("Número de apresentações realizadas diretamente pela OS"):
                                        $ultimoIdMunicipio = -1;
                                        //$numeroDeMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->municipio->id != $ultimoIdMunicipio && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numeroDeMunicipio++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimoIdMunicipio = $atividade->municipio->id;
                                            }
                                        }
                                        //echo "Numero Total de Municipios: ".$numeroDeMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numeroDeMunicipio;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;
                                    case strtoupper("Número de Municípios até 250 km da Capital"):
                                        //$numMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimoIdMunicipio = -1;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->municipio->id != $ultimoIdMunicipio && $atividade->municipio->distancia <= 250 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numMunicipio++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimoIdMunicipio = $atividade->municipio->id;
                                            }
                                        }
                                        //echo "Numero de municipios até 250k de distancia: ".$numMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numMunicipio;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de Municípios acima de 401 km da Capital"):
                                        //$numMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimoIdMunicipio = -1;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->municipio->id != $ultimoIdMunicipio && $atividade->municipio->distancia > 400 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numMunicipio++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimoIdMunicipio = $atividade->municipio->id;
                                            }
                                        }
                                        //echo "Numero de municipios de 401k pra cima de distancia: ".$numMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numMunicipio;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;


                                    break;

                                    case strtoupper("Número de Apresentações"):
                                        //$totalApresentacoesOs = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoesOs++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoesOs;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número Total de Público"):
                                        //$totalPublicoPelaOs = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPublicoPelaOs += $atividade->num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Total Publico pela OS: ".$totalPublicoPelaOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPublicoPelaOs;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de Eventos"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Percentual Mínimo de Municípios presentes"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número de Apresentações realizadas diretamente pela OS (exclui as apresentações para estudantes)"):

                                        //$totalApresentacoesOs = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoesOs++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoesOs;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número de Apresentações realizadas em parceria com os municípios e instituições"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número Total de Público nas apresentações realizadas diretamente pela OS"):
                                        //$totalPublicoPelaOs = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPublicoPelaOs += $atividade->num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Total Publico pela OS: ".$totalPublicoPelaOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPublicoPelaOs;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;
                                    /*ESSE É UM CASO QUE PRECISO TER UM CAMPO ESPECIFICO QUE NO MOMENTO NÃO TEM PARA ESTUDANTES, ESTOU FAZENDO O CALCULO DAS APRESENTACOES NORMAIS*/
                                    case strtoupper("Número de Apresentações para Estudantes"):
                                        //$apresentacoesEstudantes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {

                                                //$apresentacoesEstudantes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Apresentações para estudantes: ".$apresentacoesEstudantes."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $apresentacoesEstudantes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*ESSE É UM CASO QUE PRECISO TER UM CAMPO ESPECIFICO QUE NO MOMENTO NÃO TEM PARA ESTUDANTES, ESTOU FAZENDO O CALCULO PELA SOMA DE MEIAS*/
                                    case strtoupper("Número Total de público de estudantes"):
                                        //$totalPublicoEstudantes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPublicoEstudantes += $atividade->meia;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->meia;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->meia;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->meia;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->meia;
                                                }
                                            }
                                        }
                                        //echo "Número total de publico estudante: ".$totalPublicoEstudantes;
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPublicoEstudantes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número Total de Público Circulante"):

                                        //$totalApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*ESSE É UM CASO QUE PRECISO FAZER O FILTRO PARA NAO APARECER CAMPOS COM UM CAMPO ESPECIFICO QUE NAO EXISTE NO CADASTRO, ASSIM ESTOU FAZENDO SEM O FILTRO MAS PELO REALIZADOR APAA*/
                                    case strtoupper("Número de Apresentações (exclui as para estudantes)"):

                                        //$totalApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*ESSE É UM CASO QUE PRECISO TER UM CAMPO ESPECIFICO QUE NO MOMENTO NÃO TEM PARA ESTUDANTES, ESTOU FAZENDO O CALCULO DAS APRESENTACOES NORMAIS*/
                                    case strtoupper("Número de Apresentações para Estudantes"):
                                        //$apresentacoesEstudantes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {

                                                //$apresentacoesEstudantes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Apresentações para estudantes: ".$apresentacoesEstudantes."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $apresentacoesEstudantes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*ESSE É UM CASO QUE PRECISO TER UM CAMPO ESPECIFICO QUE NO MOMENTO NÃO TEM PARA ESTUDANTES, ESTOU FAZENDO O CALCULO PELA SOMA DE MEIAS*/
                                    case strtoupper("Total de Público de Estudantes"):
                                        //$totalPublicoEstudantes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPublicoEstudantes += $atividade->meia;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->meia;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->meia;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->meia;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->meia;
                                                }
                                            }
                                        }
                                        //echo "Número total de publico estudante: ".$totalPublicoEstudantes;
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPublicoEstudantes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de apresentações"):

                                        //$totalApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número mínimo de público (média de 400 pessoas por apresentação)"):
                                        //$minPublico = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $acao->programa->id == $atividade->programa->id)
                                            {

                                                //$minPublico += $atividade->num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Apresentações para estudantes: ".$apresentacoesEstudantes."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $minPublico;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número Total de Municípios"):
                                        $ultimoIdMunicipio = -1;
                                        //$numeroDeMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->municipio->id != $ultimoIdMunicipio && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numeroDeMunicipio++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimoIdMunicipio = $atividade->municipio->id;
                                            }
                                        }
                                        //echo "Numero Total de Municipios: ".$numeroDeMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numeroDeMunicipio;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de Municípios de 251 até 400 Km da Capital"):
                                        //$numMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimoIdMunicipio = -1;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->municipio->id != $ultimoIdMunicipio && $atividade->municipio->distancia > 250 && $atividade->municipio->distancia <= 400 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numMunicipio++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimoIdMunicipio = $atividade->municipio->id;
                                            }
                                        }
                                        //echo "Numero de municipios de 251k até 400k de distancia: ".$numMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numMunicipio;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de Municípios acima de 400 Km da Capital"):
                                        //$numMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimoIdMunicipio = -1;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->municipio->id != $ultimoIdMunicipio && $atividade->municipio->distancia > 400 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numMunicipio++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimoIdMunicipio = $atividade->municipio->id;
                                            }
                                        }
                                        //echo "Numero de municipios de 401k pra cima de distancia: ".$numMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numMunicipio;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número Mínimo de público (média de 320 pessoas por apresentação)"):

                                        //$minPublico = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$minPublico += $atividade->num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Apresentações para estudantes: ".$apresentacoesEstudantes."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //campos[$countCampos]["alcancado"] = $minPublico;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*Campo de preenchimento no relatorio aberto*/
                                    /*Número de Espetáculo - Título Novo*/
                                    case strtoupper("Número de Espetáculo - Título Novo"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;


                                    case strtoupper("Número de Atividades Culturais"):

                                        //$totalApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Público"):

                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPessoas += $num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*Campos de preenchimento aberto no relatorio*/
                                    /*Número de Oficinas*/
                                    /*Número de Vagas*/
                                    case strtoupper("Número de Oficinas"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número de Vagas"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    /*Campos de preenchimento aberto no relatorio*/
                                    /*Número de Oficinas*/
                                    /*Número de Vagas*/
                                    case strtoupper("Número de Oficinas"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número de Vagas"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número de Atividades"):

                                        //$totalApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1  && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Total de Público nas atividades literárias"):

                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $atividade->linguagem_programa->nome == strtoupper("Literatura") && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPessoas += $num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*Campo de preenchimento aberto no relatorio*/
                                    /*Número de escritores / artistas*/
                                    case strtoupper("Número de escritores / artistas"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número de Apresentações"):

                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $atividade->linguagem_programa->nome != strtoupper("Literatura") && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPessoas += $num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número Total de Público Circulante no Festival"):

                                        //$totalApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Quantidade de apresentações"):

                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPessoas += $num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de Municípios atendidos"):

                                        $ultimoIdMunicipio = -1;
                                        //$numeroDeMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->municipio->id != $ultimoIdMunicipio && $atividade->municipio->nome != strtoupper("São Paulo") && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$ultimoIdMunicipio = $atividade->municipio->id;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                                $numeroDeMunicipio++;
                                            }
                                        }
                                        //echo "Numero Total de Municipios: ".$numeroDeMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numeroDeMunicipio;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;


                                    case strtoupper("Número de Exposições no Municipio de São Paulo"):

                                        //$numExposicoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $atividade->municipio->nome == strtoupper("São Paulo") && $atividade->nome != $ultimaApresentacaoNome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numExposicoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Público do Municipio de São Paulo"):

                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->municipio->nome == strtoupper("São Paulo") && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPessoas += $num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*CAMPO DE PREENCHIMENTO ABERTO*/
                                    /*Número de adaptações – Sala Sérgio Cardoso*/
                                    case strtoupper("Número de adaptações – Sala Sérgio Cardoso"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper('Soma do "Número total de pessoas" quando o campo "Sessão acessível" igual a "sim"'):

                                        $totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->sessao_acessivel == 1 && $acao->programa->id == $atividade->programa->id)
                                            {

                                        //$totalPublicoComSessaoAcessivel += $atividade->num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Número total de publico estudante: ".$totalPublicoEstudantes;
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPublicoEstudantes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;




                                    break;


                                    case strtoupper('Soma de registros com o campo "Sessão acessível" "sim"'):

                                        //$numApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->sessao_acessivel == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de apresentações"):

                                        //$numApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->sessao_acessivel == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*CAMPO DE PREENCHIMENTO ABERTO*/
                                    /*Número de adaptações – Sala Paschoal Carlos Magno*/
                                    case strtoupper("Número de adaptações – Sala Paschoal Carlos Magno"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número de apresentações – Sala Paschoal Carlos Magno"):

                                        //$numApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->sessao_acessivel == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número de apresentações – Festival Paulista de Circo"):

                                        //$numApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->sessao_acessivel == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;


                                    /*Campos de preenchimento aberto no relatorio*/
                                    /*Número de Oficinas*/
                                    /*Número de Vagas*/
                                    case strtoupper("Percentual sobre o valor destinado pelo Contrato de Gestão às atividades vinculadas aos equipamentos (Teatro Sérgio Cardoso - SP, Teatro Francisco Paulo Russo - Araras e Centro de Cultura, Memória e Estudos da Diversidade Sexual)"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                }
                                //echo $countCampos."<br>";
                                $countCampos++;
                                continue;
                            }
                        }
                        $arrayInfo[$count]["acao"] = $acao->codigo_acao;
                        $arrayInfo[$count]["campos"] = $campos;
                        $arrayInfo[$count]["existe_campo_aberto"] = false;
                        $count++;

                    break;


                }
            }
            }
            //dd($arrayInfo);
            //die();

            return view('relatorio.lista-relatorio-planos', ['acoes' => $acoes,'arrayInfo' => $arrayInfo, 'regras' => $regras, 'atividades' => $atividades]);
        }
        else
        {
            return redirect()->route('dashboard');
        }


    }


        // mostra view da escolha de planos mensais
    public function PlanosRelatorioMensal()    {	
        if(Auth::user()->perfil == 2)
        {
        	$planos = $this->plano->orderBy('created_at','DESC')->get();
        	return view('relatorio.planos-relatorio-mensal',['planos' => $planos]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }
        // mostra view da escolha de planos trimestrais
    public function PlanosRelatorioTrimestral()   {

        if(Auth::user()->perfil == 2)
        {

        	$planos = $this->plano->orderBy('created_at','DESC')->get();


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

            $planos = $this->plano->whereIn('id',$planos_id)->orderBy('created_at', 'desc')->get();


        }
         
        return view('relatorio.planos-relatorio-trimestral',['planos' => $planos]);
    }




        // cria regras de calculo para relatório mensal
    public function RelatorioMensal($id) {

    	if(Auth::user()->perfil == 2)
        {

            $programas = $this->programa->where('plano_id',$id)->orderBy('created_at','ASC')->get();
            $indicadores = $this->indicador->where('plano_id',$id)->orderBy('created_at','ASC')->get();
            $acoes = $this->acao->where('plano_id',$id)->orderBy('codigo_acao','ASC')->get();
            //$atividades = $this->atividade->where('plano_id',$id)->orderBy('created_at','ASC')->get();


            $atividades = $this->atividade->select(DB::raw
            	('sum(num_total_pessoas) as total_pessoas,
            	  sum(convite_prod) as total_convite_prod,
            	  sum(convite_apaa) as total_convite_apaa,
            	  sum(educativo_producao) as total_educativo_producao,
            	  sum(educativo_apaa) as total_educativo_apaa,
            	  sum(atend_social_producao) as total_atend_social_producao,
            	  sum(atend_social_apaa) as total_atend_social_apaa,
            	  MONTH(data) as data2,
            	  data_fim,plano_id,
            	  programa_id,
            	  tipo_publico_id'))->
            where('plano_id',$id)->
            groupBy('tipo_publico_id','programa_id')->get();




        /*

       echo "<hr/>teste de consulta nova <br/>";
       $results = DB::select(' select
								sum(a.num_total_pessoas) as total_pessoas,
								sum(a.convite_prod) as total_convite_prod,
								sum(a.convite_apaa) as total_convite_apaa,
								sum(a.educativo_producao) as total_educativo_producao,
								sum(a.educativo_apaa) as total_educativo_apaa,
								sum(a.atend_social_producao) as total_atend_social_producao,
								sum(a.atend_social_apaa) as total_atend_social_apaa,
								MONTH(a.data) as data2,
								a.data_fim,plano_id,
								a.programa_id,
								a.tipo_publico_id
								from atividades a
								group by a.tipo_publico_id');
       var_dump($results);
        */



            $arrayInfo = array(array());
            $count = 0;

            // criando busca de array de acoes
            foreach($acoes as $acao)
            {
                // valor do codigo da acao for igual o case
                while($acao->codigo_acao != '')
                {
                    // caso do codigo acao numero 1

                        $totais_atividades = array(array());
                        $countTotais = 0;
                        $ultimaAtividadeId = -1;
                        foreach($atividades as $atividade)
                        {
                            // se o id de programa da acao for igual ao id de programa da atividade
                            if($acao->programa->id == $atividade->programa->id)
                            {
                                $totais_atividades[$countTotais]["tipo_publico"] = $atividade->tipo_publico->nome;
                                $totais_atividades[$countTotais]["jan"] = 0;
                                $totais_atividades[$countTotais]["fev"] = 0;
                                $totais_atividades[$countTotais]["mar"] = 0;
                                $totais_atividades[$countTotais]["abr"] = 0;
                                $totais_atividades[$countTotais]["mai"] = 0;
                                $totais_atividades[$countTotais]["jun"] = 0;
                                $totais_atividades[$countTotais]["jul"] = 0;
                                $totais_atividades[$countTotais]["ago"] = 0;
                                $totais_atividades[$countTotais]["set"] = 0;
                                $totais_atividades[$countTotais]["out"] = 0;
                                $totais_atividades[$countTotais]["nov"] = 0;
                                $totais_atividades[$countTotais]["dez"] = 0;

                                // criando array de atividades e renomeando dentro para atividadeInside
                                foreach($atividades as $atividadeInside)
                                {
                                    // se o ID de tipo de publico da atividade for igual ao ID de tipo de publico de atividadeInside faça

                                    if($atividade->tipo_publico->id == $atividadeInside->tipo_publico->id)
                                    {

                                        // Se o ID de programa da ação for igual ao ID de programa da atividade Inside faça

                                        if($acao->programa->id == $atividadeInside->programa->id)
                                        {
                                            //$date_mes = date_parse_from_format("Y-m-d", $atividadeInside->data2);
                                            if($atividadeInside->data2 == 1)
                                            {
                                                $totais_atividades[$countTotais]["jan"] += $atividadeInside->total_pessoas;
                                            }
                                            elseif($atividadeInside->data2 == 2)
                                            {
                                                $totais_atividades[$countTotais]["fev"] += $atividadeInside->total_pessoas;
                                            }
                                            elseif($atividadeInside->data2 == 3)
                                            {
                                                $totais_atividades[$countTotais]["mar"] += $atividadeInside->total_pessoas;
                                            }
                                            elseif($atividadeInside->data2 == 4)
                                            {
                                                $totais_atividades[$countTotais]["abr"] += $atividadeInside->total_pessoas;
                                            }
                                            elseif($atividadeInside->data2 == 5)
                                            {
                                                $totais_atividades[$countTotais]["mai"] += $atividadeInside->total_pessoas;
                                            }
                                            elseif($atividadeInside->data2 == 6)
                                            {
                                                $totais_atividades[$countTotais]["jun"] += $atividadeInside->total_pessoas;
                                            }
                                            elseif($atividadeInside->data2 == 7)
                                            {
                                                $totais_atividades[$countTotais]["jul"] += $atividadeInside->total_pessoas;
                                            }
                                            elseif($atividadeInside->data2 == 8)
                                            {
                                                $totais_atividades[$countTotais]["ago"] += $atividadeInside->total_pessoas;
                                            }
                                            elseif($atividadeInside->data2 == 9)
                                            {
                                                $totais_atividades[$countTotais]["set"] += $atividadeInside->total_pessoas;
                                            }
                                            elseif($atividadeInside->data2 == 10)
                                            {
                                                $totais_atividades[$countTotais]["out"] += $atividadeInside->total_pessoas;
                                            }
                                            elseif($atividadeInside->data2 == 11)
                                            {
                                                $totais_atividades[$countTotais]["nov"] += $atividadeInside->total_pessoas;
                                            }
                                            elseif($atividadeInside->data2 == 12)
                                            {
                                                $totais_atividades[$countTotais]["dez"] += $atividadeInside->total_pessoas;
                                            }
                                        }
                                    }

                                    $agendado = 0; //educativo APAA + educativo Produção + atendimento social produção + atendimento social APAA
                                    $espontaneo = 0; //total de convites – resultado agendado (soma das colunas acima)

                                    $agendado = intval($atividade->total_educativo_apaa) + intval($atividade->total_educativo_producao) + intval($atividade->total_atend_social_producao) + intval($atividade->total_atend_social_apaa);
                                    $espontaneo = ((intval($atividade->total_convite_prod) + intval($atividade->total_convite_apaa)) - $agendado);
                                    $totais_atividades[$countTotais]["agendado"] = $agendado;
                                    $totais_atividades[$countTotais]["espontaneo"] = $espontaneo;
                                }
                            }
                            $countTotais++;
                        }

                        $arrayInfo[$count]["acao"] = $acao->codigo_acao;
                        $arrayInfo[$count]["totais_atividades"] = $totais_atividades;
                        $count++;
                    break;
                }
            }
            //dd($arrayInfo);
            //die();

            return view('relatorio.relatorio-mensal',['arrayInfo' => $arrayInfo]);
        }
        else
        {
            return redirect()->route('dashboard');
        }
    }
    // fim do relatório mensal


    // cria regra de calculo para relatório trimestral
    public function RelatorioTrimestral(Request $request, $id)  {

        if(Auth::user()->perfil == 2)
        {
            $regras = $this->regra->where('plano_id',$id)->orderBy('created_at','ASC');
        	$programas = $this->programa->where('plano_id',$id)->orderBy('created_at','ASC')->get();
        	$indicadores = $this->indicador->where('plano_id',$id)->orderBy('created_at','ASC')->get();
        	$acoes = $this->acao->where('plano_id',$id)->orderBy('codigo_acao','ASC')->get();
        	$atividades = $this->atividade->where('plano_id',$id)->orderBy('created_at','ASC')->get();
            $planos = $this->plano->where('id',$id)->orderBy('created_at','ASC')->get();


        	$arrayInfo = array(array());
        	$count = 0;


            // array de acoes transformando variavel ação para dentro do foreach
        	foreach($acoes as $acao)
        	{

                if($acao->codigo_acao != ''){


                // se o codigo da ação for igual aos cases
        		while($acao->codigo_acao != 0)
        		{

        				$campos = array(array());
        				/*$campos[0]["nome"] = 0;
        				$campos[0]["alcancado"] = 0;
        				$campos[0]["meta"] = 0;*/
        				$countCampos = 0;


                        // cria lista de array de indicadores transformando em variavel indicador dentro do foreach

        				foreach($indicadores as $indicador)
        				{

                            // se o codigo de ação do indicador for o mesmo do codigo de ação da ação faça

        					if($indicador->acao->codigo_acao == $acao->codigo_acao)
        					{

                                // variavel do nome do indicador para os casos

        						switch(strtoupper($indicador->regra->descricao))
        						{

                                    //criar regra dentro deste campo

                                    /* case strtoupper("Número de municípios"):


                                        $ultimoIdMunicipio = -1;
                                        //$numeroDeMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;

                                        // lista de atividades transformando na variavel atividade para dentro do foreach

                                        foreach($atividades as $atividade)
                                        {

                                            // se ID de municipio dentro de atividade for diferente do ultimo municipio e se o ID do programa da ação for igual ao ID da atividade faça

                                            if($atividade->municipio->id != $ultimoIdMunicipio && $acao->programa->id == $atividade->programa->id)
                                            {

                                                // transformando o created_at da atividade em unidade de data para utilizar apenas os números do mês e
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->created_at);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimoIdMunicipio = $atividade->municipio->id;
                                                //$numeroDeMunicipio++;
                                            }
                                        }
                                        //echo "Numero de Municipios: ".$numeroDeMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->regra->descricao;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    */




                                    // caso com um nome que seja semelhante a variavel nome do indicador


        							case strtoupper("Número de municípios"):


    	    							$ultimoIdMunicipio = -1;
    	    							//$numeroDeMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;

                                        // lista de atividades transformando na variavel atividade para dentro do foreach

    	    							foreach($atividades as $atividade)
    	    							{

                                            // se ID de municipio dentro de atividade for diferente do ultimo municipio e se o ID do programa da ação for igual ao ID da atividade faça

    	    								if($atividade->municipio->id != $ultimoIdMunicipio && $acao->programa->id == $atividade->programa->id)
    	    								{

                                                // transformando o data da atividade em unidade de data para utilizar apenas os números do mês e
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
    	    									$ultimoIdMunicipio = $atividade->municipio->id;
    	    									//$numeroDeMunicipio++;
    	    								}
    	    							}
    	    							//echo "Numero de Municipios: ".$numeroDeMunicipio."<br>";
    				    				$campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
    				    				$campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
    				    				$campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

        							break;

        							case strtoupper("Número de apresentações realizadas diretamente pela OS"):



    	    							//$totalApresentacoesOs = 0;
        								$ultimaApresentacaoNome = "-1";
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
    	    							foreach($atividades as $atividade)
    	    							{
    	    								if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
    	    								{
    	    									//$totalApresentacoesOs++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
    	    									$ultimaApresentacaoNome = $atividade->nome;
    	    								}
    	    							}
    	    							//echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
        								$campos[$countCampos]["nome"] = $indicador->nome_indicador;
    				    				//$campos[$countCampos]["alcancado"] = $totalApresentacoesOs;
    				    				//$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

        							break;

        							case strtoupper("Número de apresentações realizadas em parceria com os municípios e instituições"):
    	    							//$totalApresentacoesOutrasOss = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
    	    							foreach($atividades as $atividade)
    	    							{
    	    								if($atividade->realizador->id != 1 && $acao->programa->id == $atividade->programa->id)
    	    								{
    	    									//$totalApresentacoesOutrasOss++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
    	    								}
    	    							}
    	    							//echo "Número de Apresentações por outras OSs: ".$totalApresentacoesOutrasOss."<br>";
        								$campos[$countCampos]["nome"] = $indicador->nome_indicador;
    				    				//$campos[$countCampos]["alcancado"] = $totalApresentacoesOutrasOss;
    				    				//$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

        							break;

        							//INDICADOR PARA A PORCENTAGEM DE ARTISTAS QUE NÃO SE APRESENTARAM NA ULTIMA EDICAO
        							//SERÁ UM CAMPO ABERTO NO RELATÓRIO
                                    case strtoupper("Porcentagem de artistas que não se apresentaram na última edição"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;


        							case strtoupper("Número Total de Público"):

                                       //itamar

                                      // var_dump("$indicador");
                                      // var_dump("$acao");

    	    							//$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
    	    							foreach($atividades as $atividade)
    	    							{
    	    								if($atividade->realizador->id != 1 && $acao->programa->id == $atividade->programa->id)
    	    								{
            									//$totalPessoas += $ativadade->num_total_pessoas;
            								    $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
    	    							}
    	    							//echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
        								$campos[$countCampos]["nome"] = $indicador->nome_indicador;
    				    				//$campos[$countCampos]["alcancado"] = $totalPessoas;
    				    				//$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

        							break;
                                    case strtoupper("Número de apresentações realizadas diretamente pela OS"):
                                        $ultimoIdMunicipio = -1;
                                        //$numeroDeMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->municipio->id != $ultimoIdMunicipio && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numeroDeMunicipio++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimoIdMunicipio = $atividade->municipio->id;
                                            }
                                        }
                                        //echo "Numero Total de Municipios: ".$numeroDeMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numeroDeMunicipio;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;
                                    case strtoupper("Número de Municípios até 250 km da Capital"):
                                        //$numMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimoIdMunicipio = -1;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->municipio->id != $ultimoIdMunicipio && $atividade->municipio->distancia <= 250 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numMunicipio++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimoIdMunicipio = $atividade->municipio->id;
                                            }
                                        }
                                        //echo "Numero de municipios até 250k de distancia: ".$numMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numMunicipio;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de Municípios acima de 401 km da Capital"):
                                        //$numMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimoIdMunicipio = -1;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->municipio->id != $ultimoIdMunicipio && $atividade->municipio->distancia > 400 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numMunicipio++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimoIdMunicipio = $atividade->municipio->id;
                                            }
                                        }
                                        //echo "Numero de municipios de 401k pra cima de distancia: ".$numMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numMunicipio;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;


                                    break;

                                    case strtoupper("Número de Apresentações"):
                                        //$totalApresentacoesOs = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoesOs++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoesOs;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número Total de Público"):
                                        //$totalPublicoPelaOs = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPublicoPelaOs += $atividade->num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Total Publico pela OS: ".$totalPublicoPelaOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPublicoPelaOs;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de Eventos"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Percentual Mínimo de Municípios presentes"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número de Apresentações realizadas diretamente pela OS (exclui as apresentações para estudantes)"):

                                        //$totalApresentacoesOs = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoesOs++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoesOs;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número de Apresentações realizadas em parceria com os municípios e instituições"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número Total de Público nas apresentações realizadas diretamente pela OS"):
                                        //$totalPublicoPelaOs = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPublicoPelaOs += $atividade->num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Total Publico pela OS: ".$totalPublicoPelaOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPublicoPelaOs;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;
                                    /*ESSE É UM CASO QUE PRECISO TER UM CAMPO ESPECIFICO QUE NO MOMENTO NÃO TEM PARA ESTUDANTES, ESTOU FAZENDO O CALCULO DAS APRESENTACOES NORMAIS*/
                                    case strtoupper("Número de Apresentações para Estudantes"):
                                        //$apresentacoesEstudantes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {

                                                //$apresentacoesEstudantes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Apresentações para estudantes: ".$apresentacoesEstudantes."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $apresentacoesEstudantes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*ESSE É UM CASO QUE PRECISO TER UM CAMPO ESPECIFICO QUE NO MOMENTO NÃO TEM PARA ESTUDANTES, ESTOU FAZENDO O CALCULO PELA SOMA DE MEIAS*/
                                    case strtoupper("Número Total de público de estudantes"):
                                        //$totalPublicoEstudantes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPublicoEstudantes += $atividade->meia;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->meia;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->meia;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->meia;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->meia;
                                                }
                                            }
                                        }
                                        //echo "Número total de publico estudante: ".$totalPublicoEstudantes;
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPublicoEstudantes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número Total de Público Circulante"):

                                        //$totalApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*ESSE É UM CASO QUE PRECISO FAZER O FILTRO PARA NAO APARECER CAMPOS COM UM CAMPO ESPECIFICO QUE NAO EXISTE NO CADASTRO, ASSIM ESTOU FAZENDO SEM O FILTRO MAS PELO REALIZADOR APAA*/
                                    case strtoupper("Número de Apresentações (exclui as para estudantes)"):

                                        //$totalApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*ESSE É UM CASO QUE PRECISO TER UM CAMPO ESPECIFICO QUE NO MOMENTO NÃO TEM PARA ESTUDANTES, ESTOU FAZENDO O CALCULO DAS APRESENTACOES NORMAIS*/
                                    case strtoupper("Número de Apresentações para Estudantes"):
                                        //$apresentacoesEstudantes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {

                                                //$apresentacoesEstudantes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Apresentações para estudantes: ".$apresentacoesEstudantes."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $apresentacoesEstudantes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*ESSE É UM CASO QUE PRECISO TER UM CAMPO ESPECIFICO QUE NO MOMENTO NÃO TEM PARA ESTUDANTES, ESTOU FAZENDO O CALCULO PELA SOMA DE MEIAS*/
                                    case strtoupper("Total de Público de Estudantes"):
                                        //$totalPublicoEstudantes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPublicoEstudantes += $atividade->meia;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->meia;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->meia;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->meia;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->meia;
                                                }
                                            }
                                        }
                                        //echo "Número total de publico estudante: ".$totalPublicoEstudantes;
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPublicoEstudantes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de apresentações"):

                                        //$totalApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número mínimo de público (média de 400 pessoas por apresentação)"):
                                        //$minPublico = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $acao->programa->id == $atividade->programa->id)
                                            {

                                                //$minPublico += $atividade->num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Apresentações para estudantes: ".$apresentacoesEstudantes."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $minPublico;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número Total de Municípios"):
                                        $ultimoIdMunicipio = -1;
                                        //$numeroDeMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->municipio->id != $ultimoIdMunicipio && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numeroDeMunicipio++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimoIdMunicipio = $atividade->municipio->id;
                                            }
                                        }
                                        //echo "Numero Total de Municipios: ".$numeroDeMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numeroDeMunicipio;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de Municípios de 251 até 400 Km da Capital"):
                                        //$numMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimoIdMunicipio = -1;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->municipio->id != $ultimoIdMunicipio && $atividade->municipio->distancia > 250 && $atividade->municipio->distancia <= 400 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numMunicipio++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimoIdMunicipio = $atividade->municipio->id;
                                            }
                                        }
                                        //echo "Numero de municipios de 251k até 400k de distancia: ".$numMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numMunicipio;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de Municípios acima de 400 Km da Capital"):
                                        //$numMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimoIdMunicipio = -1;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->municipio->id != $ultimoIdMunicipio && $atividade->municipio->distancia > 400 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numMunicipio++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimoIdMunicipio = $atividade->municipio->id;
                                            }
                                        }
                                        //echo "Numero de municipios de 401k pra cima de distancia: ".$numMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numMunicipio;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número Mínimo de público (média de 320 pessoas por apresentação)"):

                                        //$minPublico = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$minPublico += $atividade->num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Apresentações para estudantes: ".$apresentacoesEstudantes."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //campos[$countCampos]["alcancado"] = $minPublico;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*Campo de preenchimento no relatorio aberto*/
                                    /*Número de Espetáculo - Título Novo*/
                                    case strtoupper("Número de Espetáculo - Título Novo"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;


                                    case strtoupper("Número de Atividades Culturais"):

                                        //$totalApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Público"):

                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPessoas += $num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*Campos de preenchimento aberto no relatorio*/
                                    /*Número de Oficinas*/
                                    /*Número de Vagas*/
                                    case strtoupper("Número de Oficinas"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número de Vagas"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    /*Campos de preenchimento aberto no relatorio*/
                                    /*Número de Oficinas*/
                                    /*Número de Vagas*/
                                    case strtoupper("Número de Oficinas"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número de Vagas"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número de Atividades"):

                                        //$totalApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1  && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Total de Público nas atividades literárias"):

                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $atividade->linguagem_programa->nome == strtoupper("Literatura") && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPessoas += $num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*Campo de preenchimento aberto no relatorio*/
                                    /*Número de escritores / artistas*/
                                    case strtoupper("Número de escritores / artistas"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número de Apresentações"):

                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $atividade->linguagem_programa->nome != strtoupper("Literatura") && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPessoas += $num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número Total de Público Circulante no Festival"):

                                        //$totalApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Quantidade de apresentações"):

                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPessoas += $num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de Municípios atendidos"):

                                        $ultimoIdMunicipio = -1;
                                        //$numeroDeMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->municipio->id != $ultimoIdMunicipio && $atividade->municipio->nome != strtoupper("São Paulo") && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$ultimoIdMunicipio = $atividade->municipio->id;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                                $numeroDeMunicipio++;
                                            }
                                        }
                                        //echo "Numero Total de Municipios: ".$numeroDeMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numeroDeMunicipio;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;


                                    case strtoupper("Número de Exposições no Municipio de São Paulo"):

                                        //$numExposicoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $atividade->municipio->nome == strtoupper("São Paulo") && $atividade->nome != $ultimaApresentacaoNome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numExposicoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Público do Municipio de São Paulo"):

                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->municipio->nome == strtoupper("São Paulo") && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPessoas += $num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*CAMPO DE PREENCHIMENTO ABERTO*/
                                    /*Número de adaptações – Sala Sérgio Cardoso*/
                                    case strtoupper("Número de adaptações – Sala Sérgio Cardoso"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper('Soma do "Número total de pessoas" quando o campo "Sessão acessível" igual a "sim"'):

                                        $totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->sessao_acessivel == 1 && $acao->programa->id == $atividade->programa->id)
                                            {

                                        //$totalPublicoComSessaoAcessivel += $atividade->num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Número total de publico estudante: ".$totalPublicoEstudantes;
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPublicoEstudantes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;




                                    break;


                                    case strtoupper('Soma de registros com o campo "Sessão acessível" "sim"'):

                                        //$numApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->sessao_acessivel == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de apresentações – Sala Sérgio Cardoso"):

                                        //$numApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->sessao_acessivel == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*CAMPO DE PREENCHIMENTO ABERTO*/
                                    /*Número de adaptações – Sala Paschoal Carlos Magno*/
                                    case strtoupper("Número de adaptações – Sala Paschoal Carlos Magno"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número de apresentações – Sala Paschoal Carlos Magno"):

                                        //$numApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->sessao_acessivel == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número de apresentações – Festival Paulista de Circo"):

                                        //$numApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->sessao_acessivel == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                        $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                        $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                        $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;


                                    /*Campos de preenchimento aberto no relatorio*/
                                    /*Número de Oficinas*/
                                    /*Número de Vagas*/
                                    case strtoupper("Percentual sobre o valor destinado pelo Contrato de Gestão às atividades vinculadas aos equipamentos (Teatro Sérgio Cardoso - SP, Teatro Francisco Paulo Russo - Araras e Centro de Cultura, Memória e Estudos da Diversidade Sexual)"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                        $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                        $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                        $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

        						}
        						//echo $countCampos."<br>";
        						$countCampos++;
        						continue;
        					}
        				}
        				$arrayInfo[$count]["acao"] = $acao->codigo_acao;
        				$arrayInfo[$count]["campos"] = $campos;
        				$arrayInfo[$count]["existe_campo_aberto"] = false;
        				$count++;

        			break;


        		}
        	}
            }
        	//dd($arrayInfo);
            //die();

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
                $regras = $this->regra->where('plano_id',$id)->orderBy('created_at','ASC');
                $programas = $this->programa->where('plano_id',$id)->orderBy('created_at','ASC')->get();
                $indicadores = $this->indicador->where('plano_id',$id)->orderBy('created_at','ASC')->get();
                $acoes = $this->acao->where('plano_id',$id)->orderBy('codigo_acao','ASC')->get();
                $atividades = $this->atividade->where('plano_id',$id)->orderBy('created_at','ASC')->get();
                $planos = $this->plano->where('id',$id)->orderBy('created_at','ASC')->get();


            $arrayInfo = array(array());
            $count = 0;


            // array de acoes transformando variavel ação para dentro do foreach
            foreach($acoes as $acao)
            {

                if($acao->codigo_acao != ''){


                // se o codigo da ação for igual aos cases
                while($acao->codigo_acao != 0)
                {

                        $campos = array(array());
                        /*$campos[0]["nome"] = 0;
                        $campos[0]["alcancado"] = 0;
                        $campos[0]["meta"] = 0;*/
                        $countCampos = 0;


                        // cria lista de array de indicadores transformando em variavel indicador dentro do foreach

                        foreach($indicadores as $indicador)
                        {

                            // se o codigo de ação do indicador for o mesmo do codigo de ação da ação faça

                            if($indicador->acao->codigo_acao == $acao->codigo_acao)
                            {

                                // variavel do nome do indicador para os casos

                                switch(strtoupper($indicador->regra->descricao))
                                {

                                    //criar regra dentro deste campo

                                    /* case strtoupper("Número de municípios"):


                                        $ultimoIdMunicipio = -1;
                                        //$numeroDeMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;

                                        // lista de atividades transformando na variavel atividade para dentro do foreach

                                        foreach($atividades as $atividade)
                                        {

                                            // se ID de municipio dentro de atividade for diferente do ultimo municipio e se o ID do programa da ação for igual ao ID da atividade faça

                                            if($atividade->municipio->id != $ultimoIdMunicipio && $acao->programa->id == $atividade->programa->id)
                                            {

                                                // transformando o created_at da atividade em unidade de data para utilizar apenas os números do mês e
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->created_at);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimoIdMunicipio = $atividade->municipio->id;
                                                //$numeroDeMunicipio++;
                                            }
                                        }
                                        //echo "Numero de Municipios: ".$numeroDeMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->regra->descricao;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    */




                                    // caso com um nome que seja semelhante a variavel nome do indicador


                                    case strtoupper("Número de municípios"):


                                        $ultimoIdMunicipio = -1;
                                        //$numeroDeMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;

                                        // lista de atividades transformando na variavel atividade para dentro do foreach

                                        foreach($atividades as $atividade)
                                        {

                                            // se ID de municipio dentro de atividade for diferente do ultimo municipio e se o ID do programa da ação for igual ao ID da atividade faça

                                            if($atividade->municipio->id != $ultimoIdMunicipio && $acao->programa->id == $atividade->programa->id)
                                            {

                                                // transformando a data da atividade em unidade de data para utilizar apenas os números do mês e
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimoIdMunicipio = $atividade->municipio->id;
                                                //$numeroDeMunicipio++;
                                            }
                                        }
                                        //echo "Numero de Municipios: ".$numeroDeMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de apresentações realizadas diretamente pela OS"):



                                        //$totalApresentacoesOs = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoesOs++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoesOs;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de apresentações realizadas em parceria com os municípios e instituições"):
                                        //$totalApresentacoesOutrasOss = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id != 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoesOutrasOss++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                            }
                                        }
                                        //echo "Número de Apresentações por outras OSs: ".$totalApresentacoesOutrasOss."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoesOutrasOss;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    //INDICADOR PARA A PORCENTAGEM DE ARTISTAS QUE NÃO SE APRESENTARAM NA ULTIMA EDICAO
                                    //SERÁ UM CAMPO ABERTO NO RELATÓRIO
                                    case strtoupper("Porcentagem de artistas que não se apresentaram na última edição"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;


                                    case strtoupper("Número Total de Público"):

                                       //itamar

                                      // var_dump("$indicador");
                                      // var_dump("$acao");

                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id != 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPessoas += $ativadade->num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;
                                    case strtoupper("Número de apresentações realizadas diretamente pela OS"):
                                        $ultimoIdMunicipio = -1;
                                        //$numeroDeMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->municipio->id != $ultimoIdMunicipio && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numeroDeMunicipio++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimoIdMunicipio = $atividade->municipio->id;
                                            }
                                        }
                                        //echo "Numero Total de Municipios: ".$numeroDeMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numeroDeMunicipio;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;
                                    case strtoupper("Número de Municípios até 250 km da Capital"):
                                        //$numMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimoIdMunicipio = -1;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->municipio->id != $ultimoIdMunicipio && $atividade->municipio->distancia <= 250 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numMunicipio++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimoIdMunicipio = $atividade->municipio->id;
                                            }
                                        }
                                        //echo "Numero de municipios até 250k de distancia: ".$numMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numMunicipio;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de Municípios acima de 401 km da Capital"):
                                        //$numMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimoIdMunicipio = -1;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->municipio->id != $ultimoIdMunicipio && $atividade->municipio->distancia > 400 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numMunicipio++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimoIdMunicipio = $atividade->municipio->id;
                                            }
                                        }
                                        //echo "Numero de municipios de 401k pra cima de distancia: ".$numMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numMunicipio;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;


                                    break;

                                    case strtoupper("Número de Apresentações"):
                                        //$totalApresentacoesOs = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoesOs++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoesOs;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número Total de Público"):
                                        //$totalPublicoPelaOs = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPublicoPelaOs += $atividade->num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Total Publico pela OS: ".$totalPublicoPelaOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPublicoPelaOs;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de Eventos"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Percentual Mínimo de Municípios presentes"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número de Apresentações realizadas diretamente pela OS (exclui as apresentações para estudantes)"):

                                        //$totalApresentacoesOs = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoesOs++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoesOs;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número de Apresentações realizadas em parceria com os municípios e instituições"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número Total de Público nas apresentações realizadas diretamente pela OS"):
                                        //$totalPublicoPelaOs = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPublicoPelaOs += $atividade->num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Total Publico pela OS: ".$totalPublicoPelaOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPublicoPelaOs;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;
                                    /*ESSE É UM CASO QUE PRECISO TER UM CAMPO ESPECIFICO QUE NO MOMENTO NÃO TEM PARA ESTUDANTES, ESTOU FAZENDO O CALCULO DAS APRESENTACOES NORMAIS*/
                                    case strtoupper("Número de Apresentações para Estudantes"):
                                        //$apresentacoesEstudantes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {

                                                //$apresentacoesEstudantes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Apresentações para estudantes: ".$apresentacoesEstudantes."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $apresentacoesEstudantes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*ESSE É UM CASO QUE PRECISO TER UM CAMPO ESPECIFICO QUE NO MOMENTO NÃO TEM PARA ESTUDANTES, ESTOU FAZENDO O CALCULO PELA SOMA DE MEIAS*/
                                    case strtoupper("Número Total de público de estudantes"):
                                        //$totalPublicoEstudantes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPublicoEstudantes += $atividade->meia;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->meia;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->meia;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->meia;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->meia;
                                                }
                                            }
                                        }
                                        //echo "Número total de publico estudante: ".$totalPublicoEstudantes;
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPublicoEstudantes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número Total de Público Circulante"):

                                        //$totalApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*ESSE É UM CASO QUE PRECISO FAZER O FILTRO PARA NAO APARECER CAMPOS COM UM CAMPO ESPECIFICO QUE NAO EXISTE NO CADASTRO, ASSIM ESTOU FAZENDO SEM O FILTRO MAS PELO REALIZADOR APAA*/
                                    case strtoupper("Número de Apresentações (exclui as para estudantes)"):

                                        //$totalApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*ESSE É UM CASO QUE PRECISO TER UM CAMPO ESPECIFICO QUE NO MOMENTO NÃO TEM PARA ESTUDANTES, ESTOU FAZENDO O CALCULO DAS APRESENTACOES NORMAIS*/
                                    case strtoupper("Número de Apresentações para Estudantes"):
                                        //$apresentacoesEstudantes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {

                                                //$apresentacoesEstudantes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Apresentações para estudantes: ".$apresentacoesEstudantes."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $apresentacoesEstudantes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*ESSE É UM CASO QUE PRECISO TER UM CAMPO ESPECIFICO QUE NO MOMENTO NÃO TEM PARA ESTUDANTES, ESTOU FAZENDO O CALCULO PELA SOMA DE MEIAS*/
                                    case strtoupper("Total de Público de Estudantes"):
                                        //$totalPublicoEstudantes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPublicoEstudantes += $atividade->meia;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->meia;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->meia;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->meia;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->meia;
                                                }
                                            }
                                        }
                                        //echo "Número total de publico estudante: ".$totalPublicoEstudantes;
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPublicoEstudantes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de apresentações"):

                                        //$totalApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número mínimo de público (média de 400 pessoas por apresentação)"):
                                        //$minPublico = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $acao->programa->id == $atividade->programa->id)
                                            {

                                                //$minPublico += $atividade->num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Apresentações para estudantes: ".$apresentacoesEstudantes."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $minPublico;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número Total de Municípios"):
                                        $ultimoIdMunicipio = -1;
                                        //$numeroDeMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->municipio->id != $ultimoIdMunicipio && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numeroDeMunicipio++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimoIdMunicipio = $atividade->municipio->id;
                                            }
                                        }
                                        //echo "Numero Total de Municipios: ".$numeroDeMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numeroDeMunicipio;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de Municípios de 251 até 400 Km da Capital"):
                                        //$numMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimoIdMunicipio = -1;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->municipio->id != $ultimoIdMunicipio && $atividade->municipio->distancia > 250 && $atividade->municipio->distancia <= 400 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numMunicipio++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimoIdMunicipio = $atividade->municipio->id;
                                            }
                                        }
                                        //echo "Numero de municipios de 251k até 400k de distancia: ".$numMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numMunicipio;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de Municípios acima de 400 Km da Capital"):
                                        //$numMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimoIdMunicipio = -1;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->municipio->id != $ultimoIdMunicipio && $atividade->municipio->distancia > 400 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numMunicipio++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimoIdMunicipio = $atividade->municipio->id;
                                            }
                                        }
                                        //echo "Numero de municipios de 401k pra cima de distancia: ".$numMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numMunicipio;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número Mínimo de público (média de 320 pessoas por apresentação)"):

                                        //$minPublico = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$minPublico += $atividade->num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Apresentações para estudantes: ".$apresentacoesEstudantes."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //campos[$countCampos]["alcancado"] = $minPublico;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*Campo de preenchimento no relatorio aberto*/
                                    /*Número de Espetáculo - Título Novo*/
                                    case strtoupper("Número de Espetáculo - Título Novo"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;


                                    case strtoupper("Número de Atividades Culturais"):

                                        //$totalApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Público"):

                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPessoas += $num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*Campos de preenchimento aberto no relatorio*/
                                    /*Número de Oficinas*/
                                    /*Número de Vagas*/
                                    case strtoupper("Número de Oficinas"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número de Vagas"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    /*Campos de preenchimento aberto no relatorio*/
                                    /*Número de Oficinas*/
                                    /*Número de Vagas*/
                                    case strtoupper("Número de Oficinas"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número de Vagas"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número de Atividades"):

                                        //$totalApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1  && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Total de Público nas atividades literárias"):

                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $atividade->linguagem_programa->nome == strtoupper("Literatura") && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPessoas += $num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*Campo de preenchimento aberto no relatorio*/
                                    /*Número de escritores / artistas*/
                                    case strtoupper("Número de escritores / artistas"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número de Apresentações"):

                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $atividade->linguagem_programa->nome != strtoupper("Literatura") && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPessoas += $num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número Total de Público Circulante no Festival"):

                                        //$totalApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Quantidade de apresentações"):

                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($ultimaApresentacaoNome != $atividade->nome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPessoas += $num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de Municípios atendidos"):

                                        $ultimoIdMunicipio = -1;
                                        //$numeroDeMunicipio = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->municipio->id != $ultimoIdMunicipio && $atividade->municipio->nome != strtoupper("São Paulo") && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$ultimoIdMunicipio = $atividade->municipio->id;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                                $numeroDeMunicipio++;
                                            }
                                        }
                                        //echo "Numero Total de Municipios: ".$numeroDeMunicipio."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numeroDeMunicipio;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;


                                    case strtoupper("Número de Exposições no Municipio de São Paulo"):

                                        //$numExposicoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        $ultimaApresentacaoNome = "-1";
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->realizador->id == 1 && $atividade->municipio->nome == strtoupper("São Paulo") && $atividade->nome != $ultimaApresentacaoNome && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numExposicoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                                $ultimaApresentacaoNome = $atividade->nome;
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Público do Municipio de São Paulo"):

                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->municipio->nome == strtoupper("São Paulo") && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$totalPessoas += $num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*CAMPO DE PREENCHIMENTO ABERTO*/
                                    /*Número de adaptações – Sala Sérgio Cardoso*/
                                    case strtoupper("Número de adaptações – Sala Sérgio Cardoso"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper('Soma do "Número total de pessoas" quando o campo "Sessão acessível" igual a "sim"'):

                                        $totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->sessao_acessivel == 1 && $acao->programa->id == $atividade->programa->id)
                                            {

                                        //$totalPublicoComSessaoAcessivel += $atividade->num_total_pessoas;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri += $atividade->num_total_pessoas;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri += $atividade->num_total_pessoas;
                                                }
                                            }
                                        }
                                        //echo "Número total de publico estudante: ".$totalPublicoEstudantes;
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $totalPublicoEstudantes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;




                                    break;


                                    case strtoupper('Soma de registros com o campo "Sessão acessível" "sim"'):

                                        //$numApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->sessao_acessivel == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                        $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                        $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                        $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    case strtoupper("Número de apresentações – Sala Sérgio Cardoso"):

                                        //$numApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->sessao_acessivel == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;

                                    /*CAMPO DE PREENCHIMENTO ABERTO*/
                                    /*Número de adaptações – Sala Paschoal Carlos Magno*/
                                    case strtoupper("Número de adaptações – Sala Paschoal Carlos Magno"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número de apresentações – Sala Paschoal Carlos Magno"):

                                        //$numApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->sessao_acessivel == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                    case strtoupper("Número de apresentações – Festival Paulista de Circo"):

                                        //$numApresentacoes = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        foreach($atividades as $atividade)
                                        {
                                            if($atividade->sessao_acessivel == 1 && $acao->programa->id == $atividade->programa->id)
                                            {
                                                //$numApresentacoes++;
                                                $date_mes = date_parse_from_format("Y-m-d", $atividade->data);
                                                if($date_mes["month"] == 1 || $date_mes["month"] == 2 || $date_mes["month"] == 3)
                                                {
                                                    $alcancado_1_tri++;
                                                }
                                                elseif ($date_mes["month"] == 4 || $date_mes["month"] == 5 || $date_mes["month"] == 6)
                                                {
                                                   $alcancado_2_tri++;
                                                }
                                                elseif ($date_mes["month"] == 7 || $date_mes["month"] == 8 || $date_mes["month"] == 9)
                                                {
                                                    $alcancado_3_tri++;
                                                }
                                                elseif ($date_mes["month"] == 10 || $date_mes["month"] == 11 || $date_mes["month"] == 12)
                                                {
                                                    $alcancado_4_tri++;
                                                }
                                            }
                                        }
                                        //echo "Numero de apresentações pela OS: ".$totalApresentacoesOs."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        //$campos[$countCampos]["alcancado"] = $numApresentacoes;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                         $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                         $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                         $campos[$countCampos]["indicador_id"] = $indicador->id;

                                    break;


                                    /*Campos de preenchimento aberto no relatorio*/
                                    /*Número de Oficinas*/
                                    /*Número de Vagas*/
                                    case strtoupper("Percentual sobre o valor destinado pelo Contrato de Gestão às atividades vinculadas aos equipamentos (Teatro Sérgio Cardoso - SP, Teatro Francisco Paulo Russo - Araras e Centro de Cultura, Memória e Estudos da Diversidade Sexual)"):
                                        //$totalPessoas = 0;
                                        $alcancado_1_tri = 0;
                                        $alcancado_2_tri = 0;
                                        $alcancado_3_tri = 0;
                                        $alcancado_4_tri = 0;
                                        //echo "Número de Total de Pessoas: ".$totalPessoas."<br>";
                                        $campos[$countCampos]["nome"] = $indicador->nome_indicador;
                                        $campos[$countCampos]["campo_aberto"] = true;
                                        //$campos[$countCampos]["alcancado"] = $totalPessoas;
                                        //$campos[$countCampos]["meta"] = $indicador->meta;
                                        $campos[$countCampos]["alcancado_1_tri"] = $alcancado_1_tri;
                                        $campos[$countCampos]["alcancado_2_tri"] = $alcancado_2_tri;
                                        $campos[$countCampos]["alcancado_3_tri"] = $alcancado_3_tri;
                                        $campos[$countCampos]["alcancado_4_tri"] = $alcancado_4_tri;
                                        $campos[$countCampos]["meta_1_tri"] = $indicador->meta_1_tri;
                                        $campos[$countCampos]["meta_2_tri"] = $indicador->meta_2_tri;
                                        $campos[$countCampos]["meta_3_tri"] = $indicador->meta_3_tri;
                                        $campos[$countCampos]["meta_4_tri"] = $indicador->meta_4_tri;
                                        $campos[$countCampos]["justificativa"] = $indicador->justificativa;
                                        $campos[$countCampos]["justificativa2"] = $indicador->justificativa2;
                                        $campos[$countCampos]["indicador_id"] = $indicador->id;
                                    break;

                                }
                                //echo $countCampos."<br>";
                                $countCampos++;
                                continue;
                            }
                        }
                        $arrayInfo[$count]["acao"] = $acao->codigo_acao;
                        $arrayInfo[$count]["campos"] = $campos;
                        $arrayInfo[$count]["existe_campo_aberto"] = false;
                        $count++;

                    break;


                }
            }
            }
            }

        }

            //dd($arrayInfo);
            //die();
            $programas = $this->programa->name($request->get('nome'))->where('plano_id',$id)->whereIn('id',$programas_id)->orderBy('created_at','desc')->get();
        }
            return view('relatorio.relatorio-trimestral', ['indicador' => $indicador,'acoes' => $acoes,'arrayInfo' => $arrayInfo, 'regras' => $regras, 'planos' => $planos, 'programas' => $programas]);
    }


}
