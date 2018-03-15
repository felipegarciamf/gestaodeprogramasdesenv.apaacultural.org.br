@extends('layouts.master')

@section('title')
    Editar Regra
@endsection

@section('content')
<div class="teste"></div>
<div class="row">
		<div class="col-md-6 col-sm-8">
			<form class="form" name="form-editar-regra" id="form-editar-regra" action="{{ route('update-regra',['id' => $regra->id]) }}" method="POST">
				<div class="form-group">
					<label for="codigo_regra">Código da Regra</label>
					<input type="text" name="codigo_regra" id="codigo_regra" value="{{ $regra->codigo }}" class="form-control">
				</div>
				<div class="form-group">
					<label for="descricao">Descricao</label>
					<input type="text" name="descricao" value="{{ $regra->descricao }}" class="form-control">
				</div>
				<div class="form-group">
					<label for="plano">Plano</label>
					<select class="form-control" name="plano" id="plano">
						<option value="">Selecione</option>
						@foreach ($planos as $plano)
							@if($regra->plano->id == $plano->id)
							    <option selected value="{{ $plano->id }}">{{ $plano->nome }}</option>
							@else
							    <option value="{{ $plano->id }}">{{ $plano->nome }}</option>
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