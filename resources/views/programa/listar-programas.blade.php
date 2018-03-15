@extends('layouts.master')

@section('title')
    Listagem de Programas
@endsection

@section('content')
<div style="margin-bottom:11px;"> <a href="cadastra-programa" class="btn btn-primary">Cadastrar novo Programa</a></div>
	<!-- Filtro por programa -->
	<form class="navbar-form navbar-left" role="search">
	{!! Form::open(['route' => 'listar-programas', 'method' => 'GET', 'class' => 'navbar-form navbar-left pull-right', 'role' => 'search']) !!}

	<div class="form-group">
		{!! Form::text('nome', null, ['class' => 'form-control', 'placeholder' => 'Nome do Programa']) !!}

	</div>
	<button type="submit" class="btn btn-default">Pesquisar</button>
	</form>

	<div class="row">
		<div class="col-md-12 col-sm-12">
			<div class="table table-responsive">
				<table class="table table-striped table-bordered"> 
					<thead> 
						<tr> 
							<th>Nome</th> 
							<th>Plano</th> 
							<th>Tipagem</th> 
							<th>Descrição</th>
						</tr> 
					</thead> 
					<tbody> 
						@foreach ($programas as $programa)
						<tr>
							<td>{{$programa->nome}}</td>
							<td>{{$programa->plano->nome}}</td>
							<td>{{$programa->tipagem->nome}}</td>
							<td>{{$programa->descricao}}</td>
							<td>
								<ul class="list-inline">
									<li>
										<a href="{{ route('editar-programa',["id" => $programa->id]) }}" class="btn btn-warning">Editar</a>
										<a class="validaexcluir btn btn-danger" href="{{ route('delete-programa',["id" => $programa->id]) }}" class="btn btn-danger">Excluir</a>
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