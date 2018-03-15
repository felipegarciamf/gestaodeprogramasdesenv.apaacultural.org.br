@extends('layouts.master')

@section('title')
    Editar Tipo de Objeto
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form action="{{ route('update-tipo-objeto',['id' => $tipoobjeto->id]) }}" name="form-editar-tipo-objeto" id="form-editar-tipo-objeto" method="post">
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" name="nome" class="form-control" value="{{ $tipoobjeto->nome }}">
				</div>
				<button type="submit" class="btn btn-primary">Editar</button>
				<!--Necessário para o laravel-->
				<input type="hidden" name="_token" value="{{ Session::token() }}">
			</form>
		</div>
	</div>
@endsection