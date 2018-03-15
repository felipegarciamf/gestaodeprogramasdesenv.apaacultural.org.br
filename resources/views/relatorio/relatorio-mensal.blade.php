<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{"Mapa - Módulo Mensuração Público(s)"}}</title>
    <link rel="stylesheet" type="text/css" href="{{URL::to('src/css/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('src/css/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('src/css/ionicons/css/ionicons.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('src/css/main.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('src/css/jquery-ui/jquery-ui.min.css')}}">
    <style>
        .spacer-final-pagina
        {
            height:200px;
        }

        .row-container
        {
            border:1px solid #000;
        }
        
        .col-row-container
        {
            border:1px solid #000;
        }

        .row-table-first
        {
            display:table;
        }
        
        .row-table-first-cell
        {
            border:1px solid #000;
            display:table-cell;
            float:none;
            text-align:center;
        }

        .paragrafo-row-table-first-cell
        {
            border:1px solid #000;
            margin: 0px 0px 0px 0px;
            position: absolute;
            top: 0;
            left: 5px;
        }

        .row-table-inside-first
        {
            display:table;
            /*border:1px solid #000;*/
            width:100%;"
        }

        .row-table-inside-first-left
        {
            margin-left:10px;"
        }
        
        .col-titles
        {
            border:1px solid #000;
            height:65px;
        }

        .col-titles-meses
        {
            border:1px solid #000;
            text-align:left;
            height:65px;
        }

        .ul-col-title-meses
        {
            list-style-type:none;
        }
        
        .li-col-title-meses
        {
            display:inline-block;
            margin:2px;
            font-size:10px;
        }

        .col-titles-left
        {
            border:1px solid #000;
            text-align:left;
            height:65px;
        }

        .row-dados
        {
            margin-left:10px;
            border:1px solid #000;
        }

        .input-dados
        {
            width: 85px;
            height: 20px;
            margin-left: -15px;
        }

        .col-dados-meses
        {
            text-align:left;
        }

        .col-dados-meses-ul
        {
            list-style-type:none;
        }

        .col-dados-meses-li
        {
            display:inline-block;
            margin:2px;
            width:18.89px;
            text-align:center;
        }

        .col-dados-subtotal
        {
            text-align:left;
        }

    </style>
</head>
<body>
    <div class="container">
        @foreach($arrayInfo as $acao)
            @if(!empty(array_filter($acao["totais_atividades"])))
                <div class="row row-container">
                    <div class="col-md-12 col-row-container">
                        <div class="row row-table-first">
                            <div class="col-md-1 row-table-first-cell">
                                <p class="paragrafo-row-table-first-cell">
                                    Código Ação
                                </p>
                                {{$acao["acao"]}}
                            </div>
                            <div class="col-md-11 row-table-first-cell">
                                <div class="row row-table-inside-first">
                                    <div class="row row-table-inside-first-left">
                                        <div class="col-md-1 col-titles" >Público</div>
                                        <div class="col-md-1 col-titles" >Tipo Público</div>
                                        <div class="col-md-2 col-titles" >Engajamento Público</div>
                                        <div class="col-md-1 col-titles" >Segmento Público</div>
                                        <div class="col-md-1 col-titles" >Público Previsto (Anual)</div>
                                        <div class="col-md-5 col-titles-meses">
                                            <ul class="ul-col-title-meses">
                                                <li class="li-col-title-meses">
                                                    JAN
                                                </li>
                                                <li class="li-col-title-meses">
                                                    FEV
                                                </li>
                                                <li class="li-col-title-meses">
                                                    MAR
                                                </li>
                                                <li class="li-col-title-meses">
                                                    ABR
                                                </li>
                                                <li class="li-col-title-meses">
                                                    MAI
                                                </li>
                                                <li class="li-col-title-meses">
                                                    JUN
                                                </li>
                                                <li class="li-col-title-meses">
                                                    JUL
                                                </li>
                                                <li class="li-col-title-meses">
                                                    AGO
                                                </li>
                                                <li class="li-col-title-meses">
                                                    SET
                                                </li>
                                                <li class="li-col-title-meses">
                                                    OUT
                                                </li>
                                                <li class="li-col-title-meses">
                                                    NOV
                                                </li>
                                                <li class="li-col-title-meses">
                                                    DEZ
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-1 col-titles-left">
                                            SubTotal
                                        </div>
                                    </div>

                                    @foreach($acao["totais_atividades"] as $totais)
                                        @if(!empty(array_filter($totais)))
                                            <div class="row row-dados">
                                                <div class="col-md-1">
                                                    <input type="text" class="input-dados">
                                                </div>
                                                <div class="col-md-1">
                                                    {{$totais["tipo_publico"]}}
                                                </div>
                                                <div class="col-md-2">
                                                    <p>{{"Agendado: ".$totais["agendado"]}}</p>
                                                    <p>{{"Espontaneo: ".$totais["espontaneo"]}}</p>
                                                </div>
                                                <div class="col-md-1">
                                                    Não se Aplica
                                                </div>
                                                <div class="col-md-1">
                                                    <input type="text" class="input-dados">
                                                </div>
                                                <div class="col-md-5 col-dados-meses">
                                                    <ul class="col-dados-meses-ul">
                                                        <li class="col-dados-meses-li">
                                                            {{$totais["jan"]}}
                                                        </li>
                                                        <li class="col-dados-meses-li">
                                                            {{$totais["fev"]}}
                                                        </li>
                                                        <li class="col-dados-meses-li">
                                                            {{$totais["mar"]}}
                                                        </li>
                                                        <li class="col-dados-meses-li">
                                                            {{$totais["abr"]}}
                                                        </li>
                                                        <li class="col-dados-meses-li">
                                                            {{$totais["mai"]}}
                                                        </li>
                                                        <li class="col-dados-meses-li">
                                                            {{$totais["jun"]}}
                                                        </li>
                                                        <li class="col-dados-meses-li">
                                                            {{$totais["jul"]}}
                                                        </li>
                                                        <li class="col-dados-meses-li">
                                                            {{$totais["ago"]}}
                                                        </li>
                                                        <li class="col-dados-meses-li">
                                                            {{$totais["set"]}}
                                                        </li>
                                                        <li class="col-dados-meses-li">
                                                            {{$totais["out"]}}
                                                        </li>
                                                        <li class="col-dados-meses-li">
                                                            {{$totais["nov"]}}
                                                        </li>
                                                        <li class="col-dados-meses-li">
                                                            {{$totais["dez"]}}
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-1 col-dados-subtotal">
                                                    {{intval($totais["jan"])+intval($totais["fev"])+intval($totais["mar"])+intval($totais["abr"])+intval($totais["mai"])+intval($totais["jun"])+intval($totais["jul"])+intval($totais["ago"])+intval($totais["set"])+intval($totais["out"])+intval($totais["nov"])+intval($totais["dez"])}}
                                                </div>
                                            </div> 
                                        @endif                               
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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

</body>
</html>