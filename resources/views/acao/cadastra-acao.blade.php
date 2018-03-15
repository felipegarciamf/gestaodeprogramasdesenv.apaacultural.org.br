@extends('layouts.master')

@section('title')
    Cadastro de Ação
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form class="form" name="form-cadastro-acao" id="form-cadastro-acao" action="{{ route('cria-acao') }}" method="POST">
				<div class="form-group">
					<label for="codigo">Código</label>
					<input type="number" name="codigo" placeholder="Digite o código da ação" id="codigo" class="form-control">
				</div>
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" name="nome" placeholder="Digite o nome da Ação" id="nome" class="form-control">
				</div>


				
				<div class="form-group">
					<label for="plano">Plano</label>
					<select class="form-control" name="plano" id="planos-ajax-acao">
						<option value="">Selecione</option>
						@foreach ($planos as $plano)
						    <option value="{{ $plano->id }}">{{ $plano->nome }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="programa">Programa</label>
					<select class="form-control" name="programa" id="programa">
						<option value="">Selecione um plano</option>
					</select>
				</div>



				<div class="form-group">
					<label for="especie">Espécie</label>
					<select class="form-control" name="especie" id="especie">
						<option value="">Selecione</option>
						@foreach ($especies_acao as $especie)
						    <option value="{{ $especie->id }}">{{ $especie->nome }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="linguagem">Linguagem</label>
					<select class="form-control" name="linguagem" id="linguagem">
						<option value="">Selecione</option>
						@foreach ($linguagens_acao as $linguagem)
						    <option value="{{ $linguagem->id }}">{{ $linguagem->nome }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="funcao">Função</label>
					<select class="form-control" name="funcao" id="funcao">
						<option value="">Selecione</option>
						@foreach ($funcoes_acao as $funcao)
						    <option value="{{ $funcao->id }}">{{ $funcao->nome }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="regiao">Região</label>
					<input type="text" name="regiao" placeholder="Digite a Região" id="regiao" class="form-control">
				</div>
				<button type="submit" class="btn btn-primary">Cadastrar</button>
				<!--Necessário para o laravel-->
				<input type="hidden" name="_token" value="{{ Session::token() }}">
			</form>
		</div>
	</div>
@endsection