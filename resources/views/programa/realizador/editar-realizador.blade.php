@extends('layouts.master')

@section('title')
    Editar Realizador
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form action="{{ route('update-realizador',['id' => $realizador->id]) }}" name="form-editar-realizador" id="form-editar-realizador" method="post">
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" name="nome" class="form-control" value="{{ $realizador->nome }}">
				</div>
				<div class="form-group">
					<label for="num_total_pessoas">Número Total de Pessoas</label>
					<input type="number" name="num_total_pessoas" class="form-control" value="{{ $realizador->num_total_pessoas }}">
				</div>
				<div class="form-group">
					<label for="num_apresentacoes">Número de Apresentações</label>
					<input type="number" name="num_apresentacoes" class="form-control" value="{{ $realizador->num_apresentacoes }}">
				</div>
				<div class="form-group">
					<label for="municipio">Cidade</label>
					<select class="form-control" name="municipio" id="municipio">
						<option value="">Selecione</option>
						@foreach ($municipios as $municipio)
							@if($realizador->municipio->id == $municipio->id)
							    <option selected value="{{ $municipio->id }}">{{ $municipio->nome }}</option>
							@else
							    <option value="{{ $municipio->id }}">{{ $municipio->nome }}</option>
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