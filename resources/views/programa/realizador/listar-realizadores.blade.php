@extends('layouts.master')

@section('title')
    Listagem de Realizadores
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
						@foreach ($realizadores as $realizador)
						<tr> 
							<th>{{ $realizador->nome }}</th> 
							<td>
								<ul class="list-inline">
									<li>
										<a href="{{ route('editar-realizador',["id" => $realizador->id]) }}" class="btn btn-warning">Editar</a>
										<a href="{{ route('delete-realizador',["id" => $realizador->id]) }}" class="btn btn-danger">Excluir</a>
									</li>
								</ul>
							</td>
						</tr>
						@endforeach
					</tbody> 
				</table>
			</div>
			<a href="{{ route('cadastra-realizador') }}" class="btn btn-primary">Cadastrar novo município</a>
		</div>
	</div>
@endsection