@extends('layouts.master')

@section('title')
    Editar Gênero de Linguagem
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form action="{{ route('update-genero-linguagem',['id' => $genero_linguagem->id]) }}" name="form-editar-genero-linguagem" id="form-editar-genero-linguagem" method="post">
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" name="nome" class="form-control" value="{{ $genero_linguagem->nome }}">
				</div>
				<div class="form-group">
					<label for="linguagem">Linguagem</label>
					<select class="form-control" name="linguagem" id="linguagem">
						<option value="">Selecione</option>
						@foreach ($linguagens_programa as $linguagem)
							@if($genero_linguagem->linguagem_programa->id == $linguagem->id)
							    <option selected value="{{ $linguagem->id }}">{{ $linguagem->nome }}</option>
							@else
							    <option value="{{ $linguagem->id }}">{{ $linguagem->nome }}</option>
							@endif
						@endforeach
					</select>
				</div>
				<button type="submit" class="btn btn-primary">Editar</button>
				<!--Necessário para o laravel-->
				<input type="hidden" name="_token" value="{{ Session::token() }}">
			</form>
		</div>
	</div>
@endsection