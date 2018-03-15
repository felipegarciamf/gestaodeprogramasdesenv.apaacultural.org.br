@extends('layouts.master')

@section('title')
    Editar Tipo Público
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form action="{{ route('update-tipo-publico',['id' => $tipopublico->id]) }}" name="form-editar-tipo-publico" id="form-editar-tipo-publico" method="post">
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" name="nome" class="form-control" value="{{ $tipopublico->nome }}">
				</div>
				<button type="submit" class="btn btn-primary">Editar</button>
				<!--Necessário para o laravel-->
				<input type="hidden" name="_token" value="{{ Session::token() }}">
			</form>
		</div>
	</div>
@endsection