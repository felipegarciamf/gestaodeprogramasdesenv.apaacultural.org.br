@extends('layouts.master')

@section('title')
    Listagem de Tipo de Público
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
						@foreach ($tipopublicos as $tipopublico)
						<tr> 
							<th>{{ $tipopublico->nome }}</th> 
							<td>
								<ul class="list-inline">
									<li>
										<a href="{{ route('editar-tipo-publico',["id" => $tipopublico->id]) }}" class="btn btn-warning">Editar</a>
										<a href="{{ route('delete-tipo-publico',["id" => $tipopublico->id]) }}" class="btn btn-danger">Excluir</a>
									</li>
								</ul>
							</td>
						</tr>
						@endforeach
					</tbody> 
				</table>
			</div>
			<a href="{{ route('cadastra-tipo-publico') }}" class="btn btn-primary">Cadastrar novo Tipo de Público</a>
		</div>
	</div>
@endsection