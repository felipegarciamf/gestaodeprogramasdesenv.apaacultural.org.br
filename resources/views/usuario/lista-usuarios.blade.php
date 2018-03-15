@extends('layouts.master')

@section('title')
    Listagem de usuários
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
										<a href="{{ route('editar-usuario',["id" => $usuario->id]) }}" class="btn btn-warning">Editar</a>
										<a class="validaexcluir btn btn-danger" href="{{ route('delete-usuario',["id" => $usuario->id]) }}" class="btn btn-danger">Excluir</a>
									</li>
								</ul>
							</td>
						</tr>
						@endforeach
					</tbody> 
				</table>
			</div>
			<a href="cadastra-usuario" class="btn btn-primary">Cadastrar novo usuário</a>
		</div>
	</div>
@endsection