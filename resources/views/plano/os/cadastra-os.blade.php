@extends('layouts.master')

@section('title')
    Cadastro de Os
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form class="form" name="form-cadastro-os" id="form-cadastro-os" action="{{ route('cria-os') }}" method="POST">
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" name="nome" class="form-control">
				</div>
				<button type="submit" class="btn btn-primary">Cadastrar</button>
				<input type="hidden" name="_token" value="{{ Session::token() }}">
			</form>
		</div>
	</div>
@endsection