@extends('layouts.master')

@section('title')
    Planos para Mapa - Mapa Mensuração Público(s)
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <ul class="list-unstyled">
                @foreach($planos as $plano)
                    <li>
                        <h2>
                            <a href="{{route('relatorio-mensal',["id" => $plano->id])}}" >
                                {{$plano->nome}}
                            </a>
                        </h2>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection