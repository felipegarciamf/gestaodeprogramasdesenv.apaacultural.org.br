@extends('layouts.master')

@section('title')
    Cadastro de Objeto
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form class="form" name="form-cadastro-objeto" id="form-cadastro-objeto" action="{{ route('cria-objeto') }}" method="POST">
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" name="nome" class="form-control">
				</div>
				<button type="submit" class="btn btn-primary">Cadastrar</button>
				<!--Necessário para o laravel SILAS-->
				<input type="hidden" name="_token" value="{{ Session::token() }}">
			</form>
		</div>
	</div>
@endsection