@extends('layouts.master')

@section('title')
    Cadastro de Município
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form class="form" name="form-cadastro-municipio" id="form-cadastro-municipio" action="{{ route('cria-municipio') }}" method="POST">
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" name="nome" class="form-control">
				</div>
				<div class="form-group">
					<label for="regiao_administrativa">Região Administrativa</label>
					<select class="form-control" name="regiao_administrativa" id="regiao_administrativa">
						<option value="">Selecione</option>
						@foreach ($regiaoadministrativas as $regiaoadministrativa)
						    <option value="{{ $regiaoadministrativa->id }}">{{ $regiaoadministrativa->nome }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="nome">Distância</label>
					<input type="number" name="distancia" id="distancia" class="form-control">
				</div>
				<button type="submit" class="btn btn-primary">Cadastrar</button>
				<!--Necessário para o laravel-->
				<input type="hidden" name="_token" value="{{ Session::token() }}">
			</form>
		</div>
	</div>
@endsection