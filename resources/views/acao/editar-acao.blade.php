@extends('layouts.master')

@section('title')
    Editar Ação
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form action="{{ route('update-acao',['id' => $acao->id]) }}" name="form-editar-acao" id="form-editar-acao" method="post">
				<div class="form-group">
					<label for="codigo">Numero</label>
					<input type="number" name="codigo" id="codigo" value="{{ $acao->codigo_acao }}" class="form-control">
				</div>
				<!-- Usado para fazer o ajax -->
				<input type="text" id="urlAjaxProgramasAcaoEdit" value="{{route('chama-programas-acao')}}" hidden>
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" name="nome" id="nome" value="{{ $acao->nome }}" class="form-control">
				</div>
				<div class="form-group">
					<label for="plano">Plano</label>
					<select class="form-control" name="plano" id="planos-ajax-acao-edit">
						<option value="">Selecione</option>
						@foreach ($planos as $plano)
							@if($acao->plano->id == $plano->id)
							    <option selected value="{{ $plano->id }}">{{ $plano->nome }}</option>
							@else
							    <option value="{{ $plano->id }}">{{ $plano->nome }}</option>
							@endif
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="programa">Programa</label>
					<select class="form-control" name="programa" id="programa">
						<option value="">Selecione</option>
						@foreach ($programas as $programa)
							@if($acao->programa->id == $programa->id)
							    <option selected value="{{ $programa->id }}">{{ $programa->nome }}</option>
							@else
							    <option value="{{ $programa->id }}">{{ $programa->nome }}</option>
							@endif
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="especie">Espécie</label>
					<select class="form-control" name="especie" id="especie">
						<option value="">Selecione</option>
						@foreach ($especies_acao as $especie)
							@if($acao->especie_acao->id == $especie->id)
							    <option selected value="{{ $especie->id }}">{{ $especie->nome }}</option>
							@else
							    <option value="{{ $especie->id }}">{{ $especie->nome }}</option>
							@endif
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="linguagem">Linguagem</label>
					<select class="form-control" name="linguagem" id="linguagem">
						<option value="">Selecione</option>
						@foreach ($linguagens_acao as $linguagem)
							@if($acao->linguagem_acao->id == $linguagem->id)
							    <option selected value="{{ $linguagem->id }}">{{ $linguagem->nome }}</option>
							@else
							    <option value="{{ $linguagem->id }}">{{ $linguagem->nome }}</option>
							@endif
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="funcao">Função</label>
					<select class="form-control" name="funcao" id="funcao">
						<option value="">Selecione</option>
						@foreach ($funcoes_acao as $funcao)
							@if($acao->funcao_acao->id == $funcao->id)
							    <option selected value="{{ $funcao->id }}">{{ $funcao->nome }}</option>
							@else
							    <option value="{{ $funcao->id }}">{{ $funcao->nome }}</option>
							@endif
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="regiao">Região</label>
					<input type="text" value="{{ $acao->regiao_acao }}" name="regiao" id="regiao">
				</div>
				<button type="submit" class="btn btn-primary">Editar</button>
				<!--Necessário para o laravel-->
				<input type="hidden" name="_token" value="{{ Session::token() }}">
			</form>
		</div>
	</div>
@endsection