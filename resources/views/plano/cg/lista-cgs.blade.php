@extends('layouts.master')

@section('title')
    Listagem de Cgs
@endsection

@section('content')
<div style="margin-bottom:11px;"> <a href="{{ route('cadastra-cg') }}" class="btn btn-primary">Cadastrar novo Cg</a></div>
	<div class="row">
		<div class="col-md-8 col-sm-8">
			<div class="table table-responsive">
				<table class="table table-striped table-bordered"> 
					<thead> 
						<tr> 
							<th>Nome</th> 
						</tr> 
					</thead> 
					<tbody> 
						@foreach ($cgs as $cg)
						<tr> 
							<th>{{ $cg->nome }}</th> 
							<td>
								<ul class="list-inline">
									<li>
										<a href="{{ route('editar-cg',["id" => $cg->id]) }}" class="btn btn-warning">Editar</a>
										<a class="validaexcluir btn btn-danger" href="{{ route('delete-cg',["id" => $cg->id]) }}" class="btn btn-danger">Excluir</a>
									</li>
								</ul>
							</td>
						</tr>
						@endforeach
					</tbody> 
				</table>
			</div>
			
		</div>
	</div>
@endsection