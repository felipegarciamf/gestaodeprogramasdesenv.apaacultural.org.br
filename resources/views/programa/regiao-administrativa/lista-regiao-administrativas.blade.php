@extends('layouts.master')

@section('title')
    Listagem de Regiões Admnistrativas
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
						@foreach ($regiaoadministrativas as $regiaoadministrativa)
						<tr> 
							<th>{{ $regiaoadministrativa->nome }}</th> 
							<td>
								<ul class="list-inline">
									<li>
										<a href="{{ route('editar-regiao-administrativa',["id" => $regiaoadministrativa->id]) }}" class="btn btn-warning">Editar</a>
										<a href="{{ route('delete-regiao-administrativa',["id" => $regiaoadministrativa->id]) }}" class="btn btn-danger">Excluir</a>
									</li>
								</ul>
							</td>
						</tr>
						@endforeach
					</tbody> 
				</table>
			</div>
			<a href="{{ route('cadastra-regiao-administrativa') }}" class="btn btn-primary">Cadastrar nova Região Administrativa</a>
		</div>
	</div>
@endsection