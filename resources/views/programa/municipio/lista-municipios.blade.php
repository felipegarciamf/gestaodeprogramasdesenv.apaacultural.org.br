@extends('layouts.master')

@section('title')
    Listagem de Municípios
@endsection

@section('content')
	<div class="row">
		<div class="col-md-8 col-sm-8">
			<div class="table table-responsive">
				<table class="table table-striped table-bordered"> 
					<thead> 
						<tr> 
							<th>Nome</th>
							<th>Regiao Administrativa</th>
							<th>Distancia</th>
						</tr> 
					</thead> 
					<tbody> 
						@foreach ($municipios as $municipio)
						<tr> 
							<th>{{ $municipio->nome }}</th> 
							<th>{{ $municipio->regiao_administrativa->nome }}</th> 
							<th>{{ $municipio->distancia }}</th> 
							<td>
								<ul class="list-inline">
									<li>
										<a href="{{ route('editar-municipio',["id" => $municipio->id]) }}" class="btn btn-warning">Editar</a>
										<a class="validaexcluir btn btn-danger" href="{{ route('delete-municipio',["id" => $municipio->id]) }}" class="btn btn-danger">Excluir</a>
									</li>
								</ul>
							</td>
						</tr>
						@endforeach
					</tbody> 
				</table>
			</div>
			<a href="{{ route('cadastra-municipio') }}" class="btn btn-primary">Cadastrar novo município</a>
		</div>
	</div>
@endsection