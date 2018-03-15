@extends('layouts.master')

@section('title')
    Editar Programa
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form action="{{ route('update-programa',['id' => $programa->id]) }}" name="form-editar-programa" id="form-editar-programa" method="post">
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" name="nome" value="{{ $programa->nome }}" class="form-control">
				</div>
				<div class="form-group">
					<label for="plano">Plano</label>
					<select class="form-control" name="plano" id="plano">
						<option value="">Selecione</option>
						@foreach ($planos as $plano)
							@if($programa->plano->id == $plano->id)
							    <option selected value="{{ $plano->id }}">{{ $plano->nome }}</option>
							@else
							    <option value="{{ $plano->id }}">{{ $plano->nome }}</option>
							@endif
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="perfil">Tipagem</label>
					<select class="form-control" name="tipagem" id="tipagem">
						<option value="">Selecione</option>
						@foreach ($tipagens as $tipagem)
							@if($programa->tipagem->id == $tipagem->id)
							    <option selected value="{{ $tipagem->id }}">{{ $tipagem->nome }}</option>
							@else
							    <option value="{{ $tipagem->id }}">{{ $tipagem->nome }}</option>
							@endif
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="descricao">Descrição</label>
					<textarea name="descricao" class="form-control" id="descricao" required>{{ $programa->descricao }}</textarea>
				</div>
				<button type="submit" class="btn btn-primary">Editar</button>
				<!--Necessário para o laravel-->
				<input type="hidden" name="_token" value="{{ Session::token() }}">
			</form>
		</div>
	</div>
@endsection
			
			