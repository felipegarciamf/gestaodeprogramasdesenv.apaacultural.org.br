@extends('layouts.master')

@section('title')
    Cadastro de Rotinas
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form class="form" name="form-cadastro-rotina" id="form-cadastro-rotina" action="{{ route('cria-rotina') }}" method="POST">
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" name="nome" class="form-control">
				</div>
				<div class="form-group">
					<label for="plano">Plano</label>
					<select class="form-control" name="plano" id="plano">
						<option value="">Selecione</option>
						@foreach ($planos as $plano)
						    <option value="{{ $plano->id }}">{{ $plano->nome }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="data_limite">Data Limite</label>
					<input type="text" name="data_limite" id="data_limite" readonly>
				</div>
				<button type="submit" class="btn btn-primary">Cadastrar</button>
				<!--Necessário para o laravel-->
				<input type="hidden" name="_token" value="{{ Session::token() }}">
			</form>
		</div>
	</div>
@endsection