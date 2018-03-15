@extends('layouts.master')

@section('title')
    Listagem de Ações
@endsection

@section('content')
<div style="margin-bottom:11px;"><a href="cadastra-acao" class="btn btn-primary">Cadastrar nova ação</a> </div>
<form class="navbar-form navbar-left" role="search">
    <div class="form-group">
        {!! Form::open(['route' => 'listar-acoes', 'method' => 'GET', 'class' => 'navbar-form navbar-left pull-right', 'role' => 'search']) !!}
        <div class="form-group">
        	{{ Form::text('codigo', null, ['class' => 'form-control', 'placeholder' => 'Código'])}}
        </div>
        <div class="form-group">
        {!! Form::text('nome', null, ['class' => 'form-control', 'placeholder' => 'Nome da Ação']) !!}
        </div>
        <div class="form-group">
        {!! Form::text('programa_id', null, ['class' => 'form-control', 'placeholder' => 'Nome do Programa']) !!}
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
							<th>Código</th>
							<th>Nome</th>
							<th>Plano</th>
							<th>Programa</th>
							<th>Espécie</th>
							<th>Linguagem</th>
							<th>Função</th>
							<th>Regiao</th>
						</tr> 
					</thead> 
					<tbody>
						@foreach ($acoes as $acao)
							<tr>
								<td>{{ $acao->codigo_acao }}</td>
								<td>{{ $acao->nome }}</td>
								<td>{{ $acao->plano->nome }}</td>
								<td>{{ $acao->programa->nome }}</td>
								<td>{{ $acao->especie_acao->nome }}</td>
								<td>{{ $acao->linguagem_acao->nome }}</td>
								<td>{{ $acao->funcao_acao->nome }}</td>
								<td>{{ $acao->regiao_acao }}</td>
								<td>
									<ul class="list-inline">
										<li>
											<a href="{{ route('editar-acao',["id" => $acao->id]) }}" class="btn btn-warning">Editar</a>
											<a class="validaexcluir btn btn-danger" href="{{ route('delete-acao',["id" => $acao->id]) }}" class="btn btn-danger">Excluir</a>
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