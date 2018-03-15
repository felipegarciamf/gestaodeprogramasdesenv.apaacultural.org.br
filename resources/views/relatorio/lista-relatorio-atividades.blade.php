@extends('layouts.master')

@section('title')
    Planos para Lista Trimestral de Atividades
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <ul class="list-unstyled">
                @foreach($planos as $plano)
                    <li>
                        <h2>
                            <a href="{{route('listar-relatorio-programas-atividades',["id" => $plano->id])}}" >
                                {{$plano->nome}}
                            </a>
                        </h2>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection