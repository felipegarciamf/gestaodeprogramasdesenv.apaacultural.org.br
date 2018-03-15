@extends('layouts.master')

@section('title')
    Editar Região Administrativa
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form action="{{ route('update-regiao-administrativa',['id' => $regiaoadministrativa->id]) }}" name="form-editar-regiao-administrativa" id="form-editar-regiao-administrativa" method="post">
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" name="nome" class="form-control" value="{{ $regiaoadministrativa->nome }}">
				</div>
				<button type="submit" class="btn btn-primary">Editar</button>
				<!--Necessário para o laravel-->
				<input type="hidden" name="_token" value="{{ Session::token() }}">
			</form>
		</div>
	</div>
@endsection