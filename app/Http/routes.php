<?php
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//DASHBOARD
Route::get('/', function () {
	if(Auth::check())
	{
		return redirect()->route('dashboard');
	}
	else
	{
    	return view('login');
	}
})->name('login'); //usado para ser route da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)

//logout
Route::get('/sair',[
	'uses' => 'UserController@logout',
	'as' => 'sair',
	'middleware' => 'auth'
]);

//autenticar o usuario
Route::post('/autentica',[
	'uses' => 'UserController@autentica',
	'as' => 'autentica'
]);

//Redirecionar para a pagina principal logado
Route::get('/dashboard',[
	'uses' => 'UserController@dashboardView',
	'as' => 'dashboard',
	'middleware' => 'auth'
	//'middleware' => 'auth' //usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
]);

//USUARIO
Route::group(['prefix' => 'usuario'], function () {

		//Redirecionar para a listagem de usuarios
		Route::get('/listar-usuarios',[
			'uses' => 'UserController@listarUsuarios',
			'as' => 'listar-usuarios',
			'middleware' => 'auth'
			//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
		]);

		//mostra view de cadastro de usuario
		Route::get('/cadastra-usuario',[
			'uses' => 'UserController@cadastraUsuarioView',
			'as' => 'cadastra-usuario',
			'middleware' => 'auth'
			//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
		]);

		//cadastrar usuario
		Route::post('/cria-usuario',[
			'uses' => 'UserController@cadastraUsuario',
			'as' => 'cria-usuario',
			'middleware' => 'auth'
		]);

		//Redirecionar para a listagem de permissoes de usuarios
		Route::get('/listar-usuarios-permissoes',[
			'uses' => 'UserController@listarUsuariosPermissoesView',
			'as' => 'listar-usuarios-permissoes',
			'middleware' => 'auth'
			//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
		]);

		//Redirecionar para a listagem de permissoes POR usuarios
		Route::get('/listar-permissoes-por-usuario/{id}',[
			'uses' => 'UserController@listarUsuariosPorPermissoesView',
			'as' => 'listar-permissoes-por-usuario',
			'middleware' => 'auth'
			//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
		]);

		//cadastrar permissoes usuario
		Route::get('/cadastra-permissoes-usuario/{id}',[
			'uses' => 'UserController@cadastraUsuarioPermissoesView',
			'as' => 'cadastra-permissoes-usuario',
			'middleware' => 'auth'
		]);

		//chamar programas via ajax a partir do id do plano
		Route::post('/chama-programas-permissao',[
			'uses' => 'UserController@ajaxPermissaoFetchProgramasByPlano',
			'as' => 'chama-programas-permissao',
			'middleware' => 'auth'			
		]);

		//cadastrar usuario
		Route::post('/cria-usuario-permissao',[
			'uses' => 'UserController@cadastraUsuarioPermissao',
			'as' => 'cria-usuario-permissao',
			'middleware' => 'auth'					
		]);

		//deletar usuario permissao
		Route::get('/delete-usuario-permissao/{id}',[
			'uses' => 'UserController@deleteUsuarioPermissao',
			'as' => 'delete-usuario-permissao',
			'middleware' => 'auth'		
		]);

		//editar usuario
		Route::post('/update-usuario/{id}',[
			'uses' => 'UserController@editUsuario',
			'as' => 'update-usuario',
			'middleware' => 'auth'
		]);

		//editar perfil usuario view
		Route::get('/editar-perfil/{id}',[
			'uses' => 'UserController@editProfileView',
			'as' => 'editar-perfil',
			'middleware' => 'auth'
		]);

		//editar perfil usuario
		Route::post('/update-perfil/{id}',[
			'uses' => 'UserController@editProfile',
			'as' => 'update-perfil',
			'middleware' => 'auth'
		]);


		//editar usuario view
		Route::get('/editar-usuario/{id}',[
			'uses' => 'UserController@editUsuarioView',
			'as' => 'editar-usuario',
			'middleware' => 'auth'
		]);

		//deletar usuario
		Route::get('/delete-usuario/{id}',[
			'uses' => 'UserController@deleteUsuario',
			'as' => 'delete-usuario',
			'middleware' => 'auth'
		]);
});

