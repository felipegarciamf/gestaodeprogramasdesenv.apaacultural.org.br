<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{"Relatório Trimestral de Atividades"}}</title>
    <link rel="stylesheet" type="text/css" href="{{URL::to('src/css/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('src/css/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('src/css/ionicons/css/ionicons.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('src/css/main.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('src/css/jquery-ui/jquery-ui.min.css')}}">
<style media="print">
	.botao{
		display:none;
	}
</style>

    <style>
    	
		.row-table-master
		{
			border:1px solid black;
			display: table;
			width:100%;
		}

		.col-table-master-cell
		{
			border:1px solid black;
			float: none;
			display: table-cell;
			text-align:center;
		}

		.col-table-master-cell-without-text-center
		{
			border:1px solid black;
			float: none;
			display: table-cell;
		}
		
		.indicadores-container-row
		{
			border:1px solid black;
			padding:15px;
		}

		.row-table-indicadores
		{
			display:table;
			border:1px solid #000;
		}

		.row-table-justificativa
		{
			display:table;
			border:1px solid #000;
			width:100%;
		}

		.col-table-indicadores-cell
		{
			/*border:1px solid brown;*/
			display:table-cell;
			float:none;
		}

		.spacer-final-pagina
		{
			height:200px;
		}

		.campo-aberto-input
		{
			width:50px;
			height:20px;
		}
		.row{
			page-break-inside:avoid;
			margin-left: 2px;
		}

    </style>
