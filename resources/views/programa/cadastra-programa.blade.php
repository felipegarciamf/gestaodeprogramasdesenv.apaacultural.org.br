@extends('layouts.master')

@section('title')
    Cadastro de Programa
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form class="form" name="form-cadastro-programa" id="form-cadastro-programa" action="{{ route('cria-programa') }}" method="POST">
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" name="nome" placeholder="Digite o nome do Programa" class="form-control">
				</div>
				<div class="form-group">
					<label for="plano">Plano</label>
					<select class="form-control" name="plano" id="plano-change-programa">
						<option value="">Selecione</option>
						@foreach ($planos as $plano)
						    <option value="{{ $plano->id }}">{{ $plano->nome }}</option>
						@endforeach
					</select>
				</div>
				<!--<div class="form-group">
					<label for="acao">Ação</label>
					<select class="form-control" name="acao" id="acao">
						<option value="">Selecione um plano</option>
					</select>
				</div>-->
				<div class="form-group">
					<label for="tipagem">Tipagem</label>
					<select class="form-control" name="tipagem" id="tipagem">
						<option value="">Selecione</option>
						@foreach ($tipagens as $tipagem)
						    <option value="{{ $tipagem->id }}">{{ $tipagem->nome }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="descricao">Descrição</label>
					<textarea name="descricao" id="descricao" class="form-control" required></textarea>
				</div>
				<button type="submit" class="btn btn-primary">Cadastrar</button>
				<!--Necessário para o laravel-->
				<input type="hidden" name="_token" value="{{ Session::token() }}">
			</form>
		</div>
	</div>
@endsection