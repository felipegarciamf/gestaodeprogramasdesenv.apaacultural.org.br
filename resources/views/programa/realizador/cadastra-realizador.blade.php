@extends('layouts.master')

@section('title')
    Cadastro de Realizador
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form class="form" name="form-cadastro-realizador" id="form-cadastro-realizador" action="{{ route('cria-realizador') }}" method="POST">
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" name="nome" class="form-control">
				</div>
				<div class="form-group">
					<label for="nome">Número Total de Pessoas</label>
					<input type="number" name="num_total_pessoas" id="num_total_pessoas" class="form-control">
				</div>
				<div class="form-group">
					<label for="nome">Número Apresentações</label>
					<input type="number" name="num_apresentacoes" id="num_apresentacoes" class="form-control">
				</div>
				<div class="form-group">
					<label for="regiao_administrativa">Cidade</label>
					<select class="form-control" name="municipio" id="municipio">
						<option value="">Selecione</option>
						@foreach ($municipios as $municipio)
						    <option value="{{ $municipio->id }}">{{ $municipio->nome }}</option>
						@endforeach
					</select>
				</div>
				<button type="submit" class="btn btn-primary">Cadastrar</button>
				<!--Necessário para o laravel-->
				<input type="hidden" name="_token" value="{{ Session::token() }}">
			</form>
		</div>
	</div>
@endsection