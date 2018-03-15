@extends('layouts.master')

@section('title')
    Editar Atividade
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 col-sm-8">
			<form action="{{ route('update-atividade',['id' => $atividade->id]) }}" name="form-editar-atividade" id="form-editar-atividade" method="post">
				<div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" name="nome" id="nome" value="{{ $atividade->nome }}" class="form-control">
				</div>
				<div class="form-group">
					<label for="data">Data</label>
					<input type="text" name="data" id="data" value="{{ $atividade->data }}" class="form-control" readonly>
				</div>
				<div class="form-group">
					<label for="horario">Horario</label>
					<input name="horario" id="horario" value="{{ $atividade->horario }}" class="form-control" readonly>
				</div>
				<div class="form-group">
					<label for="dia_semana">Dia da Semana</label>
					<select class="form-control" name="dia_semana" id="dia_semana">
					    <option  value="">Selecione</option>
					    <option {{ $atividade->dia_semana == 0 ? 'selected' : '' }} value="0">Segunda-Feira</option>
					    <option {{ $atividade->dia_semana == 1 ? 'selected' : '' }} value="1">Terça-Feira</option>
					    <option {{ $atividade->dia_semana == 2 ? 'selected' : '' }} value="2">Quarta-Feira</option>
					    <option {{ $atividade->dia_semana == 3 ? 'selected' : '' }} value="3">Quinta-Feira</option>
					    <option {{ $atividade->dia_semana == 4 ? 'selected' : '' }} value="4">Sexta-Feira</option>
					    <option {{ $atividade->dia_semana == 5 ? 'selected' : '' }} value="5">Sábado</option>
					    <option {{ $atividade->dia_semana == 6 ? 'selected' : '' }} value="6">Domingo</option>
					</select>
				</div>
				<div class="form-group">
					<label for="data_fim">Data Fim</label>
					<input type="text" name="data_fim" id="data_fim" class="form-control" value="{{$atividade->data_fim}}" readonly>
				</div>
				<div class="form-group">
					<label for="local">Local</label>
					<input type="text" name="local" id="local" class="form-control" value="{{$atividade->local}}">
				</div>
				<div class="form-group">
					<label for="capacidade">Capacidade</label>
					<input type="number" min="0" name="capacidade" id="capacidade" class="form-control" value="{{$atividade->capacidade}}">
				</div>
				<div class="form-group">
					<label for="num_total_pessoas">Nº Total de Pessoas</label>
					<input type="number" min="0" name="num_total_pessoas" id="num_total_pessoas" class="form-control" value="{{$atividade->num_total_pessoas}}">
				</div>
				<div class="form-group">
					<label for="num_total_tecnicos">Nº Total de Técnicos</label>
					<input type="number" min="0" name="num_total_tecnicos" id="num_total_tecnicos" class="form-control" value="{{$atividade->num_total_tecnicos}}">
				</div>
				<div class="form-group">
					<label for="num_total_artistas">Nº Total de Artistas</label>
					<input type="number" min="0" name="num_total_artistas" id="num_total_artistas" class="form-control" value="{{$atividade->num_total_artistas}}">
				</div>
				<div class="form-group">
					<label for="inteiras">Inteiras</label>
					<input type="number" min="0" name="inteiras" id="inteiras" class="form-control" value="{{$atividade->inteira}}">
				</div>
				<div class="form-group">
					<label for="meias">Meias</label>
					<input type="number" min="0" name="meias" id="meias" class="form-control" value="{{$atividade->meia}}">
				</div>
				<div class="form-group">
					<label for="moradores_entorno">Moradores do Entorno</label>
					<input type="number" min="0" name="moradores_entorno" id="moradores_entorno" class="form-control" value="{{$atividade->morador_entorno}}">
				</div>
				<div class="form-group">
					<label for="prom">Prom</label>
					<input type="number" min="0" name="prom" id="prom" class="form-control" value="{{$atividade->prom}}">
				</div>
				<div class="form-group">
					<label for="total_pagantes">Total Pagantes</label>
					<input type="number" min="0" name="total_pagantes" id="total_pagantes" class="form-control" value="{{$atividade->total_pagantes}}">
				</div>
				<div class="form-group">
					<label for="convites_prod">Convites Produção</label>
					<input type="number" min="0" name="convites_prod" id="convites_prod" class="form-control" value="{{$atividade->convite_prod}}">
				</div>
				<div class="form-group">
					<label for="convites_apaa">Convites Apaa</label>
					<input type="number" min="0" name="convites_apaa" id="convites_apaa" class="form-control" value="{{$atividade->convite_apaa}}">
				</div>
				<div class="form-group">
					<label for="educativo_producao">Educativo Produção</label>
					<input type="number" min="0" name="educativo_producao" id="educativo_producao" class="form-control" value="{{$atividade->educativo_producao}}">
				</div>
				<div class="form-group">
					<label for="educativo_apaa">Educativo Apaa</label>
					<input type="number" min="0" name="educativo_apaa" id="educativo_apaa" class="form-control" value="{{$atividade->educativo_apaa}}">
				</div>
				<div class="form-group">
					<label for="atend_social_producao">Atendimento Social Produção</label>
					<input type="number" min="0" name="atend_social_producao" id="atend_social_producao" class="form-control" value="{{$atividade->atend_social_producao}}">
				</div>
				<div class="form-group">
					<label for="atend_social_apaa">Atendimento Social Apaa</label>
					<input type="number" min="0" name="atend_social_apaa" id="atend_social_apaa" class="form-control" value="{{$atividade->atend_social_apaa}}">
				</div>
				<div class="form-group">
					<label for="sessao_acessivel">Sessão Acessível</label>
					<select class="form-control" name="sessao_acessivel" id="sessao_acessivel">
					    <option  value="">Selecione</option>
					    <option {{ $atividade->sessao_acessivel == false ? 'selected' : '' }} value="0">Não</option>
					    <option {{ $atividade->sessao_acessivel == true ? 'selected' : '' }} value="1">Sim</option>
					</select>
				</div>
				<div class="form-group">
					<label for="acessibilidade_acompanhante">Acessibilidade Acompanhante</label>
					<select class="form-control" name="acessibilidade_acompanhante" id="acessibilidade_acompanhante">
					    <option  value="">Selecione</option>
					    <option {{ $atividade->acessibilidade_acompanhante == false ? 'selected' : '' }} value="0">Não</option>
					    <option {{ $atividade->acessibilidade_acompanhante == true ? 'selected' : '' }} value="1">Sim</option>
					</select>
				</div>
				<div class="form-group">
					<label for="bilheteria">Bilheteria</label>
					<input type="number" min="0" name="bilheteria" id="bilheteria" class="form-control" value="{{$atividade->bilheteria}}">
				</div>
				<div class="form-group">
					<label for="porcent_bilheteria_apaa">% Bilheteria</label>
					<input type="number" min="0" name="porcent_bilheteria_apaa" id="porcent_bilheteria_apaa" class="form-control" value="{{$atividade->porcent_bilheteria_apaa}}">
				</div>
				<div class="form-group">
					<label for="artista">Artísta/Banda/Apresentação</label>
					<input type="text" name="artista" id="artista" class="form-control" value="{{$atividade->artista}}">
				</div>
				<div class="form-group">
					<label for="tipo_publico">Tipo Público</label>
					<select class="form-control" name="tipo_publico" id="tipo_publico">
						<option value="">Selecione</option>
						@foreach ($tipos_publico as $tipo_publico)
							@if($atividade->tipo_publico->id == $tipo_publico->id)
							    <option selected value="{{ $tipo_publico->id }}">{{ $tipo_publico->nome }}</option>
							@else
							    <option value="{{ $tipo_publico->id }}">{{ $tipo_publico->nome }}</option>
							@endif
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="realizador">Realizador</label>
					<select class="form-control" name="realizador" id="realizador">
						<option value="">Selecione</option>
						@foreach ($realizadores as $realizador)
							@if($atividade->realizador->id == $realizador->id)
							    <option selected value="{{ $realizador->id }}">{{ $realizador->nome }}</option>
							@else
							    <option value="{{ $realizador->id }}">{{ $realizador->nome }}</option>
							@endif
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="linguagem">Linguagem</label>
					<select class="form-control" name="linguagem" id="linguagem">
						<option value="">Selecione</option>
						@foreach ($linguagens_programa as $linguagem)
							@if($atividade->linguagem_programa->id == $linguagem->id)
							    <option selected value="{{ $linguagem->id }}">{{ $linguagem->nome }}</option>
							@else
							    <option value="{{ $linguagem->id }}">{{ $linguagem->nome }}</option>
							@endif
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="municipio">Município</label>
					<select class="form-control" name="municipio" id="municipio">
						<option value="">Selecione</option>
						@foreach ($municipios as $municipio)
							@if($atividade->municipio->id == $municipio->id)
							    <option selected value="{{ $municipio->id }}">{{ $municipio->nome }}</option>
							@else
							    <option value="{{ $municipio->id }}">{{ $municipio->nome }}</option>
							@endif
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="tipo_evento">Tipo Evento</label>
					<select class="form-control" name="tipo_evento" id="tipo_evento">
						<option value="">Selecione</option>
						@foreach ($tipos_publico as $tipo_publico)
							@if($atividade->tipo_publico->id == $tipo_publico->id)
							    <option selected value="{{ $tipo_publico->id }}">{{ $tipo_publico->nome }}</option>
							@else
							    <option value="{{ $tipo_publico->id }}">{{ $tipo_publico->nome }}</option>
							@endif
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="observacoes">Observações</label>
					<textarea name="observacoes" id="observacoes" class="form-control">{{ $atividade->observacoes }}</textarea>
				</div>
				<button type="submit" class="btn btn-primary">Editar</button>
				<!--Necessário para o laravel-->
				<input type="hidden" name="_token" value="{{ Session::token() }}">
			</form>
		</div>
	</div>
@endsection