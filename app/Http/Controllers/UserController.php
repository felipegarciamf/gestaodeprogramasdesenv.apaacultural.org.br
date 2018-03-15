<?php  

namespace App\Http\Controllers;


use App\User;
use App\Plano;
use App\Programa;
use App\PermissoesUsuario;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;


class UserController extends Controller
{
	/*
	 *@var Usuario
	 */
	private $usuario;
	private $plano;
	private $programa;
	private $permissoes_usuario;

	public function __construct(User $usuario,Plano $plano,Programa $programa,PermissoesUsuario $permissoes_usuario)
	{
		$this->usuario = $usuario;
		$this->plano = $plano;
		$this->programa = $programa;
		$this->permissoes_usuario = $permissoes_usuario;
	}

	public function dashboardView()
	{
		return view('dashboard');
	}

	public function cadastraUsuarioView()
	{
		return view('usuario.cadastra-usuario');
	}

	public function listarUsuarios()
	{
		if(Auth::user()->perfil == 2)
		{
			//$usuarios = User::orderBy('created_at','desc')->get();
			$usuarios = $this->usuario->orderBy('created_at', 'DESC')->get();
			return view('usuario.lista-usuarios')->with(['usuarios' => $usuarios]);
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	public function autentica(Request $request)
	{
		//por padrao o metodo attempt usado o campo password como autenticador de senhas, mudei o nome do campo no banco para senha com o metodo getAuthPassword, porem  o metodo exige que o parametro senha password
		if(Auth::attempt(["email" => $request["email"], "password" => $request["senha"]]))
		{
			return redirect()->route('dashboard');
		}
		return redirect()->route('login')->with(["error_logado" => true]);
	}

	public function cadastraUsuario(Request $request)
	{
		if(Auth::user()->perfil == 2)
		{
			$email = $request['email'];
			$nome = $request['nome'];
			$sobrenome = $request['sobrenome'];
			$senha = bcrypt($request['senha']);
			$perfil = intval($request["perfil"]);
			$criadoPor = Auth::user()->id;
			$usuario = $this->usuario;

			$usuario->email = $email;
			$usuario->nome = $nome;
			$usuario->sobrenome = $sobrenome;
			$usuario->senha = $senha;
			$usuario->perfil = $perfil;
			$usuario->created_by = $criadoPor;

			$usuario->save();

			//Auth::login($user);

			return redirect()->route('listar-usuarios');
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	public function editUsuarioView($id)
	{
		if(Auth::user()->perfil == 2)
		{
			$usuario = $this->usuario->find($id);

			return view('usuario.editar-usuarios',['usuario' => $usuario]);
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	public function editUsuario(Request $request,$id)
	{

		if(Auth::user()->perfil == 2)
		{
			$usuario = $this->usuario->find($id);

			$email = $request['email'];
			$nome = $request['nome'];
			$sobrenome = $request['sobrenome'];
			$senha = "";
			if(!empty($request['senha']))
			{
				$senha = bcrypt($request['senha']);
			}
			$alteradoPor = Auth::user()->id;

			$perfil = intval($request["perfil"]);

			$usuario->email = $email;
			$usuario->nome = $nome;
			$usuario->sobrenome = $sobrenome;
			if(!empty($senha))
			{
				$usuario->senha = $senha;
			}
			$usuario->perfil = $perfil;
			$usuario->changed_by = $alteradoPor;

			$usuario->update();

			return redirect()->route('listar-usuarios');
		}
		else
		{
			return redirect()->route('dashboard');
		}		
	}

	public function deleteUsuario($id)
	{
		if(Auth::user()->perfil == 2)
		{
			$usuario = $this->usuario->find($id);
			$excluidoPor = Auth::user()->id;
			$usuario->deleted_by = $excluidoPor;
			$usuario->update();
			$usuario->delete();

			return redirect()->route('listar-usuarios');
		}
		else
		{
			return redirect()->route('dashboard');
		}
		
	}

	public function editProfileView($id)
	{
			$usuario = $this->usuario->find($id);
			$autorizado = 0;
			if(Auth::user()->id == $id)
			{
				$autorizado = 1;
			}
			else
			{
				$autorizado = 0;
			}

			if($autorizado == 0)
			{
				$usuario = null;
			}

			return view('usuario.perfil-usuario',['usuario' => $usuario]);
		
	}

	public function editProfile(Request $request,$id)
	{

			$usuario = $this->usuario->find($id);
			$alteradoPor = Auth::user()->id;
			if(Auth::user()->id == $id)	
			{
				$usuario->email = $request["email"];
				$usuario->nome = $request["nome"];
				$usuario->sobrenome = $request["sobrenome"];
				$senha = "";
				if(!empty($request['senha']))
				{
					$senha = bcrypt($request['senha']);
				}
				if(!empty($senha))
				{
					$usuario->senha = $senha;
				}
				$usuario->changed_by = $alteradoPor;

				$usuario->update();
			}

			return redirect()->route('dashboard');
		
	}

	public function logout()
	{
		Auth::logout();
		return redirect()->route('login');
	}

	//PERMISSOES DE USUÁRIO
	public function listarUsuariosPermissoesView()
	{
		if(Auth::user()->perfil == 2)
		{
			$usuarios = $this->usuario->where('perfil',1)->orderBy('id', 'ASC')->get();
			
			return view('usuario.listar-usuarios-permissoes')->with(['usuarios' => $usuarios]);
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	public function listarUsuariosPorPermissoesView($id)
	{

		if(Auth::user()->perfil == 2)
		{
			$usuario = $this->usuario->find($id);
			$permissoes = $this->permissoes_usuario->where('user_id',$usuario->id)->orderBy('created_at','DESC')->get();

			if($usuario->perfil != 1)
			{
				$usuario = null;
				$permissoes = null;
			}
			return view('usuario.listar-permissoes-por-usuario')->with(['usuario' => $usuario,'permissoes' => $permissoes]);
		}
		else
		{
			return redirect()->route('dashboard');
		}
		
	}

	public function cadastraUsuarioPermissoesView($id)
	{
		if(Auth::user()->perfil == 2)
		{
			$usuario = $this->usuario->find($id);
			$planos = $this->plano->orderBy('created_at','DESC')->get();

			return view('usuario.cadastra-permissoes-usuario',['planos' => $planos,'usuario' => $usuario]);
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	public function ajaxPermissaoFetchProgramasByPlano(Request $request)
	{
		if(Auth::user()->perfil == 2)
		{
			//->whereNotIn('programa_id',)
			//$permissoes = $this->permissoes_usuario->where('user_id',$request->input('plano_id'))->get();
			$permissoes = $this->permissoes_usuario->orderBy('created_at','DESC')->where('user_id',$request->input('user_id'))->get();

			if($permissoes->count())
			{
				$programas_id = array();
				foreach ($permissoes as $permissao) 
				{
					array_push($programas_id, $permissao->programa_id);
				}

				$programas = $this->programa->where('plano_id',$request->input('plano_id'))->whereNotIn('id',$programas_id)->get();

				if($request->ajax()){
			        return response()->json([
			            'programas' => $programas
			        ]);
			    }
			}
			else
			{
				$programas = $this->programa->where('plano_id',$request->input('plano_id'))->get();

				if($request->ajax()){
			        return response()->json([
			            'programas' => $programas
			        ]);
			    }
			}
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	public function cadastraUsuarioPermissao(Request $request)
	{
		if(Auth::user()->perfil == 2)
		{
			$arrayPermissoes = array();
			for($i = 0; $i < sizeof($request["programas"]); $i++)
			{
				DB::table('permissoes_usuarios')->insert(
				    ['user_id' => intval($request["usuario"]), 'plano_id' => intval($request["plano"]), 'programa_id' => intval($request["programas"][$i]),'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("Y-m-d H:i:s")]
				);
			}

			return redirect()->route('listar-permissoes-por-usuario',['id' => $request["usuario"]]);
		}
		else
		{
			return redirect()->route('dashboard');
		}
		
	}

	public function deleteUsuarioPermissao($id)
	{
		if(Auth::user()->perfil == 2)
		{
			$permissao = $this->permissoes_usuario->find($id);
			$usuarioPermissaoDelete = $permissao->user->id;
			$excluidoPor = Auth::user()->id;
			$permissao->deleted_by = $excluidoPor;
			$permissao->update();
			$permissao->delete();

			return redirect()->route('listar-permissoes-por-usuario',['id' => $usuarioPermissaoDelete]);
		}
		else
		{
			return redirect()->route('dashboard');
		}
		
	}

}

?>