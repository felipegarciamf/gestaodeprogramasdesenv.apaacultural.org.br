@extends('layouts.master')

@section('title')
    Cadastro de Gênero de Linguagem
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form class="form" name="form-cadastro-genero-linguagem" id="form-cadastro-genero-linguagem" action="{{ route('cria-genero-linguagem') }}" method="POST">
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" name="nome" class="form-control">
				</div>
				<div class="form-group">
					<label for="linguagem">Linguagem</label>
					<select class="form-control" name="linguagem" id="linguagem">
						<option value="">Selecione</option>
						@foreach ($linguagens as $linguagem)
						    <option value="{{ $linguagem->id }}">{{ $linguagem->nome }}</option>
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