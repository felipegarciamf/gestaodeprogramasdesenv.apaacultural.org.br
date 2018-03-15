@extends('layouts.master')

@section('title')
    Cadastro de Permissões de Usuário
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form class="form" name="form-cadastro-usuario-permissao" id="form-cadastro-usuario-permissao" action="{{ route('cria-usuario-permissao') }}" method="POST">
			<input type="hidden" value="{{$usuario->id}}" id="usuario" name="usuario">
				<div class="form-group">
					<label for="plano">Plano</label>
					<select class="form-control" name="plano" id="planos-ajax-permissao">
						<option value="">Selecione</option>
						@foreach ($planos as $plano)
						    <option value="{{ $plano->id }}">{{ $plano->nome }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="programa">Programa</label>
					<ul class="list-inline" id="programa">
						
					</ul>
				</div>
				<button type="submit" class="btn btn-primary">Cadastrar</button>
				<!--Necessário para o laravel-->
				<input type="hidden" name="_token" value="{{ Session::token() }}">
			</form>
		</div>
	</div>
@endsection