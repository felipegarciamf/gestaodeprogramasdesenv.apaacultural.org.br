@extends('layouts.master')

@section('title')
    Listagem de permissões de Usuário
@endsection

@section('content')
	<div class="row">
		<div class="col-md-8 col-sm-8">
			@if($usuario == null && $permissoes == null)
				<p>Usuário não pode ter permissões alteradas.</p>
			@else
				<b class="page-header">Usuário: <i>{{$usuario->nome}} {{$usuario->sobrenome}}</i></b>
				<div class="table table-responsive">
					<table class="table table-striped table-bordered"> 
						<thead> 
							<tr> 
								<th>Plano</th> 
								<th>Programa</th> 
							</tr> 
						</thead> 
						<tbody> 
							@foreach($permissoes as $permissao)
								<tr> 
									<th>{{$permissao->plano->nome}}</th>
									<th>{{$permissao->programa->nome}}</th>
									<td>
										<ul class="list-inline">
											<li>
												<a class="btn btn-danger" href="{{route('delete-usuario-permissao',['id' => $permissao->id])}}">Excluir</a>
											</li>
										</ul>
									</td>
								</tr>
							@endforeach
						</tbody> 
					</table>
					<a href="{{route('cadastra-permissoes-usuario',["id" => $usuario->id])}}" class="btn btn-primary">Conceder Nova Permissão</a>
				</div>
			@endif
		</div>
	</div>
@endsection