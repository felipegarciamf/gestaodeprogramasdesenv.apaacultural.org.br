@extends('layouts.master')

@section('title')
    Cadastro de Usuário
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form class="form" name="form-cadastro-usuario" id="form-cadastro-usuario" action="{{ route('cria-usuario') }}" method="POST">
				<div class="form-group">
					<label for="email">E-mail</label>
					<input type="email" name="email" class="form-control">
				</div>
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" name="nome" class="form-control">
				</div>
				<div class="form-group">
					<label for="sobrenome">Sobrenome</label>
					<input type="text" name="sobrenome" class="form-control">
				</div>
				<div class="form-group">
					<label for="senha">Senha</label>
					<input type="password" name="senha" class="form-control">
				</div>
				<div class="form-group">
					<label for="perfil">Perfil</label>
					<select class="form-control" name="perfil" id="perfil">
						<option value="">Selecione</option>
						<option value="1">Usuário</option>
						<option value="2">Administrador</option>
					</select>
				</div>
				<button type="submit" class="btn btn-primary">Cadastrar</button>
				<!--Necessário para o laravel-->
				<input type="hidden" name="_token" value="{{ Session::token() }}">
			</form>
		</div>
	</div>
@endsection