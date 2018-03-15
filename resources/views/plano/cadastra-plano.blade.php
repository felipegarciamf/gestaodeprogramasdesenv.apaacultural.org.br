@extends('layouts.master')

@section('title')
    Cadastro de Plano
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form class="form" name="form-cadastro-plano" id="form-cadastro-plano" action="{{ route('cria-plano') }}" method="POST">
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" name="nome" placeholder="Digite um Plano" class="form-control">
				</div>
				<div class="form-group">
					<label for="uge">Uge</label>
					<select class="form-control" name="uge" id="uge">
						<option value="">Selecione</option>
						@foreach ($uges as $uge)
						    <option value="{{ $uge->id }}">{{ $uge->nome }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="cg">Cg</label>
					<select class="form-control" name="cg" id="cg">
						<option value="">Selecione</option>
						@foreach ($cgs as $cg)
						    <option value="{{ $cg->id }}">{{ $cg->nome }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="objeto">Objeto</label>
					<select class="form-control" name="objeto" id="objeto">
						<option value="">Selecione</option>
						@foreach ($objetos as $objeto)
						    <option value="{{ $objeto->id }}">{{ $objeto->nome }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="os">Os</label>
					<select class="form-control" name="os" id="os">
						<option value="">Selecione</option>
						@foreach ($oss as $os)
						    <option value="{{ $os->id }}">{{ $os->nome }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="tipo_objeto">Tipo Objeto</label>
					<select class="form-control" name="tipo_objeto" id="tipo_objeto">
						<option value="">Selecione</option>
						@foreach ($tipoobjetos as $tipoobjeto)
						    <option value="{{ $tipoobjeto->id }}">{{ $tipoobjeto->nome }}</option>
						@endforeach
					</select>
				</div>

				<div class="form-group">
					<label for="data_limite">Data Limite</label>
					<input type="text" name="data_limite" value="00/00/0000" id="data_limite" readonly>
				</div>

				<button type="submit" class="btn btn-primary">Cadastrar</button>
				<!--Necessário para o laravel-->
				<input type="hidden" name="_token" value="{{ Session::token() }}">
			</form>
		</div>
	</div>
@endsection