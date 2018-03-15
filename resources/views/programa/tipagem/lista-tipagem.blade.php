@extends('layouts.master')

@section('title')
    Listagem de Tipagem
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
						@foreach ($tipagens as $tipagem)
						<tr> 
							<th>{{ $tipagem->nome }}</th> 
							<td>
								<ul class="list-inline">
									<li>
										<a href="{{ route('editar-tipagem',["id" => $tipagem->id]) }}" class="btn btn-warning">Editar</a>
										<a href="{{ route('delete-tipagem',["id" => $tipagem->id]) }}" class="btn btn-danger">Excluir</a>
									</li>
								</ul>
							</td>
						</tr>
						@endforeach
					</tbody> 
				</table>
			</div>
			<a href="{{ route('cadastra-tipagem') }}" class="btn btn-primary">Cadastrar novo Tipagem</a>
		</div>
	</div>
@endsection