//CGS
Route::group(['prefix' => 'cg'], function () {

	//Redirecionar para a listagem de cgs
	Route::get('/listar-cgs',[
		'uses' => 'CgController@listarCg',
		'as' => 'listar-cgs',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//mostra view de cadastro de cg
	Route::get('/cadastra-cg',[
		'uses' => 'CgController@cadastraCgView',
		'as' => 'cadastra-cg',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar cg
	Route::post('/cria-cg',[
		'uses' => 'CgController@cadastraCg',
		'as' => 'cria-cg',
		'middleware' => 'auth'
	]);

	//editar cg
	Route::post('/update-cg/{id}',[
		'uses' => 'CgController@editCg',
		'as' => 'update-cg',
		'middleware' => 'auth'
	]);

	//deletar cg
	Route::get('/delete-cg/{id}',[
		'uses' => 'CgController@deleteCg',
		'as' => 'delete-cg',
		'middleware' => 'auth'
	]);


	//editar cg view
	Route::get('/editar-cg/{id}',[
		'uses' => 'CgController@editCgView',
		'as' => 'editar-cg',
		'middleware' => 'auth'
	]);

});

//OBJETO
Route::group(['prefix' => 'objeto'], function () {

	//Redirecionar para a listagem de objetos
	Route::get('/listar-objetos',[
		'uses' => 'ObjetoController@listarObjeto',
		'as' => 'listar-objetos',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//mostra view de cadastro de objeto
	Route::get('/cadastra-objeto',[
		'uses' => 'ObjetoController@cadastraObjetoView',
		'as' => 'cadastra-objeto',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar objeto
	Route::post('/cria-objeto',[
		'uses' => 'ObjetoController@cadastraObjeto',
		'as' => 'cria-objeto',
		'middleware' => 'auth'
	]);

	//editar objeto
	Route::post('/update-objeto/{id}',[
		'uses' => 'ObjetoController@editObjeto',
		'as' => 'update-objeto',
		'middleware' => 'auth'
	]);

	//deletar objeto
	Route::get('/delete-objeto/{id}',[
		'uses' => 'ObjetoController@deleteObjeto',
		'as' => 'delete-objeto',
		'middleware' => 'auth'
	]);


	//editar objeto view
	Route::get('/editar-objeto/{id}',[
		'uses' => 'ObjetoController@editObjetoView',
		'as' => 'editar-objeto',
		'middleware' => 'auth'
	]);

});

//OS
Route::group(['prefix' => 'os'], function () {

	//Redirecionar para a listagem de oss
	Route::get('/listar-oss',[
		'uses' => 'OsController@listarOs',
		'as' => 'listar-oss',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//mostra view de cadastro de os
	Route::get('/cadastra-os',[
		'uses' => 'OsController@cadastraOsView',
		'as' => 'cadastra-os',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar os
	Route::post('/cria-os',[
		'uses' => 'OsController@cadastraOs',
		'as' => 'cria-os',
		'middleware' => 'auth'
	]);

	//editar os
	Route::post('/update-os/{id}',[
		'uses' => 'OsController@editOs',
		'as' => 'update-os',
		'middleware' => 'auth'
	]);

	//deletar os
	Route::get('/delete-os/{id}',[
		'uses' => 'OsController@deleteOs',
		'as' => 'delete-os',
		'middleware' => 'auth'
	]);


	//editar os view
	Route::get('/editar-os/{id}',[
		'uses' => 'OsController@editOsView',
		'as' => 'editar-os',
		'middleware' => 'auth'
	]);

});

//UGE
Route::group(['prefix' => 'uge'], function () {

	//Redirecionar para a listagem de uges
	Route::get('/listar-uges',[
		'uses' => 'UgeController@listarUge',
		'as' => 'listar-uges',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//mostra view de cadastro de uge
	Route::get('/cadastra-uge',[
		'uses' => 'UgeController@cadastraUgeView',
		'as' => 'cadastra-uge',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar uge
	Route::post('/cria-uge',[
		'uses' => 'UgeController@cadastraUge',
		'as' => 'cria-uge',
		'middleware' => 'auth'
	]);

	//editar uge
	Route::post('/update-uge/{id}',[
		'uses' => 'UgeController@editUge',
		'as' => 'update-uge',
		'middleware' => 'auth'
	]);

	//deletar uge
	Route::get('/delete-uge/{id}',[
		'uses' => 'UgeController@deleteUge',
		'as' => 'delete-uge',
		'middleware' => 'auth'
	]);


	//editar uge view
	Route::get('/editar-uge/{id}',[
		'uses' => 'UgeController@editUgeView',
		'as' => 'editar-uge',
		'middleware' => 'auth'
	]);

});

//TIPO OBJETO
Route::group(['prefix' => 'tipo-objeto'], function () {

	//Redirecionar para a listagem de tipo objetos
	Route::get('/listar-tipo-objetos',[
		'uses' => 'TipoObjetoController@listarTipoObjeto',
		'as' => 'listar-tipo-objetos',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//mostra view de cadastro de tipo objeto
	Route::get('/cadastra-tipo-objeto',[
		'uses' => 'TipoObjetoController@cadastraTipoObjetoView',
		'as' => 'cadastra-tipo-objeto',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar tipo objeto
	Route::post('/cria-tipo-objeto',[
		'uses' => 'TipoObjetoController@cadastraTipoObjeto',
		'as' => 'cria-tipo-objeto',
		'middleware' => 'auth'
	]);

	//editar tipo objeto
	Route::post('/update-tipo-objeto/{id}',[
		'uses' => 'TipoObjetoController@editTipoObjeto',
		'as' => 'update-tipo-objeto',
		'middleware' => 'auth'
	]);

	//deletar tipo objeto
	Route::get('/delete-tipo-objeto/{id}',[
		'uses' => 'TipoObjetoController@deleteTipoObjeto',
		'as' => 'delete-tipo-objeto',
		'middleware' => 'auth'
	]);


	//editar tipo objeto view
	Route::get('/editar-tipo-objeto/{id}',[
		'uses' => 'TipoObjetoController@editTipoObjetoView',
		'as' => 'editar-tipo-objeto',
		'middleware' => 'auth'
	]);

});

//TIPAGEM
Route::group(['prefix' => 'tipagem'], function () {

	//Redirecionar para a listagem de tipagens
	Route::get('/listar-tipagens',[
		'uses' => 'TipagemController@listarTipagem',
		'as' => 'listar-tipagens',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//mostra view de cadastro de tipagem
	Route::get('/cadastra-tipagem',[
		'uses' => 'TipagemController@cadastraTipagemView',
		'as' => 'cadastra-tipagem',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar tipagem
	Route::post('/cria-tipagem',[
		'uses' => 'TipagemController@cadastraTipagem',
		'as' => 'cria-tipagem',
		'middleware' => 'auth'
	]);

	//editar tipagem
	Route::post('/update-tipagem/{id}',[
		'uses' => 'TipagemController@editTipagem',
		'as' => 'update-tipagem',
		'middleware' => 'auth'
	]);

	//deletar tipagem
	Route::get('/delete-tipagem/{id}',[
		'uses' => 'TipagemController@deleteTipagem',
		'as' => 'delete-tipagem',
		'middleware' => 'auth'
	]);


	//editar tipagem view
	Route::get('/editar-tipagem/{id}',[
		'uses' => 'TipagemController@editTipagemView',
		'as' => 'editar-tipagem',
		'middleware' => 'auth'
	]);

});

//ROTINAS
Route::group(['prefix' => 'rotina'], function () {

	//Redirecionar para a listagem de rotinas
	Route::get('/listar-rotinas',[
		'uses' => 'RotinaController@listarRotina',
		'as' => 'listar-rotinas',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//mostra view de cadastro de rotina
	Route::get('/cadastra-rotina',[
		'uses' => 'RotinaController@cadastraRotinaView',
		'as' => 'cadastra-rotina',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar rotina
	Route::post('/cria-rotina',[
		'uses' => 'RotinaController@cadastraRotina',
		'as' => 'cria-rotina',
		'middleware' => 'auth'
	]);

	//editar rotina
	Route::post('/update-rotina/{id}',[
		'uses' => 'RotinaController@editRotina',
		'as' => 'update-rotina',
		'middleware' => 'auth'
	]);

	//deletar rotina
	Route::get('/delete-rotina/{id}',[
		'uses' => 'RotinaController@deleteRotina',
		'as' => 'delete-rotina',
		'middleware' => 'auth'
	]);


	//editar rotina view
	Route::get('/editar-rotina/{id}',[
		'uses' => 'RotinaController@editRotinaView',
		'as' => 'editar-rotina',
		'middleware' => 'auth'
	]);

});

//PLANO
Route::group(['prefix' => 'plano'], function () {

	//Redirecionar para a listagem de planos
	Route::get('/listar-planos',[
		'uses' => 'PlanoController@listarPlano',
		'as' => 'listar-planos',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//mostra view de cadastro de plano
	Route::get('/cadastra-plano',[
		'uses' => 'PlanoController@cadastraPlanoView',
		'as' => 'cadastra-plano',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar plano
	Route::post('/cria-plano',[
		'uses' => 'PlanoController@cadastraPlano',
		'as' => 'cria-plano',
		'middleware' => 'auth'
	]);

	//editar plano
	Route::post('/update-plano/{id}',[
		'uses' => 'PlanoController@editPlano',
		'as' => 'update-plano',
		'middleware' => 'auth'
	]);

	//deletar plano
	Route::get('/delete-plano/{id}',[
		'uses' => 'PlanoController@deletePlano',
		'as' => 'delete-plano',
		'middleware' => 'auth'
	]);


	//editar plano view
	Route::get('/editar-plano/{id}',[
		'uses' => 'PlanoController@editPlanoView',
		'as' => 'editar-plano',
		'middleware' => 'auth'
	]);

});

//TIPO DE PUBLICO
Route::group(['prefix' => 'tipo-publico'], function () {

	//Redirecionar para a listagem de tipos de publico
	Route::get('/listar-tipo-publicos',[
		'uses' => 'TipoPublicoController@listarTipoPublico',
		'as' => 'listar-tipo-publicos',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//mostra view de cadastro de tipos de publico
	Route::get('/cadastra-tipo-publico',[
		'uses' => 'TipoPublicoController@cadastraTipoPublicoView',
		'as' => 'cadastra-tipo-publico',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar de tipos de publico
	Route::post('/cria-tipo-publico',[
		'uses' => 'TipoPublicoController@cadastraTipoPublico',
		'as' => 'cria-tipo-publico',
		'middleware' => 'auth'
	]);

	//editar tipo de publico
	Route::post('/update-tipo-publico/{id}',[
		'uses' => 'TipoPublicoController@editTipoPublico',
		'as' => 'update-tipo-publico',
		'middleware' => 'auth'
	]);

	//deletar tipo de publico
	Route::get('/delete-tipo-publico/{id}',[
		'uses' => 'TipoPublicoController@deleteTipoPublico',
		'as' => 'delete-tipo-publico',
		'middleware' => 'auth'
	]);


	//editar tipo de publico view
	Route::get('/editar-tipo-publico/{id}',[
		'uses' => 'TipoPublicoController@editTipoPublicoView',
		'as' => 'editar-tipo-publico',
		'middleware' => 'auth'
	]);

});

//Engajamento DE PUBLICO
Route::group(['prefix' => 'engajamento-publico'], function () {

	//Redirecionar para a listagem de engajamento de publico
	Route::get('/listar-engajamento-publicos',[
		'uses' => 'EngajamentoPublicoController@listarEngajamentoPublico',
		'as' => 'listar-engajamento-publicos',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//mostra view de cadastro de engajamento de publico
	Route::get('/cadastra-engajamento-publico',[
		'uses' => 'EngajamentoPublicoController@cadastraEngajamentoPublicoView',
		'as' => 'cadastra-engajamento-publico',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar engajamento de publico
	Route::post('/cria-engajamento-publico',[
		'uses' => 'EngajamentoPublicoController@cadastraEngajamentoPublico',
		'as' => 'cria-engajamento-publico',
		'middleware' => 'auth'
	]);

	//editar engajamento de publico
	Route::post('/update-engajamento-publico/{id}',[
		'uses' => 'EngajamentoPublicoController@editEngajamentoPublico',
		'as' => 'update-engajamento-publico',
		'middleware' => 'auth'
	]);

	//deletar engajamento de publico
	Route::get('/delete-engajamento-publico/{id}',[
		'uses' => 'EngajamentoPublicoController@deleteEngajamentoPublico',
		'as' => 'delete-engajamento-publico',
		'middleware' => 'auth'
	]);


	//editar engajamento de publico view
	Route::get('/editar-engajamento-publico/{id}',[
		'uses' => 'EngajamentoPublicoController@editEngajamentoPublicoView',
		'as' => 'editar-engajamento-publico',
		'middleware' => 'auth'
	]);

});

//Regiao Administrativa
Route::group(['prefix' => 'regiao-administrativa'], function () {

	//Redirecionar para a listagem de regiões administrativas
	Route::get('/listar-regiao-administrativas',[
		'uses' => 'RegiaoAdministrativaController@listarRegiaoAdministrativa',
		'as' => 'listar-regiao-administrativas',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//mostra view de cadastro de região administrativa
	Route::get('/cadastra-regiao-administrativa',[
		'uses' => 'RegiaoAdministrativaController@cadastraRegiaoAdministrativaView',
		'as' => 'cadastra-regiao-administrativa',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar região administrativa
	Route::post('/cria-regiao-administrativa',[
		'uses' => 'RegiaoAdministrativaController@cadastraRegiaoAdministrativa',
		'as' => 'cria-regiao-administrativa',
		'middleware' => 'auth'
	]);

	//editar região administrativa
	Route::post('/update-regiao-administrativa/{id}',[
		'uses' => 'RegiaoAdministrativaController@editRegiaoAdministrativa',
		'as' => 'update-regiao-administrativa',
		'middleware' => 'auth'
	]);

	//deletar região administrativa
	Route::get('/delete-regiao-administrativa/{id}',[
		'uses' => 'RegiaoAdministrativaController@deleteRegiaoAdministrativa',
		'as' => 'delete-regiao-administrativa',
		'middleware' => 'auth'
	]);


	//editar região administrativa view
	Route::get('/editar-regiao-administrativa/{id}',[
		'uses' => 'RegiaoAdministrativaController@editRegiaoAdministrativaView',
		'as' => 'editar-regiao-administrativa',
		'middleware' => 'auth'
	]);

});

//Municipios
Route::group(['prefix' => 'municipios'], function () {

	//Redirecionar para a listagem de municipios
	Route::get('/listar-municipios',[
		'uses' => 'MunicipioController@listarMunicipio',
		'as' => 'listar-municipios',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//mostra view de cadastro de municipios
	Route::get('/cadastra-municipio',[
		'uses' => 'MunicipioController@cadastraMunicipioView',
		'as' => 'cadastra-municipio',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar municipio
	Route::post('/cria-municipio',[
		'uses' => 'MunicipioController@cadastraMunicipio',
		'as' => 'cria-municipio',
		'middleware' => 'auth'
	]);

	//editar municipio
	Route::post('/update-municipio/{id}',[
		'uses' => 'MunicipioController@editMunicipio',
		'as' => 'update-municipio',
		'middleware' => 'auth'
	]);

	//deletar municipio
	Route::get('/delete-municipio/{id}',[
		'uses' => 'MunicipioController@deleteMunicipio',
		'as' => 'delete-municipio',
		'middleware' => 'auth'
	]);


	//editar municipio view
	Route::get('/editar-municipio/{id}',[
		'uses' => 'MunicipioController@editMunicipioView',
		'as' => 'editar-municipio',
		'middleware' => 'auth'
	]);

});

//Segmento DE PUBLICO
Route::group(['prefix' => 'segmento-publico'], function () {

	//Redirecionar para a listagem de segmento de publico
	Route::get('/listar-segmento-publicos',[
		'uses' => 'SegmentoPublicoController@listarSegmentoPublico',
		'as' => 'listar-segmento-publicos',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//mostra view de cadastro de segmento de publico
	Route::get('/cadastra-segmento-publico',[
		'uses' => 'SegmentoPublicoController@cadastraSegmentoPublicoView',
		'as' => 'cadastra-segmento-publico',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar segmento de publico
	Route::post('/cria-segmento-publico',[
		'uses' => 'SegmentoPublicoController@cadastraSegmentoPublico',
		'as' => 'cria-segmento-publico',
		'middleware' => 'auth'
	]);

	//editar segmento de publico
	Route::post('/update-segmento-publico/{id}',[
		'uses' => 'SegmentoPublicoController@editSegmentoPublico',
		'as' => 'update-segmento-publico',
		'middleware' => 'auth'
	]);

	//deletar segmento de publico
	Route::get('/delete-segmento-publico/{id}',[
		'uses' => 'SegmentoPublicoController@deleteSegmentoPublico',
		'as' => 'delete-segmento-publico',
		'middleware' => 'auth'
	]);


	//editar segmento de publico view
	Route::get('/editar-segmento-publico/{id}',[
		'uses' => 'SegmentoPublicoController@editSegmentoPublicoView',
		'as' => 'editar-segmento-publico',
		'middleware' => 'auth'
	]);
});

//Realizador
Route::group(['prefix' => 'realizador'], function () {

	//Redirecionar para a listagem de realizadores
	Route::get('/listar-realizadores',[
		'uses' => 'RealizadorController@listarRealizador',
		'as' => 'listar-realizadores',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//mostra view de cadastro de realizador
	Route::get('/cadastra-realizador',[
		'uses' => 'RealizadorController@cadastraRealizadorView',
		'as' => 'cadastra-realizador',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar realizador
	Route::post('/cria-realizador',[
		'uses' => 'RealizadorController@cadastraRealizador',
		'as' => 'cria-realizador',
		'middleware' => 'auth'
	]);

	//editar realizador
	Route::post('/update-realizador/{id}',[
		'uses' => 'RealizadorController@editRealizador',
		'as' => 'update-realizador',
		'middleware' => 'auth'
	]);

	//deletar realizador
	Route::get('/delete-realizador/{id}',[
		'uses' => 'RealizadorController@deleteRealizador',
		'as' => 'delete-realizador',
		'middleware' => 'auth'
	]);


	//editar realizador view
	Route::get('/editar-realizador/{id}',[
		'uses' => 'RealizadorController@editRealizadorView',
		'as' => 'editar-realizador',
		'middleware' => 'auth'
	]);
});

//Linguagem Programa
Route::group(['prefix' => 'linguagem-programa'], function () {

	//Redirecionar para a listagem de linguagens de programa
	Route::get('/listar-linguagem-programas',[
		'uses' => 'LinguagemProgramaController@listarLinguagemPrograma',
		'as' => 'listar-linguagem-programas',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//mostra view de cadastro de linguagem de programa
	Route::get('/cadastra-linguagem-programa',[
		'uses' => 'LinguagemProgramaController@cadastraLinguagemProgramaView',
		'as' => 'cadastra-linguagem-programa',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar linguagem de programa
	Route::post('/cria-linguagem-programa',[
		'uses' => 'LinguagemProgramaController@cadastraLinguagemPrograma',
		'as' => 'cria-linguagem-programa',
		'middleware' => 'auth'
	]);

	//editar linguagem de programa
	Route::post('/update-linguagem-programa/{id}',[
		'uses' => 'LinguagemProgramaController@editLinguagemPrograma',
		'as' => 'update-linguagem-programa',
		'middleware' => 'auth'
	]);

	//deletar linguagem de programa
	Route::get('/delete-linguagem-programa/{id}',[
		'uses' => 'LinguagemProgramaController@deleteLinguagemPrograma',
		'as' => 'delete-linguagem-programa',
		'middleware' => 'auth'
	]);


	//editar linguagem de programa view
	Route::get('/editar-linguagem-programa/{id}',[
		'uses' => 'LinguagemProgramaController@editLinguagemProgramaView',
		'as' => 'editar-linguagem-programa',
		'middleware' => 'auth'
	]);
});

//Genero Linguagem Programa
Route::group(['prefix' => 'genero-linguagem'], function () {

	//Redirecionar para a listagem de generos de linguagem
	Route::get('/listar-generos-linguagem',[
		'uses' => 'GeneroLinguagemController@listarGeneroLinguagem',
		'as' => 'listar-generos-linguagem',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//mostra view de cadastro de genero linguagem
	Route::get('/cadastra-genero-linguagem',[
		'uses' => 'GeneroLinguagemController@cadastraGeneroLinguagemView',
		'as' => 'cadastra-genero-linguagem',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar genero linguagem
	Route::post('/cria-genero-linguagem',[
		'uses' => 'GeneroLinguagemController@cadastraGeneroLinguagem',
		'as' => 'cria-genero-linguagem',
		'middleware' => 'auth'
	]);

	//editar genero linguagem
	Route::post('/update-genero-linguagem/{id}',[
		'uses' => 'GeneroLinguagemController@editGeneroLinguagem',
		'as' => 'update-genero-linguagem',
		'middleware' => 'auth'
	]);

	//deletar genero linguagem
	Route::get('/delete-genero-linguagem/{id}',[
		'uses' => 'GeneroLinguagemController@deleteGeneroLinguagem',
		'as' => 'delete-genero-linguagem',
		'middleware' => 'auth'
	]);


	//editar genero linguagem view
	Route::get('/editar-genero-linguagem/{id}',[
		'uses' => 'GeneroLinguagemController@editGeneroLinguagemView',
		'as' => 'editar-genero-linguagem',
		'middleware' => 'auth'
	]);
});

//TIPO DE EVENTO
Route::group(['prefix' => 'tipo-evento'], function () {

	//Redirecionar para a listagem de tipos de evento
	Route::get('/listar-tipo-eventos',[
		'uses' => 'TipoEventoController@listarTipoEvento',
		'as' => 'listar-tipo-eventos',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//mostra view de cadastro de tipo de evento
	Route::get('/cadastra-tipo-evento',[
		'uses' => 'TipoEventoController@cadastraTipoEventoView',
		'as' => 'cadastra-tipo-evento',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar tipo de evento
	Route::post('/cria-tipo-evento',[
		'uses' => 'TipoEventoController@cadastraTipoEvento',
		'as' => 'cria-tipo-evento',
		'middleware' => 'auth'
	]);

	//editar tipo de evento
	Route::post('/update-tipo-evento/{id}',[
		'uses' => 'TipoEventoController@editTipoEvento',
		'as' => 'update-tipo-evento',
		'middleware' => 'auth'
	]);

	//deletar tipo de evento
	Route::get('/delete-tipo-evento/{id}',[
		'uses' => 'TipoEventoController@deleteTipoEvento',
		'as' => 'delete-tipo-evento',
		'middleware' => 'auth'
	]);


	//editar tipo de evento view
	Route::get('/editar-tipo-evento/{id}',[
		'uses' => 'TipoEventoController@editTipoEventoView',
		'as' => 'editar-tipo-evento',
		'middleware' => 'auth'
	]);
});

//ESPECIE DA ACAO
Route::group(['prefix' => 'especie-acao'], function () {

	//Redirecionar para a listagem de espécies de ação
	Route::get('/listar-especie-acao',[
		'uses' => 'EspecieAcaoController@listarEspecieAcao',
		'as' => 'listar-especie-acao',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//mostra view de cadastro de espécie de ação
	Route::get('/cadastra-especie-acao',[
		'uses' => 'EspecieAcaoController@cadastraEspecieAcaoView',
		'as' => 'cadastra-especie-acao',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar espécie de ação
	Route::post('/cria-especie-acao',[
		'uses' => 'EspecieAcaoController@cadastraEspecieAcao',
		'as' => 'cria-especie-acao',
		'middleware' => 'auth'
	]);

	//editar espécie de ação
	Route::post('/update-especie-acao/{id}',[
		'uses' => 'EspecieAcaoController@editEspecieAcao',
		'as' => 'update-especie-acao',
		'middleware' => 'auth'
	]);

	//deletar espécie de ação
	Route::get('/delete-especie-acao/{id}',[
		'uses' => 'EspecieAcaoController@deleteEspecieAcao',
		'as' => 'delete-especie-acao',
		'middleware' => 'auth'
	]);


	//editar espécie de ação view
	Route::get('/editar-especie-acao/{id}',[
		'uses' => 'EspecieAcaoController@editEspecieAcaoView',
		'as' => 'editar-especie-acao',
		'middleware' => 'auth'
	]);
});

//LINGUAGEM DA ACAO
Route::group(['prefix' => 'linguagem-acao'], function () {

	//Redirecionar para a listagem de linguagens de ação
	Route::get('/listar-linguagem-acao',[
		'uses' => 'LinguagemAcaoController@listarLinguagemAcao',
		'as' => 'listar-linguagem-acao',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//mostra view de cadastro de linguagem de ação
	Route::get('/cadastra-linguagem-acao',[
		'uses' => 'LinguagemAcaoController@cadastraLinguagemAcaoView',
		'as' => 'cadastra-linguagem-acao',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar linguagem de ação
	Route::post('/cria-linguagem-acao',[
		'uses' => 'LinguagemAcaoController@cadastraLinguagemAcao',
		'as' => 'cria-linguagem-acao',
		'middleware' => 'auth'
	]);

	//editar linguagem de ação
	Route::post('/update-linguagem-acao/{id}',[
		'uses' => 'LinguagemAcaoController@editLinguagemAcao',
		'as' => 'update-linguagem-acao',
		'middleware' => 'auth'
	]);

	//deletar linguagem de ação
	Route::get('/delete-linguagem-acao/{id}',[
		'uses' => 'LinguagemAcaoController@deleteLinguagemAcao',
		'as' => 'delete-linguagem-acao',
		'middleware' => 'auth'
	]);


	//editar linguagem de ação view
	Route::get('/editar-linguagem-acao/{id}',[
		'uses' => 'LinguagemAcaoController@editLinguagemAcaoView',
		'as' => 'editar-linguagem-acao',
		'middleware' => 'auth'
	]);
});

//FUNCAO DA ACAO
Route::group(['prefix' => 'funcao-acao'], function () {

	//Redirecionar para a listagem de funcao de ação
	Route::get('/listar-funcao-acao',[
		'uses' => 'FuncaoAcaoController@listarFuncaoAcao',
		'as' => 'listar-funcao-acao',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//mostra view de cadastro de funcao de ação
	Route::get('/cadastra-funcao-acao',[
		'uses' => 'FuncaoAcaoController@cadastraFuncaoAcaoView',
		'as' => 'cadastra-funcao-acao',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar funcao de ação
	Route::post('/cria-funcao-acao',[
		'uses' => 'FuncaoAcaoController@cadastraFuncaoAcao',
		'as' => 'cria-funcao-acao',
		'middleware' => 'auth'
	]);

	//editar funcao de ação
	Route::post('/update-funcao-acao/{id}',[
		'uses' => 'FuncaoAcaoController@editFuncaoAcao',
		'as' => 'update-funcao-acao',
		'middleware' => 'auth'
	]);

	//deletar funcao de ação
	Route::get('/delete-funcao-acao/{id}',[
		'uses' => 'FuncaoAcaoController@deleteFuncaoAcao',
		'as' => 'delete-funcao-acao',
		'middleware' => 'auth'
	]);


	//editar funcao de ação view
	Route::get('/editar-funcao-acao/{id}',[
		'uses' => 'FuncaoAcaoController@editFuncaoAcaoView',
		'as' => 'editar-funcao-acao',
		'middleware' => 'auth'
	]);
});

//AÇÃO
Route::group(['prefix' => 'acao'], function () {

	//Redirecionar para a listagem de ações
	Route::get('/listar-acoes',[
		'uses' => 'AcaoController@listarAcao',
		'as' => 'listar-acoes',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//mostra view de cadastro de ação
	Route::get('/cadastra-acao',[
		'uses' => 'AcaoController@cadastraAcaoView',
		'as' => 'cadastra-acao',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar ação
	Route::post('/cria-acao',[
		'uses' => 'AcaoController@cadastraAcao',
		'as' => 'cria-acao',
		'middleware' => 'auth'
	]);

	//editar ação
	Route::post('/update-acao/{id}',[
		'uses' => 'AcaoController@editAcao',
		'as' => 'update-acao',
		'middleware' => 'auth'
	]);

	//deletar ação
	Route::get('/delete-acao/{id}',[
		'uses' => 'AcaoController@deleteAcao',
		'as' => 'delete-acao',
		'middleware' => 'auth'
	]);


	//editar ação view
	Route::get('/editar-acao/{id}',[
		'uses' => 'AcaoController@editAcaoView',
		'as' => 'editar-acao',
		'middleware' => 'auth'
	]);

	//chamar programas via ajax a partir do id do plano
	Route::post('/chama-programas-acao',[
		'uses' => 'AcaoController@ajaxAcaoFetchProgramasByPlano',
		'as' => 'chama-programas-acao',
		'middleware' => 'auth'
	]);
});


//REGRAS  SILAS
Route::group(['prefix' => 'regras'], function () {

	// mostrar view de lista de regra
	Route::get('/listar-regras',[
		'uses' => 'regrasController@listaRegra',
		'as' => 'listar-regras',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	// mostrar view de cadastro de regra
	Route::get('/listar-regras/cadastra-regra',[
		'uses' => 'regrasController@cadastraRegraView',
		'as' => 'cadastra-regra',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar regras 
	Route::post('/cadastra-regra',[
		'uses' => 'regrasController@cadastraRegra',
		'as' => 'cria-regra',
		'middleware' => 'auth'

	//deletar regras
		]);
	Route::get('/delete-regra/{id}',[
		'uses' => 'regrasController@deleteRegra',
		'as' => 'delete-regra',
		'middleware' => 'auth'
		]);

	//editar regras
	Route::post('/update-regra/{id}',[
		'uses' => 'regrasController@editRegra',
		'as' => 'update-regra',
		'middleware' => 'auth'
	]);

	//editar regra view
	Route::get('/editar-regra/{id}',[
		'uses' => 'regrasController@editRegraView',
		'as' => 'editar-regra',
		'middleware' => 'auth'
	]);

});

//INDICADOR
Route::group(['prefix' => 'indicador'], function () {

	//Redirecionar para a listagem de indicadores
	Route::get('/listar-indicadores',[
		'uses' => 'IndicadorController@listarIndicador',
		'as' => 'listar-indicadores',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//mostra view de cadastro de indicador
	Route::get('/cadastra-indicador',[
		'uses' => 'IndicadorController@cadastraIndicadorView',
		'as' => 'cadastra-indicador',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar indicador
	Route::post('/cria-indicador',[
		'uses' => 'IndicadorController@cadastraIndicador',
		'as' => 'cria-indicador',
		'middleware' => 'auth'
	]);

	//editar indicador
	Route::post('/update-indicador/{id}',[
		'uses' => 'IndicadorController@editIndicador',
		'as' => 'update-indicador',
		'middleware' => 'auth'
	]);

	//deletar indicador
	Route::get('/delete-indicador/{id}',[
		'uses' => 'IndicadorController@deleteIndicador',
		'as' => 'delete-indicador',
		'middleware' => 'auth'
	]);


	//editar indicador view
	Route::get('/editar-indicador/{id}',[
		'uses' => 'IndicadorController@editIndicadorView',
		'as' => 'editar-indicador',
		'middleware' => 'auth'
	]);



	//chamar acoes via ajax a partir do id do plano cadastro
	Route::post('/chama-acoes-indicador',[
		'uses' => 'IndicadorController@ajaxIndicadorFetchAcoesByPlano',
		'as' => 'chama-acoes-indicador',
		'middleware' => 'auth'
	]);
});

//PROGRAMA
Route::group(['prefix' => 'programa'], function () {

	//Redirecionar para a listagem de Programas
	Route::get('/listar-programas',[
		'uses' => 'ProgramaController@listarPrograma',
		'as' => 'listar-programas',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//mostra view de cadastro de programa
	Route::get('/cadastra-programa',[
		'uses' => 'ProgramaController@cadastraProgramaView',
		'as' => 'cadastra-programa',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar programa
	Route::post('/cria-programa',[
		'uses' => 'ProgramaController@cadastraPrograma',
		'as' => 'cria-programa',
		'middleware' => 'auth'
	]);

	//editar programa
	Route::post('/update-programa/{id}',[
		'uses' => 'ProgramaController@editPrograma',
		'as' => 'update-programa',
		'middleware' => 'auth'
	]);


	//deletar programa
	Route::get('/delete-programa/{id}',[
		'uses' => 'ProgramaController@deletePrograma',
		'as' => 'delete-programa',
		'middleware' => 'auth'
	]);


	//editar programa view
	Route::get('/editar-programa/{id}',[
		'uses' => 'ProgramaController@editProgramaView',
		'as' => 'editar-programa',
		'middleware' => 'auth'
	]);

	//chamar acoes via ajax a partir do id do plano
	/*Route::post('/chama-acoes-programa',[
		'uses' => 'ProgramaController@ajaxProgramaFetchAcoesByPlano',
		'as' => 'chama-acoes-programa',
		'middleware' => 'auth'
	]);*/
});

//ATIVIDADES
Route::group(['prefix' => 'atividade'], function () {

	//chamar programas via ajax a partir do id do plano
	Route::post('/chama-programas-atividade',[
		'uses' => 'AtividadeController@ajaxAtividadeFetchProgramasByPlano',
		'as' => 'chama-programas-atividade',
		'middleware' => 'auth'
	]);

	//Redirecionar para a listagem de Atividades
	Route::get('/listar-atividades',[
		'uses' => 'AtividadeController@listarAtividade',
		'as' => 'listar-atividades',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//Redirecionar para a lista completa de Atividades
	Route::get('/listar-relatorio-programas-atividade',[
		'uses' => 'AtividadeController@listarRelatorioProgramasAtividade',
		'as' => 'listar-relatorio-programas-atividades',
		'middleware' => 'auth'
	]);
	// Extração de relatório de atividades
	Route::get('/extrair-relatorio-programas-atividade',[
		'uses' => 'AtividadeController@extrairRelatorioProgramaAtividade',
		'as' => 'extrair-relatorio-programas-atividade',
		'middleware' => 'auth'
		]);

	//Redirecionar para a listagem de Planos para atividades
	Route::get('/listar-plano-atividades',[
		'uses' => 'AtividadeController@listarPlanoAtividade',
		'as' => 'listar-plano-atividades',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//Redirecionar para a listagem de Planos para atividades
	Route::get('/listar-programas-atividades/{id}',[
		'uses' => 'AtividadeController@listarProgramaAtividade',
		'as' => 'listar-programas-atividades',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	Route::get('/listar-atividades-por-programa/{id}',[
		'uses' => 'AtividadeController@listarAtividadesPorPrograma',
		'as' => 'listar-atividades-por-programa',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//mostra view de cadastro de atividade por programa
	/*Route::get('/cadastra-atividade-por-programa/{id}',[
		'uses' => 'AtividadeController@cadastrarAtividadesPorProgramaView',
		'as' => 'cadastra-atividade-por-programa',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);*/

	/*//cadastrar atividade
	Route::post('/cria-atividade-por-programa',[
		'uses' => 'AtividadeController@cadastrarAtividadesPorPrograma',
		'as' => 'cria-atividade-por-programa',
		'middleware' => 'auth'
	]);*/

	//mostra view de cadastro de atividade
	Route::get('/cadastra-atividade/{id?}',[
		'uses' => 'AtividadeController@cadastraAtividadeView',
		'as' => 'cadastra-atividade',
		'middleware' => 'auth'
		//'middleware' => 'auth' //usado parcadastra informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//cadastrar atividade
	Route::post('/cria-atividade',[
		'uses' => 'AtividadeController@cadastraAtividade',
		'as' => 'cria-atividade',
		'middleware' => 'auth'
	]);

	//editar atividade
	Route::post('/update-atividade/{id}',[
		'uses' => 'AtividadeController@editAtividade',
		'as' => 'update-atividade',
		'middleware' => 'auth'
	]);

	//deletar atividade
	Route::get('/delete-atividade/{id}',[
		'uses' => 'AtividadeController@deleteAtividade',
		'as' => 'delete-atividade',
		'middleware' => 'auth'
	]);


	//editar atividade view
	Route::get('/editar-atividade/{id}',[
		'uses' => 'AtividadeController@editAtividadeView',
		'as' => 'editar-atividade',
		'middleware' => 'auth'
	]);

	//chamar acoes via ajax a partir do id do plano
	/*Route::post('/chama-acoes-programa',[
		'uses' => 'ProgramaController@ajaxProgramaFetchAcoesByPlano',
		'as' => 'chama-acoes-programa',
		'middleware' => 'auth'
	]);*/
});


//Relatórios
Route::group(['prefix' => 'relatorios'], function () {

	//Redirecionar para a relatorio mensalmake:
	Route::get('/relatorio-mensal/{id}',[
		'uses' => 'RelatorioController@RelatorioMensal',
		'as' => 'relatorio-mensal',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//Redirecionar para a relatorio mensalmake:
	Route::get('/planos-relatorio-mensal',[
		'uses' => 'RelatorioController@PlanosRelatorioMensal',
		'as' => 'planos-relatorio-mensal',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);

	//Redirecionar para a relatorio trimestral:
	Route::get('/relatorio-trimestral/{id}',[
		'uses' => 'RelatorioController@RelatorioTrimestral',
		'as' => 'relatorio-trimestral',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);


	//Redirecionar para a relatorio mensalmake:
	Route::get('/planos-relatorio-trimestral',[
		'uses' => 'RelatorioController@PlanosRelatorioTrimestral',
		'as' => 'planos-relatorio-trimestral',
		'middleware' => 'auth'
		//usado para informar que esta página passa por um processo de autenticacao da middleware de autenticacao entre request antes de acessar a página dashboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
	]);



	// criar lista de relatorios
	Route::get('/lista-relatorio-plano/{id}',[
		'uses' => 'RelatorioController@listarRelatorio',
		'as' => 'lista-relatorio-plano',
		'middleware' => 'auth'
		// usado para informar que a página passa por um processo de autenticao da middlware de autenticacao entre request antes de acessar a página dasboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)

	]);

	// Extração de relatório trimestral

	Route::get('/extrair-relatorio-trimestral/{id}',[
		'uses' => 'RelatorioController@extraiRelatorioTrimestral',
		'as' => 'extrair-relatorio-trimestral',
		'middleware' => 'auth'
		// usado para informar que a página passa por um processo de autenticao da middlware de autenticacao entre request antes de acessar a página dasboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)
		]);

	//Redirecionar para lista de planos de relatorios

	Route::get('/lista-relatorio',[
		'uses' => 'RelatorioController@PlanoslistarRelatorio',
		'as' => 'lista-relatorio',
		'middleware' => 'auth'
		// usado para informar que a págia passa por um processo de autenticao da middlware de autenticacao entre request antes de acessar a página dasboard. Aonde está o auth(App\Http\Middleware\Authenticate.php)

	]);

});