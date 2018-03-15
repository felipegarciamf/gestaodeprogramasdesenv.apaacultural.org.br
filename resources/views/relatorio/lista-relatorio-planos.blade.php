@extends('layouts.master')

@section('title')
Listagem de Relatórios
@endsection

@section('content')



<form class="navbar-form navbar-left" role="search">
    <div class="form-group">
        {{ Form::open(['route' => 'lista-relatorio', 'method' => 'GET', 'class' => 'navbar-form navbar-left pull-right', 'role' => 'search']) }}
        <div class="form-group">
        {{ Form::text('programa', null, ['class' => 'form-control', 'placeholder' => 'Nome do Programa']) }}
        </div>
 </div>
  <button type="submit" class="btn btn-default">Pesquisar</button>
 </form>

<div class="row">
	<div class="col-md-12 col-sm-12">
		<div class="table table-resposive">
			<table class="table table-striped table-bordered">
				<tbody>
					<thead>
						<tr>
							<th>Programa</th>
							<th>Ação</th>
							<th>Indicador de Resultado</th>
							<th>Meta</th>
							<th>1º Trimestre</th>
							<th>Meta</th>
							<th>2º Trimestre</th>
							<th>Meta</th>
							<th>3º Trimestre</th>
							<th>Meta</th>
							<th>4º Trimestre</th>
							<th>Resultado</th>
							<th>ICM Anual</th>
						</tr>
					</thead>

					<!-- Inicio da busca de array --> 
					@foreach($acoes as $acao)
						@foreach($arrayInfo as $linha)
							@if($acao->codigo_acao == $linha["acao"])

					<!-- Inicio da Linha Por cada ação-->
								@foreach($linha["campos"] as $campos)		
									@if(isset($campos["nome"]))
										<tr>
										<td>{{$acao->programa->nome}}</td>
										<td>{{ $acao->nome }}</td>
										<td>{{$campos["nome"]}}</td>
										<td>{{ $campos["meta_1_tri"] }}</td>
										<td>{{ $campos["alcancado_1_tri"] }}</td>
										<td>{{ $campos["meta_2_tri"] }}</td>
										<td>{{ $campos["alcancado_2_tri"] }}</td>
										<td>{{ $campos["meta_3_tri"] }}</td>
										<td>{{ $campos["alcancado_3_tri"] }}</td>
										<td>{{ $campos["meta_4_tri"] }}</td>
										<td>{{ $campos["alcancado_4_tri"] }}</td>
										<td>{{ intval($campos["alcancado_1_tri"])+intval($campos["alcancado_2_tri"])+intval($campos["alcancado_3_tri"])+intval($campos["alcancado_4_tri"]) }}</td>
										{{--*/ $cem_porcento_1_tri = intval($campos["meta_1_tri"]) /*--}}
																		@if($cem_porcento_1_tri == 0)
																			
																			{{--*/ $porcentagem_1_tri =  0 /*--}}
																			

																		@else
																			{{--*/ $porcentagem_1_tri =  (intval($campos["alcancado_1_tri"]) * 100) / $cem_porcento_1_tri /*--}} 															
																		@endif
																		

																		{{--*/ $cem_porcento_2_tri = intval($campos["meta_2_tri"]) /*--}} 															
																		@if($cem_porcento_2_tri == 0)
																			{{--*/ $porcentagem_2_tri =  0 /*--}}
																																		
																		@else
																			{{--*/ $porcentagem_2_tri =  (intval($campos["alcancado_2_tri"]) * 100) / $cem_porcento_2_tri /*--}}
																																		
																		@endif
																		

																		{{--*/ $cem_porcento_3_tri = intval($campos["meta_3_tri"]) /*--}}
																		@if($cem_porcento_3_tri == 0)
																			{{--*/ $porcentagem_3_tri =  0 /*--}}
																		@else
																			{{--*/ $porcentagem_3_tri =  (intval($campos["alcancado_3_tri"]) * 100) / $cem_porcento_3_tri /*--}}
																		@endif
																		

																		{{--*/ $cem_porcento_4_tri = intval($campos["meta_4_tri"]) /*--}}
																		@if($cem_porcento_4_tri == 0)
																			{{--*/ $porcentagem_4_tri =  0 /*--}}
																		@else
																			{{--*/ $porcentagem_4_tri =  (intval($campos["alcancado_4_tri"]) * 100) / $cem_porcento_4_tri /*--}}
																		@endif
																		@if(($acao->codigo_acao)== 5 && $campos["nome"] == "Número Total de Público Circulante" || ($acao->codigo_acao)== 12 && $campos["nome"] == "Número de Atividades")
																		@endif
										<td>{{ round(($porcentagem_1_tri+$porcentagem_2_tri+$porcentagem_3_tri+$porcentagem_4_tri) / 4,2) }}%</td>
									@endif
								@endforeach
								</tr>
					<!-- Fim da linha por cada ação -->
							@else
								@foreach($linha["campos"] as $campos)									
										@if(isset($campos["nome"]))
											<!-- Inicio de linha por cada indicador de cada ação -->
										


										@endif
								@endforeach
							@endif
						@endforeach
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
	<!-- Spacer para o fim da página-->
@endsection


