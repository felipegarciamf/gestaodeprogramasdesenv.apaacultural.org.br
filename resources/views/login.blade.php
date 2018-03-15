@extends('layouts.login')

@section('title')
    Login
@endsection

@section('content')
	<div class="row">
		<div class="margin-spacer-top-100"></div>
	</div>
    <div class="row">
        <div class="col-md-6 center-block col-sm-8" style="float:none;">
            <img class="center-block" style="margin-bottom:50px;" src="{{URL::to('src/img/logo.jpg')}}" alt="">
        </div>
    </div>
    <div class="row">
        @if(Session::has('error_logado'))
            <div class="col-md-5 col-sm-7 center-block" style="float:none;">
                <div class="alert alert-danger alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <strong>Ops!</strong> acesso não autorizado, favor tentar novamente.
                </div>
            </div>
        @endif
    </div>
    <div class="row">
    	<div class="col-md-4 col-sm-6 col-xs-8 center-block login-block">
    		<form action="{{URL::to('autentica')}}" method="POST" name="form-login" id="form-login" class="form-login">
    			<div class="form-group">
    				<label for="email">E-mail</label>
    				<input type="email" id="email" name="email" class="form-control" value="{{Request::old('email')}}">
    			</div>
    			<div class="form-group">
    				<label for="senha">Senha</label>
    				<input type="password" id="senha" name="senha" class="form-control" value="{{Request::old('senha')}}">
    			</div>
    			<button type="submit" class="btn btn-default" style="background-color:#E02A27;color:#fff;">Acessar</button>
                <!--Necessário para o laravel-->
                <input type="hidden" name="_token" value="{{ Session::token() }}">
    		</form>
    	</div>
    </div>
@endsection