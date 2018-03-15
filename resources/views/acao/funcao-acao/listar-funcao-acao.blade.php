@extends('layouts.master')

@section('title')
    Listagem de Função da Ação
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
						@foreach ($funcao_acoes as $funcao_acao)
						<tr> 
							<th>{{ $funcao_acao->nome }}</th> 
							<td>
								<ul class="list-inline">
									<li>
										<a href="{{ route('editar-funcao-acao',["id" => $funcao_acao->id]) }}" class="btn btn-warning">Editar</a>
										<a href="{{ route('delete-funcao-acao',["id" => $funcao_acao->id]) }}" class="btn btn-danger">Excluir</a>
									</li>
								</ul>
							</td>
						</tr>
						@endforeach
					</tbody> 
				</table>
			</div>
			<a href="{{ route('cadastra-funcao-acao') }}" class="btn btn-primary">Cadastrar nova função da ação</a>
		</div>
	</div>
@endsection