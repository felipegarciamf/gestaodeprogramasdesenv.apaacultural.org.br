@extends('layouts.master')

@section('title')
    Programas
@endsection

@section('content')

<!-- Filtro de busca por nome de programa -->
<form class="navbar-form navbar-left" role="search">
    <div class="form-group">
        {!! Form::open(['route' => 'listar-programas', 'method' => 'GET', 'class' => 'navbar-form navbar-left pull-right', 'role' => 'search']) !!}
        <div class="form-group">
        {!! Form::text('nome', null, ['class' => 'form-control', 'placeholder' => 'Nome do Programa']) !!}
        </div>
 </div>
 <button type="submit" class="btn btn-default">Pesquisar</button>
</form>

<!-- Query de programas listados no plano -->
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <ul class="list-unstyled">
            
                @foreach($programas as $programa)
                    <li>
                        <h2>
                            <a href="{{ route('listar-atividades-por-programa',['id' => $programa->id])}}" >
                                {{$programa->nome}}
                            </a>
                        </h2>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection