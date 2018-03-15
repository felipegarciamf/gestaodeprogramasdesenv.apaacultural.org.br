@extends('layouts.master')

@section('title')
    Editar Município
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form action="{{ route('update-municipio',['id' => $municipio->id]) }}" name="form-editar-municipio" id="form-editar-municipio" method="post">
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" name="nome" class="form-control" value="{{ $municipio->nome }}">
				</div>
				<div class="form-group">
					<label for="regiao_administrativa">Região Administrativa</label>
					<select class="form-control" name="regiao_administrativa" id="regiao_administrativa">
						<option value="">Selecione</option>
						@foreach ($regiaoadministrativas as $regiaoadministrativa)
							@if($municipio->regiao_administrativa->id == $regiaoadministrativa->id)
							    <option selected value="{{ $regiaoadministrativa->id }}">{{ $regiaoadministrativa->nome }}</option>
							@else
							    <option value="{{ $regiaoadministrativa->id }}">{{ $regiaoadministrativa->nome }}</option>
							@endif
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="distancia">Distancia</label>
					<input type="number" name="distancia" class="form-control" value="{{ $municipio->distancia }}">
				</div>
				<button type="submit" class="btn btn-primary">Editar</button>
				<!--Necessário para o laravel-->
				<input type="hidden" name="_token" value="{{ Session::token() }}">
			</form>
		</div>
	</div>
@endsection