@extends('layouts.master')

@section('title')
    Listagem de Rotinas
@endsection

@section('content')
<div style="margin-bottom:11px;"><a href="cadastra-rotina" class="btn btn-primary">Cadastrar nova rotina</a> </div>
	<div class="row">
		<div class="col-md-8 col-sm-8">
			<div class="table table-responsive">
				<table class="table table-striped table-bordered"> 
					<thead> 
						<tr> 
							<th>Nome</th> 
							<th>Plano</th> 
							<th>Status</th> 
							<th>Data Limite</th>
							<th>Ação</th> 
						</tr> 
					</thead> 
					<tbody> 
						@foreach ($rotinas as $rotina)
						<td>{{$rotina->nome}}</td>
						<td>{{$rotina->plano->nome}}</td>
						<td>{{$rotina->realizada}}</td>
						<td>{{$rotina->data_limite}}</td>
						<td>
							<ul class="list-inline">
								<li>
									<a href="{{ route('editar-rotina',["id" => $rotina->id]) }}" class="btn btn-warning">Editar</a>
									<a class="validaexcluir btn btn-danger" href="{{ route('delete-rotina',["id" => $rotina->id]) }}" class="btn btn-danger">Excluir</a>
								</li>
							</ul>
						</td>
						@endforeach
					</tbody> 
				</table>
			</div>

		</div>
	</div>
@endsection