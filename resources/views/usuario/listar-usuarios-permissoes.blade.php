@extends('layouts.master')

@section('title')
    Listagem de permissões por usuários
@endsection

@section('content')
	<div class="row">
		<div class="col-md-8 col-sm-8">
			<div class="table table-responsive">
				<table class="table table-striped table-bordered"> 
					<thead> 
						<tr> 
							<th>Nome</th> 
							<th>E-mail</th> 
						</tr> 
					</thead> 
					<tbody> 
						@foreach ($usuarios as $usuario)
						<tr> 
							<th>{{ $usuario->nome." ".$usuario->sobrenome}}</th> 
							<td>{{ $usuario->email}}</td>
							<td>
								<ul class="list-inline">
									<li>
										<a href="{{ route('listar-permissoes-por-usuario',["id" => $usuario->id]) }}" class="btn btn-success">Permissões</a>
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