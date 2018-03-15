<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Auth;

use App\GeneroLinguagem;
use App\LinguagemPrograma;

class GeneroLinguagemController extends Controller
{
	private $genero_linguagem;
	private $linguagem;

	public function __construct(GeneroLinguagem $genero_linguagem, LinguagemPrograma $linguagem)
	{
		$this->genero_linguagem = $genero_linguagem;
        $this->linguagem = $linguagem;
	}

	public function cadastraGeneroLinguagemView()
	{
		if(Auth::user()->perfil == 2)
		{
			$linguagens = $this->linguagem->orderBy('created_at', 'DESC')->get();
			return view('programa.genero-linguagem.cadastra-genero-linguagem',['linguagens' => $linguagens]);
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	public function listarGeneroLinguagem()
	{
		if(Auth::user()->perfil == 2)
		{
			$generos_linguagem = $this->genero_linguagem->orderBy('created_at', 'DESC')->get();
			
			return view('programa.genero-linguagem.listar-generos-linguagem',['generos_linguagem' => $generos_linguagem]);
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	public function cadastraGeneroLinguagem(Request $request)
	{
		if(Auth::user()->perfil == 2)
		{
		    $genero_linguagem = $this->genero_linguagem;

		    $genero_linguagem->nome = $request["nome"];
		    $genero_linguagem->linguagem_programa_id = intval($request["linguagem"]);

		    $genero_linguagem->created_by = Auth::user()->id;

			$genero_linguagem->save();

			//Auth::login($user);

			return redirect()->route('listar-generos-linguagem');
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	public function editGeneroLinguagemView($id)
	{
		if(Auth::user()->perfil == 2)
		{
			$genero_linguagem = $this->genero_linguagem->find($id);

			$linguagens_programa = $this->linguagem->orderBy('created_at', 'DESC')->get();
			
			return view('programa.genero-linguagem.editar-genero-linguagem',['genero_linguagem' => $genero_linguagem,'linguagens_programa' => $linguagens_programa]);
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	public function editGeneroLinguagem(Request $request,$id)
	{
		if(Auth::user()->perfil == 2)
		{
			$genero_linguagem = $this->genero_linguagem->find($id);

		    $genero_linguagem->nome = $request["nome"];
		    $genero_linguagem->linguagem_programa_id = intval($request["linguagem"]);

		    $genero_linguagem->changed_by = Auth::user()->id;

			$genero_linguagem->update();

			return redirect()->route('listar-generos-linguagem');
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}

	public function deleteGeneroLinguagem($id)
	{
		if(Auth::user()->perfil == 2)
		{
			$genero_linguagem = $this->genero_linguagem->find($id);
		    $genero_linguagem->deleted_by = Auth::user()->id;
		    $genero_linguagem->update();
			$genero_linguagem->delete();

			return redirect()->route('listar-generos-linguagem');
		}
		else
		{
			return redirect()->route('dashboard');
		}
	}
}
