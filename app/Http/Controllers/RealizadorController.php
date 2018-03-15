<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Auth;

use App\Realizador;
use App\Municipio;

class RealizadorController extends Controller
{
	/*
	 *@var plano, @var cgs, @var objetos, @var oss, @var tipoobjetos, @var uges
	 */
	private $realizador;
	private $municipio;

	public function __construct(Realizador $realizador, Municipio $municipio)
	{
		$this->realizador = $realizador;
		$this->municipio = $municipio;
	}

	public function cadastraRealizadorView()
	{
		if(Auth::user()->perfil == 2)
		{
			$realizadores = $this->realizador->orderBy('created_at', 'DESC')->get();
			$municipios = $this->municipio->orderBy('created_at', 'DESC')->get();
			return view('programa.realizador.cadastra-realizador',['realizadores' => $realizadores,'municipios' => $municipios]);
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	public function listarRealizador()
	{
		if(Auth::user()->perfil == 2)
		{
			//$usuarios = User::orderBy('created_at','desc')->get();
			$realizadores = $this->realizador->orderBy('created_at', 'DESC')->get();
			
			return view('programa.realizador.listar-realizadores',['realizadores' => $realizadores]);
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	public function cadastraRealizador(Request $request)
	{
		if(Auth::user()->perfil == 2)
		{
		    $realizadores = $this->realizador;

			$realizadores->nome = $request["nome"];
			$realizadores->municipio_id = intval($request["municipio"]);
			$realizadores->num_total_pessoas = intval($request["num_total_pessoas"]);
			$realizadores->num_apresentacoes = intval($request["num_apresentacoes"]);

		    $realizadores->created_by = Auth::user()->id;

			$realizadores->save();

			//Auth::login($user);

			return redirect()->route('listar-realizadores');
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	public function editRealizadorView($id)
	{
		if(Auth::user()->perfil == 2)
		{
			$realizador = $this->realizador->find($id);

			$municipios = $this->municipio->orderBy('created_at', 'DESC')->get();
			
			return view('programa.realizador.editar-realizador',['realizador' => $realizador,'municipios' => $municipios]);
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	public function editRealizador(Request $request,$id)
	{
		if(Auth::user()->perfil == 2)
		{
			$realizador = $this->realizador->find($id);

			$realizador->nome = $request["nome"];
			$realizador->municipio_id = $request["municipio"];
			$realizador->num_total_pessoas = $request["num_total_pessoas"];
			$realizador->num_apresentacoes = $request["num_apresentacoes"];
			
		    $realizador->changed_by = Auth::user()->id;

			$realizador->update();

			return redirect()->route('listar-realizadores');
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	public function deleteRealizador($id)
	{
		if(Auth::user()->perfil == 2)
		{
			$realizador = $this->realizador->find($id);
		    $realizador->deleted_by = Auth::user()->id;
		    $realizador->update();
			$realizador->delete();

			return redirect()->route('listar-realizadores');
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}
}
