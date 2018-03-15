@extends('layouts.master')

@section('title')
    Listagem de Planos
@endsection

@section('content')
<div style="margin-bottom:11px;"> <a href="cadastra-plano" class="btn btn-primary">Cadastrar novo plano</a></div>
	<div class="row">
		<div class="col-md-12 col-sm-12">
			<div class="table table-responsive">
				<table class="table table-striped table-bordered"> 
					<thead> 
						<tr> 
							<th>Nome</th> 
							<th>Uge</th> 
							<th>Cg</th> 
							<th>Objeto</th> 
							<th>Tipo do Objeto</th> 
							<th>Os</th> 
							<th>Data Limite</th> 
							<th>Ação</th> 
						</tr> 
					</thead> 
					<tbody> 
						@foreach ($planos as $plano)
						<tr>
							<td>{{$plano->nome}}</td>
							<td>{{$plano->uge->nome}}</td>
							<td>{{$plano->cg->nome}}</td>
							<td>{{$plano->objeto->nome}}</td>
							<td>{{$plano->tipo_objeto->nome}}</td>
							<td>{{$plano->os->nome}}</td>
							<td>{{$plano->data_limite}}</td>
							<td>
								<ul class="list-inline">
									<li>
										<a href="{{ route('editar-plano',["id" => $plano->id]) }}" class="btn btn-warning">Editar</a>
										<a class="validaexcluir btn btn-danger" href="{{ route('delete-plano',["id" => $plano->id]) }}" class="btn btn-danger">Excluir</a>
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