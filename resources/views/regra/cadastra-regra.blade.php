@extends('layouts.master')

@section('title')
    Cadastro de Regra
@endsection

@section('content')
<!-- SILAS -->
<div class="teste"></div>
<div class="row">
		<div class="col-md-6 col-sm-8">
			<form class="form" name="form-cadastro-regra" id="form-cadastro-regra" action="{{ route('cria-regra') }}" method="POST">
				<div class="form-group">
					<label for="codigo_regra">Código da Regra</label>
					<input type="text" name="codigo_regra" id="codigo_regra" class="form-control">
				</div>
				<div class="form-group">
					<label for="descricao">Descricao</label>
					<input type="text" name="descricao" id="descricao" class="form-control">
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

				<button type="submit" class="btn btn-primary">Cadastrar</button>
				<!--Necessário para o laravel-->
				<input type="hidden" name="_token" value="{{ Session::token() }}">
				
			</form>
		</div>
	</div>
@endsection