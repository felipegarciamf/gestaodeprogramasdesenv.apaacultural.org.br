@extends('layouts.master')

@section('title')
    Listagem de Atividades
@endsection

@section('content')
<div style="margin-bottom:11px;"> <a href="cadastra-atividade/1" class="btn btn-primary">Cadastrar nova atividade</a></div>

<form class="navbar-form navbar-left" role="search">
	<div class="form-group">
		{!! Form::open(['route' => 'listar-atividades', 'method' => 'GET', 'class' => 'navbar-form navbar-left pull-right', 'role' => 'search']) !!}
		<div class="form-group">
 		{!! Form::text('nome', null, ['class' => 'form-control', 'placeholder' => 'Nome da Atividade']) !!}
 		</div>
 		<div class="form-group">
 		{!! Form::text('data', null, ['class' => 'form-control', 'placeholder' => 'Data']) !!}
 		</div>
 		<div class="form-group">
 		{!! Form::text('municipio', null, ['class' => 'form-control', 'placeholder' => 'Municipio']) !!}
 		</div>
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
							<th>Data</th>
							<th>Horario</th>
							<th>Artista</th>
							<th>Municipio</th>
							<th>Programa</th>
						</tr> 
					</thead> 
					<tbody> 
						@foreach ($atividades as $atividade)
						<tr>
							<td>{{$atividade->nome}}</td>
							<td>{{$atividade->data}}</td>
							<td>{{$atividade->horario}}</td>
							<td>{{$atividade->artista}}</td>
							<td>{{$atividade->municipio->nome}}</td>
							<td>{{$atividade->programa->nome}}</td>
							<td>
								<ul class="list-inline">
									<li>
										<a href="{{ route('editar-atividade',["id" => $atividade->id]) }}" class="btn btn-warning">Editar</a>
										<a class="validaexcluir btn btn-danger" href="{{ route('delete-atividade',["id" => $atividade->id]) }}" class="btn btn-danger">Excluir</a>
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