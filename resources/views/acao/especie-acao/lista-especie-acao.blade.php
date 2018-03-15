@extends('layouts.master')

@section('title')
    Listagem de Espécie de Ação
@endsection

@section('content')
	<div class="row">
		<div class="col-md-8 col-sm-8">
			<div class="table table-responsive">
				<table class="table table-striped table-bordered"> 
					<thead> 
						<tr> 
							<th>Nome</th> 
						</tr> 
					</thead> 
					<tbody> 
						@foreach ($especie_acoes as $especie_acao)
						<tr> 
							<th>{{ $especie_acao->nome }}</th> 
							<td>
								<ul class="list-inline">
									<li>
										<a href="{{ route('editar-especie-acao',["id" => $especie_acao->id]) }}" class="btn btn-warning">Editar</a>
										<a href="{{ route('delete-especie-acao',["id" => $especie_acao->id]) }}" class="btn btn-danger">Excluir</a>
									</li>
								</ul>
							</td>
						</tr>
						@endforeach
					</tbody> 
				</table>
			</div>
			<a href="{{ route('cadastra-especie-acao') }}" class="btn btn-primary">Cadastrar nova Espécie de Ação</a>
		</div>
	</div>
@endsection