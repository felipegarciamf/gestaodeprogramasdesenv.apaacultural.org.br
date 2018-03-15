@extends('layouts.master')

@section('title')
    Editar Engajamento do Público
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form action="{{ route('update-engajamento-publico',['id' => $engajamentopublico->id]) }}" name="form-editar-engajamento-publico" id="form-editar-engajamento-publico" method="post">
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" name="nome" class="form-control" value="{{ $engajamentopublico->nome }}">
				</div>
				<button type="submit" class="btn btn-primary">Editar</button>
				<!--Necessário para o laravel-->
				<input type="hidden" name="_token" value="{{ Session::token() }}">
			</form>
		</div>
	</div>
@endsection