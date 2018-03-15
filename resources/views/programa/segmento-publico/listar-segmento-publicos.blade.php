@extends('layouts.master')

@section('title')
    Listagem de Segmento de Público
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
						@foreach ($segmentopublicos as $segmentopublico)
						<tr> 
							<th>{{ $segmentopublico->nome }}</th> 
							<td>
								<ul class="list-inline">
									<li>
										<a href="{{ route('editar-segmento-publico',["id" => $segmentopublico->id]) }}" class="btn btn-warning">Editar</a>
										<a href="{{ route('delete-segmento-publico',["id" => $segmentopublico->id]) }}" class="btn btn-danger">Excluir</a>
									</li>
								</ul>
							</td>
						</tr>
						@endforeach
					</tbody> 
				</table>
			</div>
			<a href="{{ route('cadastra-segmento-publico') }}" class="btn btn-primary">Cadastrar novo Segmento de Público</a>
		</div>
	</div>
@endsection