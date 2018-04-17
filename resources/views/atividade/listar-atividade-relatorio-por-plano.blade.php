@extends('layouts.master')

@section('title')
    Listagem de Relatório de Atividades
@endsection

@section('content')

<div style="margin-bottom:11px;">
<a href="{{ route('extrair-relatorio-programas-atividade')}}" class="btn btn-primary">
 Extrair Relatorio</a></div>

<form class="navbar-form navbar-left" role="search">
	<div class="form-group">
		{{ Form::open(['route' => 'listar-relatorio-programas-atividades', 'method' => 'GET', 'class' => 'navbar-form navbar-left pull-right', 'role' => 'search']) }}

	<div class="form-group">
		{{ Form::text('nome', null, ['class' => 'form-control', 'placeholder' => 'Nome da Atividade']) }}
	</div>
	<div class="form-group">
		{{ Form::text('dataini', null,['class' => 'form-control', 'placeholder' => 'Data Inicial']) }}
	</div>
	<div class="form-group">
		{{ Form::text('datafim', null,['class' => 'form-control', 'placeholder' => 'Data Final']) }}
	</div>

	<div class="form-group">
		{{ Form::text('municipio', null, ['class' => 'form-control', 'placeholder' => 'Municipio']) }}
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
					<th>Plano</th>
					<th>Programa</th>
					<th>Data Inicial</th>
					<th>Data Final</th>
					<th>Horario</th>
					<th>Artista</th>
					<th>Municipio</th>
					<th>Local</th>
					<th>Número de Pessoas</th>
					<th>Acessibilidade</th>
					<th>Tipo de Evento</th>
					<th>Número de Apresentações</th>
					<th>Realizador</th>
				</tr>
			</thead>
				<tbody>
					@foreach ($atividades as $atividade)
					<tr>
						<td>{{$atividade->nome}} </td>
						<td>{{$atividade->plano->nome}}</td>
						<td>{{$atividade->programa->nome }}</td>
						<td>{{$atividade->data}}</td>
						<td>{{$atividade->data_fim}}</td>
						<td>{{$atividade->horario}}</td>
						<td>{{$atividade->artista}}</td>
						<td>{{$atividade->municipio->nome}}</td>
						<td>{{$atividade->local}}</td>
						<td>{{$atividade->num_total_pessoas }} </td>
						<td>{{$atividade->sessao_acessivel}}</td>
						<td>{{$atividade->tipo_evento->nome}}</td>
						<td>{{$atividade->num_total_artistas}}</td>
						<td>{{$atividade->realizador->nome}}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection
