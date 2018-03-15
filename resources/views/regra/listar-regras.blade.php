@extends('layouts.master')

@section('title')
    Listagem de Regras
@endsection

@section('content')
<div style="margin-bottom:11px;"><a href="{{ route('cadastra-regra') }}" class="btn btn-primary">Cadastrar nova regra</a> </div>
<form class="navbar-form navbar-left" role="seach">
	<div class="form-group">
		{!! Form::open(['route' => 'listar-regras', 'method' => 'GET', 'class' => 'navbar-form navbar-left pull-right', 'role' => 'search']) !!}
	</div>
	<div class="form-group">
		{!! Form::text('nome', null, ['class' => 'form-control', 'placeholder' => 'Nome da Regra']) !!}
	</div>
	


</form>
	<div class="row">
		<div class="col-md-12 col-sm-12">
			<div class="table table-responsive">
				<table class="table table-striped table-bordered"> 
					<thead> 
						<tr> 
							<th>Código</th>
							<th>Descrição</th>
										<th></th>
						</tr> 
					</thead> 
					<tbody> 
					@foreach ($regras as $regra)
					<tr> 
							<td>{{ $regra->codigo }}</td>
							<td>{{ $regra->descricao }}</td>
						 
							<td>
								<ul class="list-inline">
									<li>
										<a href="{{ route('editar-regra',["id" => $regra->id]) }}" class="btn btn-warning">Editar</a>
										<a class="validaexcluir btn btn-danger" href="{{ route('delete-regra',["id" => $regra->id]) }}" class="btn btn-danger">Excluir</a>
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