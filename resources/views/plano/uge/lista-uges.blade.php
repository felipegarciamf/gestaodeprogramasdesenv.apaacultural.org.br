@extends('layouts.master')

@section('title')
    Listagem de Uge
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
						@foreach ($uges as $uge)
						<tr> 
							<th>{{ $uge->nome }}</th> 
							<td>
								<ul class="list-inline">
									<li>
										<a href="{{ route('editar-uge',["id" => $uge->id]) }}" class="btn btn-warning">Editar</a>
										<a class="validaexcluir btn btn-danger" href="{{ route('delete-uge',["id" => $uge->id]) }}" class="btn btn-danger">Excluir</a>
									</li>
								</ul>
							</td>
						</tr>
						@endforeach
					</tbody> 
				</table>
			</div>
			<a href="{{ route('cadastra-uge') }}" class="btn btn-primary">Cadastrar nova Uge</a>
		</div>
	</div>
@endsection