@extends('layouts.master')

@section('title')
    Listagem de Tipo de Evento
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
						@foreach ($tipoeventos as $tipoevento)
						<tr> 
							<th>{{ $tipoevento->nome }}</th> 
							<td>
								<ul class="list-inline">
									<li>
										<a href="{{ route('editar-tipo-evento',["id" => $tipoevento->id]) }}" class="btn btn-warning">Editar</a>
										<a href="{{ route('delete-tipo-evento',["id" => $tipoevento->id]) }}" class="btn btn-danger">Excluir</a>
									</li>
								</ul>
							</td>
						</tr>
						@endforeach
					</tbody> 
				</table>
			</div>
			<a href="{{ route('cadastra-tipo-evento') }}" class="btn btn-primary">Cadastrar novo Tipo de Evento</a>
		</div>
	</div>
@endsection