@extends('layouts.master')

@section('title')
    Listagem de Indicadores
@endsection

@section('content')

<div style="margin-bottom:11px;"><a href="{{ route('cadastra-indicador') }}" class="btn btn-primary">Cadastrar novo indicador</a></div>

<!-- filtro puxando indicadores pelo nome da regra -->
<form class="navbar-form navbar-left" role="search">
	{!! Form::open(['route' => 'listar-indicadores', 'method' => 'GET', 'class' => 'navbar-form navbar-left pull-right', 'role' => 'search']) !!}

	<div class="form-group">
	{!! Form::text('nome', null, ['class' => 'form-control', 'placeholder' => 'Nome do Indicador']) !!}
	</div>
	<div class="form-group">
		{{ Form::text('plano',null,['class' => 'form-control', 'placeholder' => 'Plano']) }}
	</div>
	<button type="submit" class="btn btn-default">Pesquisar</button>
</form>
	<div class="row">
		<div class="col-md-12 col-sm-12">
			<div class="table table-responsive">
				<table class="table table-striped table-bordered"> 
					<thead> 
						<tr> 
							<th>Regra</th>
							<th>Nome Indicador</th>
							<th>Nome Regra</th>
							<th>Número da Ação</th>
							<th>Ação</th>
							<th>Plano</th>
						</tr> 
					</thead> 
					<tbody> 
						@foreach ($indicadores as $indicador)
						<tr> 
							<th>{{ $indicador->regra->codigo }}</th>
							<th>{{ $indicador->nome_indicador }}</th>
							<th>{{ $indicador->regra->descricao }}</th>
							<th>{{ $indicador->acao->codigo_acao }}</th>	 
							<th>{{ $indicador->acao->nome }}</th> 
							<th>{{ $indicador->plano->nome }}</th>	
							<td>
								<ul class="list-inline">
									<li>
										<a href="{{ route('editar-indicador',["id" => $indicador->id]) }}" class="btn btn-warning">Editar</a>
										<a class="validaexcluir btn btn-danger" href="{{ route('delete-indicador',["id" => $indicador->id]) }}" class="btn btn-danger">Excluir</a>
									</li>

								</ul>
							</td>
						</tr>
						@endforeach
					</tbody> 
				</table>
			</div>
			
		</div>
	</div>
@endsection