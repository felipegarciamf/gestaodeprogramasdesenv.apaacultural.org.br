@extends('layouts.master')

@section('title')
    Listagem de Linguagem da Ação
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
						@foreach ($linguagem_acoes as $linguagem_acao)
						<tr> 
							<th>{{ $linguagem_acao->nome }}</th> 
							<td>
								<ul class="list-inline">
									<li>
										<a href="{{ route('editar-linguagem-acao',["id" => $linguagem_acao->id]) }}" class="btn btn-warning">Editar</a>
										<a href="{{ route('delete-linguagem-acao',["id" => $linguagem_acao->id]) }}" class="btn btn-danger">Excluir</a>
									</li>
								</ul>
							</td>
						</tr>
						@endforeach
					</tbody> 
				</table>
			</div>
			<a href="{{ route('cadastra-linguagem-acao') }}" class="btn btn-primary">Cadastrar nova linguagem</a>
		</div>
	</div>
@endsection