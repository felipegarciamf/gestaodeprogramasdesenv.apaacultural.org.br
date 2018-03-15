@extends('layouts.master')

@section('title')
    Listagem de Gêneros de Linguagem
@endsection

@section('content')
	<div class="row">
		<div class="col-md-8 col-sm-8">
			<div class="table table-responsive">
				<table class="table table-striped table-bordered"> 
					<thead> 
						<tr> 
							<th>Nome</th>
							<th>Linguagem</th>
						</tr> 
					</thead> 
					<tbody> 
						@foreach ($generos_linguagem as $genero_linguagem)
						<tr> 
							<th>{{ $genero_linguagem->nome }}</th> 
							<th>{{ $genero_linguagem->linguagem_programa->nome }}</th> 
							<th>{{ $genero_linguagem->distancia }}</th> 
							<td>
								<ul class="list-inline">
									<li>
										<a href="{{ route('editar-genero-linguagem',["id" => $genero_linguagem->id]) }}" class="btn btn-warning">Editar</a>
										<a href="{{ route('delete-genero-linguagem',["id" => $genero_linguagem->id]) }}" class="btn btn-danger">Excluir</a>
									</li>
								</ul>
							</td>
						</tr>
						@endforeach
					</tbody> 
				</table>
			</div>
			<a href="{{ route('cadastra-genero-linguagem') }}" class="btn btn-primary">Cadastrar novo gênero</a>
		</div>
	</div>
@endsection