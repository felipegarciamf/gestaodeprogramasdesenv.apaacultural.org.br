@extends('layouts.master')

@section('title')
    Editar Rotina
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form action="{{ route('update-rotina',['id' => $rotina->id]) }}" name="form-editar-rotina" id="form-editar-rotina" method="post">
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" name="nome" value="{{ $rotina->nome }}" class="form-control">
				</div>
				<div class="form-group">
					<label for="perfil">Plano</label>
					<select class="form-control" name="plano" id="plano">
						<option value="">Selecione</option>
						@foreach ($planos as $plano)
							@if($rotina->plano->id == $plano->id)
							    <option selected value="{{ $plano->id }}">{{ $plano->nome }}</option>
							@else
							    <option value="{{ $plano->id }}">{{ $plano->nome }}</option>
							@endif
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="realizada">Realizada?</label>
					@if($rotina->realizada)
						<div class="radio">
						  <label><input type="radio" name="realizada" value="0" >Não</label>
						</div>
						<div class="radio">
						  <label><input type="radio" name="realizada" checked value="1" >Sim</label>
						</div>
					@else
						<div class="radio">
						  <label><input type="radio" name="realizada" checked value="0" >Não</label>
						</div>
						<div class="radio">
						  <label><input type="radio" name="realizada" value="1" >Sim</label>
						</div>
					@endif
				</div>
				<div class="form-group">
					<label for="data_limite">Data Limite</label>
					<input type="text" value="{{ $rotina->data_limite }}" name="data_limite" id="data_limite" readonly>
				</div>
				<button type="submit" class="btn btn-primary">Editar</button>
				<!--Necessário para o laravel-->
				<input type="hidden" name="_token" value="{{ Session::token() }}">
			</form>
		</div>
	</div>
@endsection