</head>
<body>

	<div class="container">

		@foreach($acoes as $acao)

			@foreach($arrayInfo as $linha)
				@if($acao->codigo_acao == $linha["acao"])
					<!-- Inicio da Linha Por cada ação-->

					<!-- condição de aparecer somente uma vez (ainda a corrigir) -->

					<div class="row">
						<div class="col-md-12">
							<h1 class="page-header">Programa {{$acao->programa->nome}}</h1>
						</div>
				



					<div class="row-table-master">
						<div class="col-md-1 col-table-master-cell">
							<div style="border:1px solid #000;width:100%;">
								<b>Número da Ação</b>
							</div>
							<p>{{ $acao->codigo_acao }}</p>
						</div>
						<div class="col-md-3 col-table-master-cell">
							<div style="border:1px solid #000;width:100%;">
								<b>Ação</b>
							</div>
							<p>{{ $acao->nome }}</p>
						</div>
						<div class="col-md-8 col-table-master-cell-without-text-center">
							@if($linha["existe_campo_aberto"] && sizeof($linha["campos"]) > 1)
								@foreach($linha["campos"] as $campos)									
										@if(isset($campos["nome"]))
											<!-- Inicio de linha por cada indicador de cada ação -->
											<div class="row indicadores-container-row">
												<div class="col-md-12 row text-center row-table-indicadores">
													<div class="col-md-6 col-table-indicadores-cell">
														<div style="border:1px solid #000;width:100%;">
															<b>Indicador de Resultados</b>
														</div>
														<div style="/*border:1px solid #000;*/width:100%;">
															<p>{{$campos["nome"]}}</p>
														</div>
													</div>
													<div class="col-md-2 col-table-indicadores-cell">
														<div style="border-left: 1px solid #000;/*border:1px solid #000;*/width:100%;">
															<b>Período</b>
															<p>1º Trim</p>
															<p>2º Trim</p>
															<p>3º Trim</p>
															<p>4º Trim</p>
															<b>Anual</b>
														</div>
													</div>
													<div class="col-md-2 col-table-indicadores-cell">
														<div style="border-left: 1px solid #000;/*border:1px solid #000;*/width:100%;">
															<b>Meta</b>
															<p>{{ $campos["meta_1_tri"] }}</p>
															<p>{{ $campos["meta_2_tri"] }}</p>
															<p>{{ $campos["meta_3_tri"] }}</p>
															<p>{{ $campos["meta_4_tri"] }}</p>
															<b class="metaanual">{{ intval($campos["meta_1_tri"])+intval($campos["meta_2_tri"])+intval($campos["meta_3_tri"])+intval($campos["meta_4_tri"]) }}</b>
														</div>
													</div>
													<div class="col-md-2 col-table-indicadores-cell">
														<div style="border-left: 1px solid #000;/*border:1px solid #000;*/width:100%;">
														
														<?php if(($acao->codigo_acao)== 5) var_dump($acao->codigo_acao); ?>
														
															<b>Realizado</b>
															<p><input class="campo-aberto-input" type="text"></p>
															<p><input class="campo-aberto-input" type="text"></p>
															<p><input class="campo-aberto-input" type="text"></p>
															<p><input class="campo-aberto-input" type="text"></p>
															<b><input class="campo-aberto-input" type="text"></b>
														</div>
													</div>
												</div>
												<div class="col-md-12 row text-center row-table-indicadores">
													<div class="col-md-6 col-table-indicadores-cell">
														
													</div>
													<div class="col-md-3 col-table-indicadores-cell">
														<div style="border-left: 1px solid #000;/*border:1px solid #000;*/width:100%;">
															<p>ICM 1º Trim</p>
															<p>ICM 2º Trim</p>
															<p>ICM 3º Trim</p>
															<p>ICM 4º Trim</p>
															<b>ICM ANUAL</b>
														</div>
													</div>
													<div class="col-md-3 col-table-indicadores-cell">
														<div style="border-left: 1px solid #000;/*border:1px solid #000;*/width:100%;">														
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
															<p><input class="campo-aberto-input" type="text">%</p>
															<p><input class="campo-aberto-input" type="text">%</p>
															<p><input class="campo-aberto-input" type="text">%</p>
															<p><input class="campo-aberto-input" type="text">%</p>
															<b><input class="campo-aberto-input" type="text">%</b>
														</div>
													</div>
												</div>
					<form action="{{ route('update-programa',['id' => $programa->id]) }}" name="form-editar-programa" id="form-editar-programa" method="post">
						<div class="form-group">
							<label for="descricao">Descrição</label>
							<textarea name="descricao" class="form-control" id="descricao" required>{{ $programa->descricao }}</textarea>
						</div>

						<button type="submit" class="btn btn-primary">Editar</button>
						<!--Necessário para o laravel-->
						<input type="hidden" name="_token" value="{{ Session::token() }}">
					</form>
											</div>
											<!-- Fim de linha por cada indicador de cada ação -->
										@endif
								@endforeach
							@else
								@foreach($linha["campos"] as $campos)									
										@if(isset($campos["nome"]))
											<!-- Inicio de linha por cada indicador de cada ação -->
											<div class="row indicadores-container-row">
												<div class="col-md-12 row text-center row-table-indicadores">
													<div class="col-md-6 col-table-indicadores-cell">
														<div style="border:1px solid #000;width:100%;">
															<b>Indicador de Resultados</b>
														</div>
														<div style="/*border:1px solid #000;*/width:100%;">
															<p>{{$campos["nome"]}}</p>
														</div>
													</div>
													<div class="col-md-2 col-table-indicadores-cell">
														<div style="border-left: 1px solid #000;/*border:1px solid #000;*/width:100%;">
															<b>Período</b>
															<p>1º Trim</p>
															<p>2º Trim</p>
															<p>3º Trim</p>
															<p>4º Trim</p>
															<b>Anual</b>
														</div>
													</div>
													<div class="col-md-2 col-table-indicadores-cell">
														<div style="border-left: 1px solid #000;/*border:1px solid #000;*/width:100%;">
															<b>Meta</b>
															<p>{{ $campos["meta_1_tri"] }}</p>
															<p>{{ $campos["meta_2_tri"] }}</p>
															<p>{{ $campos["meta_3_tri"] }}</p>
															<p>{{ $campos["meta_4_tri"] }}</p>
															<b class="metaanual">{{ intval($campos["meta_1_tri"])+intval($campos["meta_2_tri"])+intval($campos["meta_3_tri"])+intval($campos["meta_4_tri"]) }}</b>
														</div>
													</div>
													<div class="col-md-2 col-table-indicadores-cell">
														<div style="border-left: 1px solid #000;/*border:1px solid #000;*/width:100%;">
														<b>Realizado</b>
														
														<?php if(($acao->codigo_acao) == 5  && $campos["nome"] == "Número Total de Público Circulante" || ($acao->codigo_acao) == 12  && $campos["nome"] == "Número de Atividades")   
														{  ?>
															
															
															<p><input class="campo-aberto-input" type="text"></p>
															<p><input class="campo-aberto-input" type="text"></p>
															<p><input class="campo-aberto-input" type="text"></p>
															<p><input class="campo-aberto-input" type="text"></p>
															<b><input class="campo-aberto-input" type="text"></b>
															<?php } else{ ?>
															 
															 
															<p class="realizado1">{{ $campos["alcancado_1_tri"] }}</p>
															<p class="realizado2">{{ $campos["alcancado_2_tri"] }}</p>
															<p class="realizado3">{{ $campos["alcancado_3_tri"] }}</p>
															<p class="realizado4">{{ $campos["alcancado_4_tri"] }}</p>
															<b class="realizado5">{{ intval($campos["alcancado_1_tri"])+intval($campos["alcancado_2_tri"])+intval($campos["alcancado_3_tri"])+intval($campos["alcancado_4_tri"]) }}</b> <?php } ?>
															 
															 
														</div>
													</div>
												</div>
												<div class="col-md-12 row text-center row-table-indicadores">
													<div class="col-md-6 col-table-indicadores-cell">
														
													</div>
													<div class="col-md-3 col-table-indicadores-cell">
														<div style="border-left: 1px solid #000;/*border:1px solid #000;*/width:100%;">
															<p>ICM 1º Trim</p>
															<p>ICM 2º Trim</p>
															<p>ICM 3º Trim</p>
															<p>ICM 4º Trim</p>
															<b>ICM ANUAL</b>
														</div>
													</div>
													<div class="col-md-3 col-table-indicadores-cell">
														<div style="border-left: 1px solid #000;/*border:1px solid #000;*/width:100%;">
														
														
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
															<p><input class="campo-aberto-input" type="text">%</p>
															<p><input class="campo-aberto-input" type="text">%</p>
															<p><input class="campo-aberto-input" type="text">%</p>
															<p><input class="campo-aberto-input" type="text">%</p>
															<b><input class="campo-aberto-input" type="text">%</b>
														@else
															<p>{{round($porcentagem_1_tri,2)}}%</p>
															<p>{{round($porcentagem_2_tri,2)}}%</p>
															<p>{{round($porcentagem_3_tri,2)}}%</p>
															<p>{{round($porcentagem_4_tri,2)}}%</p>
															<b>{{ round(($porcentagem_1_tri+$porcentagem_2_tri+$porcentagem_3_tri+$porcentagem_4_tri) / 4,2) }}%</b>
														@endif
														</div>
													</div>
												</div>
												{{--*/ $justificativa = round(($porcentagem_1_tri+$porcentagem_2_tri+$porcentagem_3_tri+$porcentagem_4_tri) / 4,2)/*--}}
				


													<div class="form-group">	
														<label for="justificativa">Justificativa</label>
														<textarea disabled type="text" name="justificativa" id="justificativa" class="form-control" style="overflow: hidden; height:300px; resize: none;"" maxlength="1000" >{{ $campos['justificativa'] }}

														</textarea>
													</div>	
													<ul class="list-inline botao">
														<li>
															<a href="{{ route('editar-indicador-justificativa',['id' =>  $campos['indicador_id']]) }}" class="btn btn-warning" target="_blank">Editar</a>
														</li>
													</ul>

													<div class="form-group">	
														<label for="justificativa2">Justificativa 2</label>
														<textarea disabled type="text" style="overflow: hidden; height:300px; resize: none;" name="justificativa2" id="justificativa2" class="form-control" maxlength="1000">{{ $campos['justificativa2'] }}
														</textarea>
													</div>	
													<ul class="list-inline botao">
														<li>
															<a href="{{ route('editar-indicador-justificativa2',['id' =>  $campos['indicador_id']]) }}" class="btn btn-warning" target="_blank">Editar</a>
														</li>
													</ul>
											</div>


										@endif
								@endforeach

							@endif
						</div>
					</div>
					</div>
					<!-- Fim da linha por cada ação -->
				@endif
			@endforeach
		@endforeach
		
		<!-- Spacer para o fim da página-->
		<div class="row">
			<div class="col-md-12 spacer-final-pagina">
				
			</div>
		</div>
	</div>

	<!-- Scripts -->

	<script src="{{URL::to('src/js/jquery.min.js')}}"></script>
	<script src="{{URL::to('src/css/bootstrap/js/bootstrap.min.js')}}"></script>
	<script src="{{URL::to('src/js/app.js')}}"></script>
	<script src="{{URL::to('src/js/jquery-ui.min.js')}}"></script>

	<script type="text/javascript">
	$(document).ready(function(){


	});
	</script>


</body>
</html>

