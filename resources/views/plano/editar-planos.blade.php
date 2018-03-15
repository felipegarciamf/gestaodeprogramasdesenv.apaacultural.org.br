@extends('layouts.master')

@section('title')
    Editar Plano
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form action="{{ route('update-plano',['id' => $plano->id]) }}" name="form-editar-plano" id="form-editar-plano" method="post">
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" name="nome" value="{{ $plano->nome }}" class="form-control">
				</div>
				<div class="form-group">
					<label for="perfil">Uge</label>
					<select class="form-control" name="uge" id="uge">
						<option value="">Selecione</option>
						@foreach ($uges as $uge)
							@if($plano->uge->id == $uge->id)
							    <option selected value="{{ $uge->id }}">{{ $uge->nome }}</option>
							@else
							    <option value="{{ $uge->id }}">{{ $uge->nome }}</option>
							@endif
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="perfil">Cg</label>
					<select class="form-control" name="cg" id="cg">
						<option value="">Selecione</option>
						@foreach ($cgs as $cg)
							@if($plano->cg->id == $cg->id)
							    <option selected value="{{ $cg->id }}">{{ $cg->nome }}</option>
							@else
							    <option value="{{ $cg->id }}">{{ $cg->nome }}</option>
							@endif
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="perfil">Objeto</label>
					<select class="form-control" name="objeto" id="objeto">
						<option value="">Selecione</option>
						@foreach ($objetos as $objeto)
							@if($plano->objeto->id == $objeto->id)
							    <option selected value="{{ $objeto->id }}">{{ $objeto->nome }}</option>
							@else
							    <option value="{{ $objeto->id }}">{{ $objeto->nome }}</option>
							@endif
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="perfil">Os</label>
					<select class="form-control" name="os" id="os">
						<option value="">Selecione</option>
						@foreach ($oss as $os)
							@if($plano->os->id == $os->id)
							    <option selected value="{{ $os->id }}">{{ $os->nome }}</option>
							@else
							    <option value="{{ $os->id }}">{{ $os->nome }}</option>
							@endif
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="perfil">Tipo Objeto</label>
					<select class="form-control" name="tipo_objeto" id="tipo_objeto">
						<option value="">Selecione</option>
						@foreach ($tipoobjetos as $tipoobjeto)
							@if($plano->tipo_objeto->id == $tipoobjeto->id)
							    <option selected value="{{ $tipoobjeto->id }}">{{ $tipoobjeto->nome }}</option>
							@else
							    <option value="{{ $tipoobjeto->id }}">{{ $tipoobjeto->nome }}</option>
							@endif
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="data_limite">Data Limite</label>
					<input type="text" value="{{ $plano->data_limite }}" name="data_limite" id="data_limite" readonly>
				</div>

				<div class="form-group">
				<fieldset>
					<legend>Deseja Replicar algum Plano?</legend>
					<label>
						<input type="radio" name="replicar-plano" id="replicar-plano-sim" data-id="sim" class="replicar" value="sim">
						Sim
					</label>
					<label>
						<input type="radio" name="replicar-plano" checked="checked" class="replicar" id="replicar-plano-nao" data-id="nao" value="sim">
						Não
					</label>
				</fieldset>
				</div>

				<div class="form-group" id="replicar-plano" style="display:none">
					<label for="plano-anterior">Replicar Plano</label>
					<select class="form-control" name="plano-anterior" id="plano-anterior">
					<option value="">Selecione</option>
					@foreach ($planos as $plano)
					<option value="{{ $plano->id }}">{{$plano->nome}}</option>	
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