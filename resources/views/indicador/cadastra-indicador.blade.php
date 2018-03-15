@extends('layouts.master')

@section('title')
    Cadastro de Indicador
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form class="form" name="form-cadastro-indicador" id="form-cadastro-indicador" action="{{ route('cria-indicador') }}" method="POST">
				<div class="form-group">
					<label for="regra">Regras</label>
					<select class="form-control" name="regra" id="regra">
						<option value="">Selecione</option>
						@foreach ($regra as $regras)
						    <option value="{{ $regras->id }}">{{ $regras->codigo }} - {{ $regras->descricao }}</option>
						@endforeach
					</select>
				</div>


				<div class="form-group">
					<label for="nome_indicador">Nome Indicador</label>
					<input type="text" name="nome_indicador" id="nome_indicador" class="form-control">
				</div>

				<div class="form-group">
					<label for="plano">Plano</label>
					<select class="form-control" name="plano" id="planos-ajax-indicador">
						<option value="">Selecione</option>
						@foreach ($planos as $plano)
						    <option value="{{ $plano->id }}">{{ $plano->nome }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="acao">Ação</label>
					<select class="form-control" name="acao" id="acao">
						<option value="">Selecione um plano</option>
					</select>
				</div>
				<div class="form-group">
					<label for="meta_1_tri">Meta 1º Trimestre</label>
					<input type="text" name="meta_1_tri" id="meta_1_tri" value="0" class="form-control">
				</div>
				<div class="form-group">
					<label for="meta_2_tri">Meta 2º Trimestre</label>
					<input type="text" name="meta_2_tri" id="meta_2_tri" value="0" class="form-control">
				</div>
				<div class="form-group">
					<label for="meta_3_tri">Meta 3º Trimestre</label>
					<input type="text" name="meta_3_tri" id="meta_3_tri" value="0" class="form-control">
				</div>
				<div class="form-group">
					<label for="meta_4_tri">Meta 4º Trismestre</label>
					<input type="text" name="meta_4_tri" id="meta_4_tri" value="0" class="form-control">
				</div>
				<!--Necessário para o laravel-->
				<input type="hidden" name="_token" value="{{ Session::token() }}">
				<button type="submit" class="btn btn-primary">Cadastrar</button>
			</form>
		</div>
	</div>
@endsection