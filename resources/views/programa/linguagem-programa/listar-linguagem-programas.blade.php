@extends('layouts.master')

@section('title')
    Listagem de Linguagem
@endsection

@section('content')
	<div class="row">
		<div class="col-md-8 col-sm-8">
			<div class="table table-responsive">
				<table class="table table-striped table-bordered"> 
					<thead> 
						<tr> 
							<th>Nome</th>
							<th>Ações</th>
						</tr> 
					</thead> 
					<tbody> 
						@foreach ($linguagem_programas as $linguagem_programa)
						<tr> 
							<th>{{ $linguagem_programa->nome }}</th> 
							<td>
								<ul class="list-inline">
									<li>
										<a href="{{ route('editar-linguagem-programa',["id" => $linguagem_programa->id]) }}" class="btn btn-warning">Editar</a>
										<a href="{{ route('delete-linguagem-programa',["id" => $linguagem_programa->id]) }}" class="btn btn-danger">Excluir</a>
									</li>
								</ul>
							</td>
						</tr>
						@endforeach
					</tbody> 
				</table>
			</div>
			<a href="{{ route('cadastra-linguagem-programa') }}" class="btn btn-primary">Cadastrar nova linguagem</a>
		</div>
	</div>
@endsection