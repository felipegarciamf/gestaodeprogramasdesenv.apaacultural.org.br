@extends('layouts.master')

@section('title')
    Editar Usuário
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form action="{{ route('update-usuario',['id' => $usuario->id]) }}" name="form-editar-usuario" id="form-editar-usuario" method="post">
				<div class="form-group">
					<label for="email">E-mail</label>
					<input type="email" name="email" class="form-control" value="{{ $usuario->email }}">
				</div>
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" name="nome" class="form-control" value="{{ $usuario->nome }}">
				</div>
				<div class="form-group">
					<label for="sobrenome">Sobrenome</label>
					<input type="text" name="sobrenome" class="form-control" value="{{ $usuario->sobrenome }}">
				</div>
				<div class="form-group">
					<label for="senha">Senha</label>
					<input type="password" name="senha" class="form-control">
				</div>
				<div class="form-group">
					<label for="perfil">Perfil</label>
					<select name="perfil" id="perfil">
						<option value="">Selecione</option>
						@if($usuario->perfil == 1)
							<option selected="selected" value="1">Usuário</option>
							<option value="2">Administrador</option>
						@elseif($usuario->perfil == 2)
							<option value="1">Usuário</option>
							<option selected="selected" value="2">Administrador</option>
						@endif
						
					</select>
				</div>
				<button type="submit" class="btn btn-primary">Editar</button>
				<!--Necessário para o laravel-->
				<input type="hidden" name="_token" value="{{ Session::token() }}">
			</form>
		</div>
	</div>
@endsection