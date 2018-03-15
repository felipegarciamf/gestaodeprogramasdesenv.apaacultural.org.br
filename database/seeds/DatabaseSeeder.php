<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        //Meu Admin
        factory('App\User')->create(
        	[
	            'email' => "eiglimar.junior@mfconsulting.com.br",
	            'nome' => "Eiglimar",
	            'sobrenome' => "Junior",
                'perfil' => 2,
	            'senha' => bcrypt("ugabuga123"),
	            'remember_token' => str_random(10)
            ]
        );


        //Admin APAA
        factory('App\User')->create(
            [
                'email' => "apaa@apaa.org.br",
                'nome' => "Associação dos Amigos",
                'sobrenome' => "da Arte",
                'perfil' => 2,
                'senha' => bcrypt("apaa"),
                'remember_token' => str_random(10)
            ]
        );

        //CG
        factory('App\Cg')->create([
            'nome' => '01/2004'
        ]);
        factory('App\Cg')->create([
            'nome' => '02/2004'
        ]);
        factory('App\Cg')->create([
            'nome' => '03/2005'
        ]);
        factory('App\Cg')->create([
            'nome' => '04/2005'
        ]);
        factory('App\Cg')->create([
            'nome' => '05/2005'
        ]);
        factory('App\Cg')->create([
            'nome' => '06/2005'
        ]);
        factory('App\Cg')->create([
            'nome' => '07/2005'
        ]);
        factory('App\Cg')->create([
            'nome' => '08/2005'
        ]);
        factory('App\Cg')->create([
            'nome' => '09/2005'
        ]);
        factory('App\Cg')->create([
            'nome' => '11/2006'
        ]);
        factory('App\Cg')->create([
            'nome' => '12/2006'
        ]);
        factory('App\Cg')->create([
            'nome' => '13/2006'
        ]);
        factory('App\Cg')->create([
            'nome' => '14/2006'
        ]);
        factory('App\Cg')->create([
            'nome' => '15/2007'
        ]);
        factory('App\Cg')->create([
            'nome' => '16/2007'
        ]);
        factory('App\Cg')->create([
            'nome' => '17/2007'
        ]);
        factory('App\Cg')->create([
            'nome' => '18/2007'
        ]);
        factory('App\Cg')->create([
            'nome' => '19/2007'
        ]);
        factory('App\Cg')->create([
            'nome' => '20/2007'
        ]);
        factory('App\Cg')->create([
            'nome' => '21/2008'
        ]);
        factory('App\Cg')->create([
            'nome' => '22/2008'
        ]);
        factory('App\Cg')->create([
            'nome' => '23/2008'
        ]);
        factory('App\Cg')->create([
            'nome' => '24/2008'
        ]);
        factory('App\Cg')->create([
            'nome' => '25/2008'
        ]);
        factory('App\Cg')->create([
            'nome' => '26/2008'
        ]);
        factory('App\Cg')->create([
            'nome' => '27/2008'
        ]); 
        factory('App\Cg')->create([
            'nome' => '28/2008'
        ]);
        factory('App\Cg')->create([
            'nome' => '29/2008'
        ]);
        factory('App\Cg')->create([
            'nome' => '30/2008'
        ]);
        factory('App\Cg')->create([
            'nome' => '31/2008'
        ]);
        factory('App\Cg')->create([
            'nome' => '32/2008'
        ]);
        factory('App\Cg')->create([
            'nome' => '33/2008'
        ]);
        factory('App\Cg')->create([
            'nome' => '34/2008'
        ]); 
        factory('App\Cg')->create([
            'nome' => '35/2008'
        ]);
        factory('App\Cg')->create([
            'nome' => '36/2008'
        ]);
        factory('App\Cg')->create([
            'nome' => '37/2009'
        ]);
        factory('App\Cg')->create([
            'nome' => '38/2009'
        ]);
        factory('App\Cg')->create([
            'nome' => '39/2009'
        ]);
        factory('App\Cg')->create([
            'nome' => '40/2009'
        ]);
        factory('App\Cg')->create([
            'nome' => '41/2010'
        ]);
        factory('App\Cg')->create([
            'nome' => '42/2010'
        ]);
        factory('App\Cg')->create([
            'nome' => '43/2010'
        ]);
        factory('App\Cg')->create([
            'nome' => '44/2010'
        ]);
        factory('App\Cg')->create([
            'nome' => '46/2010'
        ]);
        factory('App\Cg')->create([
            'nome' => '01/2011'
        ]);
        factory('App\Cg')->create([
            'nome' => '02/2011'
        ]);
        factory('App\Cg')->create([
            'nome' => '03/2011'
        ]);
        factory('App\Cg')->create([
            'nome' => '04/2011'
        ]);
        factory('App\Cg')->create([
            'nome' => '05/2011'
        ]);
        factory('App\Cg')->create([
            'nome' => '06/2011'
        ]);
        factory('App\Cg')->create([
            'nome' => '07/2011'
        ]);
        factory('App\Cg')->create([
            'nome' => '08/2011'
        ]);
        factory('App\Cg')->create([
            'nome' => '09/2011'
        ]);
        factory('App\Cg')->create([
            'nome' => '10/2011'
        ]);
        factory('App\Cg')->create([
            'nome' => '01/2012'
        ]);
        factory('App\Cg')->create([
            'nome' => '02/2012'
        ]);
        factory('App\Cg')->create([
            'nome' => '03/2012'
        ]);
        factory('App\Cg')->create([
            'nome' => '04/2012'
        ]);
        factory('App\Cg')->create([
            'nome' => '05/2012'
        ]);
        factory('App\Cg')->create([
            'nome' => '06/2012'
        ]);
        factory('App\Cg')->create([
            'nome' => '07/2012'
        ]);
        factory('App\Cg')->create([
            'nome' => '01/2013'
        ]);
        factory('App\Cg')->create([
            'nome' => '02/2013'
        ]);
        factory('App\Cg')->create([
            'nome' => '03/2013'
        ]);
        factory('App\Cg')->create([
            'nome' => '04/2013'
        ]);
        factory('App\Cg')->create([
            'nome' => '05/2013'
        ]);
        factory('App\Cg')->create([
            'nome' => '06/2013'
        ]);
        factory('App\Cg')->create([
            'nome' => '07/2013'
        ]);
        factory('App\Cg')->create([
            'nome' => '08/2013'
        ]);
        factory('App\Cg')->create([
            'nome' => '01/2014'
        ]);

        //Objeto
        factory('App\Objeto')->create([
            'nome' =>  'Apoio a Festivais'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Apoio ao SiSEM'
        ]);

        factory('App\Objeto')->create([
            'nome' =>  'Atendimento aos Municípios'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Auditório Cláudio Santoro'
        ]);

        factory('App\Objeto')->create([
            'nome' =>  'Banda do Estado de São Paulo'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Biblioteca de São Paulo'
        ]);

        factory('App\Objeto')->create([
            'nome' =>  'Biblioteca Parque Villa Lobos'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Casa das Rosas – Espaço Haroldo de Campos de Poesia e Literatura'
        ]);

        factory('App\Objeto')->create([
            'nome' =>  'Casa Guilherme de Almeida'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Centro Cultural de Estudos Superiores Aúthos Pagano'
        ]);

        factory('App\Objeto')->create([
            'nome' =>  'Circuito Cultural Paulista'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Complexo Cultural Julio Prestes / Sala São Paulo'
        ]);

        factory('App\Objeto')->create([
            'nome' =>  'Conservatório Dramático e Musical ”Dr. Carlos de Campos” de  Tatuí'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Cultura Livre de São Paulo'
        ]);

        factory('App\Objeto')->create([
            'nome' =>  'EMESP Tom Jobim – Escola de Música do Estado de São Paulo'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Espaço Cultural da Criança / Museu Catavento'
        ]);

        factory('App\Objeto')->create([
            'nome' =>  'Estação Pinacoteca'
        ]);  

        factory('App\Objeto')->create([
        'nome' =>  'Fábrica de Cultura Brasilância (ZN)'
        ]);

        factory('App\Objeto')->create([
            'nome' =>  'Fábrica de Cultura Capão Redondo (ZS)'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Fábrica de Cultura Cidade Tiradentes (ZL)'
        ]);

        factory('App\Objeto')->create([
            'nome' =>  'Fábrica de Cultura de Itaim Paulista (ZL)'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Fábrica de Cultura de Vila Curuçá (ZL)'
        ]);

        factory('App\Objeto')->create([
            'nome' =>  'Fábrica de Cultura do Parque Belem (ZL)'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Fábrica de Cultura Jaçanã (ZN)'
        ]);

        factory('App\Objeto')->create([
            'nome' =>  'Fabrica de Cultura Jardim São Luiz (ZS)'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Fábrica de Cultura Sapopemba (ZL)'
        ]);

        factory('App\Objeto')->create([
            'nome' =>  'Fábrica de Cultura Vila Nova Cachoeirinha (ZN)'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Festivais (Circo, Mantiqueira, Arte para Criança)'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Mapa Cultural Paulista'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Memorial da Resistência'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Museu Afro Brasil'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Museu Casa de Portinari'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Museu da Casa Brasileira'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Museu da Imagem e do Som'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Museu da Imigração'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Museu de Arte Sacra'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Museu de Esculturas “Felícia Leirner”'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Museu do Café'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Museu do Futebol'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Museu Histórico e Pedagógico “Índia Vanuíre”'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Museu Língua Portuguesa'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Oficinas Culturais'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Orquestra do Theatro São Pedro'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Orquestra Jazz Sinfônica do Estado de São Paulo'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'OSESP - Orquestra Sinfônica do Estado de SP'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Outras Ações Culturais (SG Novaes, Programa Inclusão, Mostra de Artes, Plataformas)'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Paço das Artes'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Pinacoteca do Estado de São Paulo'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Programa de Leitura do Estado'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Programa Ópera Curta'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Projeto Guri Capital e Grande São Paulo'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Projeto Guri Interior, Litoral e Fundação CASA'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Revelando São Paulo'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'São Paulo Companhia de Dança'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'São Paulo Escola de Teatro'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Sistema Paulista de Música'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Teatro Estadual Maestro Francisco Paulo Russo'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Teatro Sergio Cardoso'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Theatro São Pedro'
        ]);

        factory('App\Objeto')->create([
        'nome' =>  'Virada Cultural - Paulista e Paulistana'
        ]);

        //OS
        factory('App\Os')->create([
        'nome' => 'AACT'
        ]);
        factory('App\Os')->create([
        'nome' => 'AAPG'
        ]);
        factory('App\Os')->create([
        'nome' => 'ABAÇAI'
        ]);
        factory('App\Os')->create([
        'nome' => 'ACAMP'
        ]);
        factory('App\Os')->create([
        'nome' => 'ACASA'
        ]);
        factory('App\Os')->create([
        'nome' => 'ADAAP'
        ]);
        factory('App\Os')->create([
        'nome' => 'AMAB'
        ]);
        factory('App\Os')->create([
        'nome' => 'APAA'
        ]);
        factory('App\Os')->create([
        'nome' => 'APAC'
        ]);
        factory('App\Os')->create([
        'nome' => 'APAF'
        ]);
        factory('App\Os')->create([
        'nome' => 'APD'
        ]);
        factory('App\Os')->create([
        'nome' => 'CATAVENTO'
        ]);
        factory('App\Os')->create([
        'nome' => 'FOSESP'
        ]);
        factory('App\Os')->create([
        'nome' => 'IDBRASIL'
        ]);
        factory('App\Os')->create([
        'nome' => 'INCI'
        ]);
        factory('App\Os')->create([
        'nome' => 'PENSARTE'
        ]);
        factory('App\Os')->create([
        'nome' => 'POIESIS'
        ]);
        factory('App\Os')->create([
        'nome' => 'SAMAS'
        ]);
        factory('App\Os')->create([
        'nome' => 'SMC'
        ]);
        factory('App\Os')->create([
        'nome' => 'SPLEITURAS'
        ]);

        //UGE
        factory('App\Uge')->create([
        'nome' => 'UBL'
        ]);
        factory('App\Uge')->create([
        'nome' => 'UFC'
        ]);
        factory('App\Uge')->create([
        'nome' => 'UFDPC'
        ]);
        factory('App\Uge')->create([
        'nome' => 'UPPM'
        ]);

        //TIPO_OBJETO
        factory('App\TipoObjeto')->create([
        'nome' => 'Equipamento Cultural'
        ]);
        factory('App\TipoObjeto')->create([
        'nome' => 'Grupo Artístico'
        ]);
        factory('App\TipoObjeto')->create([
        'nome' => 'Programa Cultural'
        ]);


        //Espécie Ação
        factory('App\EspecieAcao')->create([
        'nome' => 'Ação de Acervo'
        ]);
        factory('App\EspecieAcao')->create([
        'nome' => 'Ação Educativa'
        ]);
        factory('App\EspecieAcao')->create([
        'nome' => 'Apresentação Artística'
        ]);
        factory('App\EspecieAcao')->create([
        'nome' => 'Desenvolvimento Institucional'
        ]);
        factory('App\EspecieAcao')->create([
        'nome' => 'Evento'
        ]);
        factory('App\EspecieAcao')->create([
        'nome' => 'Exibição'
        ]);
        factory('App\EspecieAcao')->create([
        'nome' => 'Exposição'
        ]);
        factory('App\EspecieAcao')->create([
        'nome' => 'Festival'
        ]);
        factory('App\EspecieAcao')->create([
        'nome' => 'Fomento'
        ]);
        factory('App\EspecieAcao')->create([
        'nome' => 'Mediação'
        ]);
        factory('App\EspecieAcao')->create([
        'nome' => 'Premiação'
        ]);
        factory('App\EspecieAcao')->create([
        'nome' => 'Produção de Materiais e/ou Obras Artísticas'
        ]);
        factory('App\EspecieAcao')->create([
        'nome' => 'Recebimento de Visitantes'
        ]);


        //Linguagem Ação
        factory('App\LinguagemAcao')->create([
        'nome' => 'Artes Cênicas'
        ]);
        factory('App\LinguagemAcao')->create([
        'nome' => 'Artes da Palavra'
        ]);
        factory('App\LinguagemAcao')->create([
        'nome' => 'Artes Visuais'
        ]);
        factory('App\LinguagemAcao')->create([
        'nome' => 'Audiovisual e Artes Digitais'
        ]);
        factory('App\LinguagemAcao')->create([
        'nome' => 'Gestão Cultural'
        ]);
        factory('App\LinguagemAcao')->create([
        'nome' => 'Multilinguagens'
        ]);
        factory('App\LinguagemAcao')->create([
        'nome' => 'Música'
        ]);
        factory('App\LinguagemAcao')->create([
        'nome' => 'Patrimônio Cultural'
        ]);

        //Função Ação
        factory('App\FuncaoAcao')->create([
        'nome' => 'Criação'
        ]);
        factory('App\FuncaoAcao')->create([
        'nome' => 'Difusão'
        ]);
        factory('App\FuncaoAcao')->create([
        'nome' => 'Formação'
        ]);
        factory('App\FuncaoAcao')->create([
        'nome' => 'Governança'
        ]);
        factory('App\FuncaoAcao')->create([
        'nome' => 'Preservação'
        ]);


        //Tipo Publico 
        factory('App\TipoPublico')->create([
        'nome' => 'Presencial Local Sede ou Local Parceria'
        ]);
        factory('App\TipoPublico')->create([
        'nome' => 'Presencial extramuros ou externo'
        ]);
        factory('App\TipoPublico')->create([
        'nome' => 'Virtual'
        ]);
        factory('App\TipoPublico')->create([
        'nome' => 'Não se aplica'
        ]);

        //Engajamento Publico
        factory('App\EngajamentoPublico')->create([
        'nome' =>'Agendado / Mediado'
        ]);
        factory('App\EngajamentoPublico')->create([
        'nome' =>'Agente Cultural'
        ]);
        factory('App\EngajamentoPublico')->create([
        'nome' =>'Espontâneo'
        ]);
        factory('App\EngajamentoPublico')->create([
        'nome' =>'Pedagógico'
        ]);
        factory('App\EngajamentoPublico')->create([
        'nome' =>'Não se aplica'
        ]);

        //segmento publico
        factory('App\SegmentoPublico')->create([
        'nome' => 'Artista / Expositor'
        ]);
        factory('App\SegmentoPublico')->create([
        'nome' => 'Educador'
        ]);
        factory('App\SegmentoPublico')->create([
        'nome' => 'Em situação de vulnerabilidade'
        ]);
        factory('App\SegmentoPublico')->create([
        'nome' => 'Escolar (escola privada)'
        ]);
        factory('App\SegmentoPublico')->create([
        'nome' => 'Escolar (escola pública)'
        ]);
        factory('App\SegmentoPublico')->create([
        'nome' => 'Especialista / Universitário'
        ]);
        factory('App\SegmentoPublico')->create([
        'nome' => 'Família'
        ]);
        factory('App\SegmentoPublico')->create([
        'nome' => 'Infanto-juvenil'
        ]);
        factory('App\SegmentoPublico')->create([
        'nome' => 'Institucional'
        ]);
        factory('App\SegmentoPublico')->create([
        'nome' => 'Pessoas com deficiência'
        ]);
        factory('App\SegmentoPublico')->create([
        'nome' => 'Terceira idade'
        ]);
        factory('App\SegmentoPublico')->create([
        'nome' => 'Turista'
        ]);
        factory('App\SegmentoPublico')->create([
        'nome' => 'Não se aplica'
        ]);

        //Tipo Evento
        factory('App\TipoEvento')->create([
        'nome' => 'Temporada - meta'
        ]);
        factory('App\TipoEvento')->create([
        'nome' => 'Cessão não onerosa - evento artístico público'
        ]);
        factory('App\TipoEvento')->create([
        'nome' => 'Cessão não onerosa - evento privado ou não artístico'
        ]);
        factory('App\TipoEvento')->create([
        'nome' => 'Cessão não onerosa - evento público - meta parceiro'
        ]);


        //Região Administrativa
        factory('App\RegiaoAdministrativa')->create([
        'nome' => 'Região Administrativa Central'
        ]);
        factory('App\RegiaoAdministrativa')->create([
        'nome' => 'Região Administrativa de Araçatuba'
        ]);
        factory('App\RegiaoAdministrativa')->create([
        'nome' => 'Região Administrativa de Barretos'
        ]);
        factory('App\RegiaoAdministrativa')->create([
        'nome' => 'Região Administrativa de Bauru'
        ]);
        factory('App\RegiaoAdministrativa')->create([
        'nome' => 'Região Administrativa de Campinas'
        ]);
        factory('App\RegiaoAdministrativa')->create([
        'nome' => 'Região Administrativa de Franca'
        ]);
        factory('App\RegiaoAdministrativa')->create([
        'nome' => 'Região Administrativa de Itapeva'
        ]);
        factory('App\RegiaoAdministrativa')->create([
        'nome' => 'Região Administrativa de Marília'
        ]);
        factory('App\RegiaoAdministrativa')->create([
        'nome' => 'Região Administrativa de Presidente Prudente'
        ]);
        factory('App\RegiaoAdministrativa')->create([
        'nome' => 'Região Administrativa de Registro'
        ]);

        factory('App\RegiaoAdministrativa')->create([
        'nome' => 'Região Administrativa de Ribeirão Preto'
        ]);

        factory('App\RegiaoAdministrativa')->create([
        'nome' => 'Região Administrativa de Santos'
        ]);

        factory('App\RegiaoAdministrativa')->create([
        'nome' => 'Região Administrativa de São José do Rio Preto'
        ]);

        factory('App\RegiaoAdministrativa')->create([
        'nome' => 'Região Administrativa de São José dos Campos'
        ]);

        factory('App\RegiaoAdministrativa')->create([
        'nome' => 'Região Administrativa de Sorocaba'
        ]);

        factory('App\RegiaoAdministrativa')->create([
        'nome' => 'Região Metropolitana de São Paulo'
        ]);

        //Linguagem de Programa
        factory('App\LinguagemPrograma')->create([
        'nome' => 'Música'
        ]);
        factory('App\LinguagemPrograma')->create([
        'nome' => 'Circo'
        ]);
        factory('App\LinguagemPrograma')->create([
        'nome' => 'Dança'
        ]);
        factory('App\LinguagemPrograma')->create([
        'nome' => 'Teatro'
        ]);
        factory('App\LinguagemPrograma')->create([
        'nome' => 'Literatura'
        ]);
        factory('App\LinguagemPrograma')->create([
        'nome' => 'Artes Visuais'
        ]);
        factory('App\LinguagemPrograma')->create([
        'nome' => 'Cultura Popular'
        ]);
        factory('App\LinguagemPrograma')->create([
        'nome' => 'Artes Urbanas'
        ]);


        //Genero Linguagem
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Instrumental Clássico',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Instrumental Contemporâneo',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Solista',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Jazz',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Blues',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Funk',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Soul',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Black Music',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Reggae',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Afro',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Hip-hop',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'MC',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Rock',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'DJ',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Eletrônino',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Latina',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'MPB',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'nova MPB',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Sertanejo tradicional',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Pagode',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Axé',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Chorinho',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Bossa Nova',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Sertaneja',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Samba',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Samba-rock',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Forró',
        'linguagem_programa_id' => 1
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Tradicional',
        'linguagem_programa_id' => 2
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Contemporâneo',
        'linguagem_programa_id' => 2
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Circo-teatro',
        'linguagem_programa_id' => 2
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Solo',
        'linguagem_programa_id' => 2
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Aéreo',
        'linguagem_programa_id' => 2
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Clown',
        'linguagem_programa_id' => 2
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Clássica',
        'linguagem_programa_id' => 3
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Contemporânea',
        'linguagem_programa_id' => 3
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Popular',
        'linguagem_programa_id' => 3
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Urbana',
        'linguagem_programa_id' => 3
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Étnica',
        'linguagem_programa_id' => 3
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Dança de Salão',
        'linguagem_programa_id' => 3
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'De rua',
        'linguagem_programa_id' => 4
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Circo-teatro',
        'linguagem_programa_id' => 4
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Drama',
        'linguagem_programa_id' => 4
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Comédia',
        'linguagem_programa_id' => 4
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Tragicomédia',
        'linguagem_programa_id' => 4
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Experimental',
        'linguagem_programa_id' => 4
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Monólogo',
        'linguagem_programa_id' => 4
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Musical',
        'linguagem_programa_id' => 4
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Stand up',
        'linguagem_programa_id' => 4
        ]);
        factory('App\GeneroLinguagem')->create([
        'nome' => 'Leitura dramática',
        'linguagem_programa_id' => 4
        ]);

        //Realizador
        factory('App\Realizador')->create([
        'nome' => 'APAA'
        ]);
    }
}
