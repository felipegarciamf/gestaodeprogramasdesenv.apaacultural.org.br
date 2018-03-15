<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\JsonResponse;

use App\Programa;
use App\Plano;
use App\Indicador;
use App\Tipagem;

class ProgramaController extends Controller
{
	/*
	 *@var plano, @var cgs, @var objetos, @var oss, @var tipoobjetos, @var uges
	 */
	private $programa;
	private $plano;
	private $indicador;
	private $tipagem;


	public function __construct(Programa $programa, Plano $plano, Indicador $indicador, Tipagem $tipagem)
	{
		$this->programa = $programa;
		$this->plano = $plano;
		$this->indicador = $indicador;
		$this->tipagem = $tipagem;
	}

	public function cadastraProgramaView()
	{
		if(Auth::user()->perfil == 2)
		{
			$tipagens = $this->tipagem->orderBy('created_at', 'DESC')->get();
			$planos = $this->plano->orderBy('created_at','DESC')->get();
			return view('programa.cadastra-programa',['planos' => $planos,'tipagens' => $tipagens]);
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	/*public function ajaxProgramaFetchAcoesByPlano(Request $request)
	{
		$acoes = $this->acao->where('plano_id',$request->input('plano_id'))->get();

	    if($request->ajax()){
	        return response()->json([
	            'acoes' => $acoes
	        ]);
	    }
	}*/

	public function listarPrograma(Request $request)
	{
		if(Auth::user()->perfil == 2)
		{
			$programas = $this->programa->name($request->get('nome'))->orderBy('plano_id', 'DESC')->get();
			
			return view('programa.listar-programas',['programas' => $programas]);
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	public function cadastraPrograma(Request $request)
	{
		if(Auth::user()->perfil == 2)
		{
		    $programa = $this->programa;
		   

		    $programa->nome = $request["nome"];
			$programa->plano_id = intval($request["plano"]);
			$programa->tipagem_id = intval($request["tipagem"]);
			$programa->descricao = $request["descricao"];
		    $programa->created_by = Auth::user()->id;

			$programa->save();

			//Auth::login($user);

			return redirect()->route('listar-programas');
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	public function editProgramaView($id)
	{
		if(Auth::user()->perfil == 2)
		{
			$programa = $this->programa->find($id);
			$planos = $this->plano->orderBy('created_at', 'DESC')->get();
			$tipagens = $this->tipagem->orderBy('created_at', 'DESC')->get();
			return view('programa.editar-programa',['planos' => $planos,'tipagens' => $tipagens,'programa' => $programa]);
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	public function editPrograma(Request $request,$id)
	{
		if(Auth::user()->perfil == 2)
		{
			$programa = $this->programa->find($id);

		    $programa->nome = $request["nome"];
		    $programa->plano_id = intval($request["plano"]);
		    $programa->tipagem_id = intval($request["tipagem"]);
		    $programa->descricao = $request["descricao"];

		    $programa->changed_by = Auth::user()->id;

			$programa->update();

			return redirect()->route('listar-programas');
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	public function deletePrograma($id)
	{
		if(Auth::user()->perfil == 2)
		{
			$programa = $this->programa->find($id);
		    $programa->deleted_by = Auth::user()->id;
		    $programa->update();
			$programa->delete();

			return redirect()->route('listar-programas');
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}
}
