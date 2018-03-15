@extends('layouts.master')

@section('title')
    Editar Perfil de Usuário
@endsection

@section('content')
	@if($usuario != null)
		<div class="row">
			<div class="col-md-8">
				<form action="{{route('update-perfil',['id' => $usuario->id])}}" method="POST">
					<div class="form-group">
						<label for="">E-mail</label>
						<input type="text" class="form-control" value="{{$usuario->email}}" name="email" id="email">
					</div>
					<div class="form-group">
						<label for="">Nome</label>
						<input type="text" class="form-control" value="{{$usuario->nome}}" name="nome" id="nome">
					</div>
					<div class="form-group">
						<label for="">Sobrenome</label>
						<input type="text" class="form-control" value="{{$usuario->sobrenome}}" name="sobrenome" id="sobrenome">
					</div>
					<div class="form-group">
						<label for="">Senha</label>
						<input type="password" class="form-control" value="" name="senha" id="senha">
					</div>
					<div class="form-group">
						<button class="btn btn-primary" >Editar Perfil</button>
					</div>
					<input type="hidden" name="_token" value="{{ Session::token() }}">
				</form>
			</div>
		</div>
	@else
		<p>Acesso não autorizado</p>
	@endif	
@endsection