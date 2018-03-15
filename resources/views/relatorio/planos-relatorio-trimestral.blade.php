@extends('layouts.master')

@section('title')
    Planos para Relatório Trimestral de Atividades
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <ul class="list-unstyled">
            <div class="container">
                @foreach($planos as $plano)
                    <li>
                        <h2>
                            <a href="{{route('relatorio-trimestral',["id" => $plano->id])}}" >
                                {{$plano->nome}}
                            </a>
                        </h2>
                    </li>
                @endforeach
                </div>
            </ul>
        </div>
    </div>
@endsection