$(document).ready(function(){

	// Esconde o select de replicar plano no cadastro plano
	$("#replicar-plano").hide();

	// Libera a replicação de plano no cadastro plano
	
	$("#replicar-plano-sim").click(function(){

		$("#replicar-plano").show("slow");
	});

	// Esconde a replicação de plano no cadastro de plano
	$("#replicar-plano-nao").click(function(){
		$("#replicar-plano").hide("slow");
	});


	// cria campo de justificativa a mais
	$("#justificativa").on('click',function(){
		event.preventDefault();
		$("#create")
		.prepend('<div class="col-md-12"><b>Justificativa</b><textarea placeholder="Digite a Justificativa" style="width:100%;"></textarea></div>')
	})

	// validar se deseja deletar o dado que foi cadastrado
	$(".validaexcluir").on('click',function(){
		var confirmado = confirm("Deseja Deletar Esses Dados");
		if (! confirmado) return false;

	});


	console.log("subiu");

	function BlockKeybord()
	{
		if((event.keyCode < 48) || (event.keyCode > 57))
		{
			event.returnValue = false;
		}
	}

	function troca(str,strsai,strentra)
	{
		while(str.indexOf(strsai)>-1)
		{
			str = str.replace(strsai,strentra);
		}
		return str;
	}

	function FormataMoeda(campo,tammax,teclapres,caracter)
	{
		if(teclapres == null || teclapres == "undefined")
		{
			var tecla = -1;
		}
		else
		{
			var tecla = teclapres.keyCode;
		}

		if(caracter == null || caracter == "undefined")
		{
			caracter = ".";
		}

		vr = campo.value;
		if(caracter != "")
		{
			vr = troca(vr,caracter,"");
		}
		vr = troca(vr,"/","");
		vr = troca(vr,",","");
		vr = troca(vr,".","");

		tam = vr.length;
		if(tecla > 0)
		{
			if(tam < tammax && tecla != 8)
			{
				tam = vr.length + 1;
			}

			if(tecla == 8)
			{
				tam = tam - 1;
			}
		}
		if(tecla == -1 || tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105)
		{
			if(tam <= 2)
			{ 
				campo.value = vr;
			}
			if((tam > 2) && (tam <= 5))
			{
				campo.value = vr.substr(0, tam - 2) + ',' + vr.substr(tam - 2, tam);
			}
			if((tam >= 6) && (tam <= 8))
			{
				campo.value = vr.substr(0, tam - 5) + caracter + vr.substr(tam - 5, 3) + ',' + vr.substr(tam - 2, tam);
			}
			if((tam >= 9) && (tam <= 11))
			{
				campo.value = vr.substr(0, tam - 8) + caracter + vr.substr(tam - 8, 3) + caracter + vr.substr(tam - 5, 3) + ',' + vr.substr(tam - 2, tam);
			}
			if((tam >= 12) && (tam <= 14))
			{
				campo.value = vr.substr(0, tam - 11) + caracter + vr.substr(tam - 11, 3) + caracter + vr.substr(tam - 8, 3) + caracter + vr.substr(tam - 5, 3) + ',' + vr.substr(tam - 2, tam);
			}
			if((tam >= 15) && (tam <= 17))
			{
				campo.value = vr.substr(0, tam - 14) + caracter + vr.substr(tam - 14, 3) + caracter + vr.substr(tam - 11, 3) + caracter + vr.substr(tam - 8, 3) + caracter + vr.substr(tam - 5, 3) + ',' + vr.substr(tam - 2, tam);
			}
		}
	}

	function maskKeyPress(objEvent) 
	{
		var iKeyCode;  	
		iKeyCode = objEvent.keyCode;  	
		if(iKeyCode>=48 && iKeyCode<=57) return true;
		return false;
	}


	// deletar campos não necessários em "cadastra atividade"
	/*$(".form-group").dblclick(function(){
		var campo = $(this).text();
		if(confirm("Deseja Apagar?" + campo )){
			$(this).remove();
		}
		else
		{
			alert("Operação Cancelada!");
		}
	});
	*/

	//data do cadastro do plano
	if($( "#data_limite" ).length > 0)
	{
		$( "#data_limite" ).datepicker({
			dateFormat: 'dd/mm/yy'
		});
	}
	
	//campo no cadastro de programas com chamada Ajax
	/*if($('#plano-change-programa').length > 0)
	{
		$('#plano-change-programa').change(function(){
		    var plano_id, token, url, data;
		    token = $('input[name=_token]').val();
		    plano_id = $('#plano-change-programa').val();
		    url = "chama-acoes-programa";
		    data = {plano_id: plano_id};
		    $.ajax({
		        url: url,
		        headers: {'X-CSRF-TOKEN': token},
		        data: data,
		        type: 'POST',
		        datatype: 'JSON',
		        success: function (resp) {
		        	//console.log(resp.acoes.length);
		        	$('#acao').empty();
		        	if(resp.acoes.length > 0)
		        	{	$('#acao').append('<option value="">Selecione</option>');
		        		$.each(resp.acoes, function (key, value) {
		        		    $('#acao').append('<option>'+ value.nome +'</option>');
		        		});
		        	}
		        	else
		        	{
		        		$('#acao').append('<option value="">Plano sem ações atreladas</option>');
		        	}
		        }
		    });
		});
	}*/
	//campo no cadastro de ATIVIDADES com chamada Ajax
	if($('#planos-ajax-atividade').length > 0)
	{
		$('#planos-ajax-atividade').change(function(){
			var plano_id, token, url, data;
			token = $('input[name=_token]').val();
			plano_id = $('#planos-ajax-atividade').val();
			url = "../chama-programas-atividade";
			data = {plano_id: plano_id};
			$.ajax({
				url: url,
				headers: {'X-CSRF-TOKEN': token},
				data: data,
				type: 'POST',
				datatype: 'JSON',
				success: function (resp) {
		        	//console.log(resp.programas.length);
		        	$('#programa').empty();
		        	if(resp.programas.length > 0)
		        		{	$('#programa').append('<option value="">Selecione</option>');
		        	$.each(resp.programas, function (key, value) {
		        		$('#programa').append('<option value="'+ value.id +'">'+ value.nome +'</option>');
		        	});
		        }
		        else
		        {
		        	$('#programa').append('<option value="">Plano sem programas atrelados</option>');
		        }
		    }
		});
		});
	}

	//campo na edição de ATIVIDADE com chamada Ajax
	if($('#planos-ajax-atividade-edit').length > 0)
	{
		$('#planos-ajax-atividade-edit').change(function(){
			var plano_id, token, url, data;
			token = $('input[name=_token]').val();
			plano_id = $('#planos-ajax-atividade-edit').val();
			url = $('#urlAjaxProgramasAtividadeEdit').val();
			data = {plano_id: plano_id};
			$.ajax({
				url: url,
				headers: {'X-CSRF-TOKEN': token},
				data: data,
				type: 'POST',
				datatype: 'JSON',
				success: function (resp) {
		        	//console.log(resp.programas.length);
		        	$('#programa').empty();
		        	if(resp.programas.length > 0)
		        		{	$('#programa').append('<option value="">Selecione</option>');
		        	$.each(resp.programas, function (key, value) {
		        		$('#programa').append('<option value="'+ value.id +'">'+ value.nome +'</option>');
		        	});
		        }
		        else
		        {
		        	$('#programa').append('<option value="">Plano sem programas atrelados</option>');
		        }
		    }
		});
		});
	}




	//campo no cadastro de AÇÕES com chamada Ajax
	if($('#planos-ajax-acao').length > 0)
	{
		$('#planos-ajax-acao').change(function(){
			var plano_id, token, url, data;
			token = $('input[name=_token]').val();
			plano_id = $('#planos-ajax-acao').val();
			url = "chama-programas-acao";
			data = {plano_id: plano_id};
			$.ajax({
				url: url,
				headers: {'X-CSRF-TOKEN': token},
				data: data,
				type: 'POST',
				datatype: 'JSON',
				success: function (resp) {
		        	//console.log(resp.programas.length);
		        	$('#programa').empty();
		        	if(resp.programas.length > 0)
		        		{	$('#programa').append('<option value="">Selecione</option>');
		        	$.each(resp.programas, function (key, value) {
		        		$('#programa').append('<option value="'+ value.id +'">'+ value.nome +'</option>');
		        	});
		        }
		        else
		        {
		        	$('#programa').append('<option value="">Plano sem programas atrelados</option>');
		        }
		    }
		});
		});
	}

	//campo na edição de AÇÕES com chamada Ajax
	if($('#planos-ajax-acao-edit').length > 0)
	{
		$('#planos-ajax-acao-edit').change(function(){
			var plano_id, token, url, data;
			token = $('input[name=_token]').val();
			plano_id = $('#planos-ajax-acao-edit').val();
			url = $('#urlAjaxProgramasAcaoEdit').val();
			data = {plano_id: plano_id};
			$.ajax({
				url: url,
				headers: {'X-CSRF-TOKEN': token},
				data: data,
				type: 'POST',
				datatype: 'JSON',
				success: function (resp) {
		        	//console.log(resp.programas.length);
		        	$('#programa').empty();
		        	if(resp.programas.length > 0)
		        		{	$('#programa').append('<option value="">Selecione</option>');
		        	$.each(resp.programas, function (key, value) {
		        		$('#programa').append('<option value="'+ value.id +'">'+ value.nome +'</option>');
		        	});
		        }
		        else
		        {
		        	$('#programa').append('<option value="">Plano sem programas atrelados</option>');
		        }
		    }
		});
		});
	}

	//campo no cadastro de INDICADORES com chamada Ajax
	if($('#planos-ajax-indicador').length > 0)
	{
		$('#planos-ajax-indicador').change(function(){
			var plano_id, token, url, data;
			token = $('input[name=_token]').val();
			plano_id = $('#planos-ajax-indicador').val();
			url = "chama-acoes-indicador";
			data = {plano_id: plano_id};
			$.ajax({
				url: url,
				headers: {'X-CSRF-TOKEN': token},
				data: data,
				type: 'POST',
				datatype: 'JSON',
				success: function (resp) {
		        	//console.log(resp.programas.length);
		        	$('#acao').empty();
		        	if(resp.acoes.length > 0)
		        		{	$('#acao').append('<option value="">Selecione</option>');
		        	$.each(resp.acoes, function (key, value) {
		        		$('#acao').append('<option value="'+ value.id +'">'+ value.nome +' - '+ value.codigo_acao +'</option>');
		        	});
		        }
		        else
		        {
		        	$('#acao').append('<option value="">Plano sem ações atreladas</option>');
		        }
		    }
		});
		});
	}

	//campo na edição de INDICADORES com chamada Ajax
	if($('#planos-ajax-indicador-edit').length > 0)
	{
		$('#planos-ajax-indicador-edit').change(function(){
			var plano_id, token, url, data;
			token = $('input[name=_token]').val();
			plano_id = $('#planos-ajax-indicador-edit').val();
			url = $('#urlAjaxAcoesIndicadorEdit').val();
			data = {plano_id: plano_id};
			$.ajax({
				url: url,
				headers: {'X-CSRF-TOKEN': token},
				data: data,
				type: 'POST',
				datatype: 'JSON',
				success: function (resp) {
		        	//console.log(resp.programas.length);
		        	$('#acao').empty();
		        	if(resp.acoes.length > 0)
		        		{	$('#acao').append('<option value="">Selecione</option>');
		        	$.each(resp.acoes, function (key, value) {
		        		$('#acao').append('<option value="'+ value.id +'">'+ value.nome +' - '+ value.codigo_acao +'</option>');
		        	});
		        }
		        else
		        {
		        	$('#acao').append('<option value="">Plano sem ações atreladas</option>');
		        }
		    }
		});
		});
	}


	//campo no cadastro de ATIVIDADES com chamada Ajax
	if($('#planos-ajax-atividade').length > 0)
	{
		$('#planos-ajax-atividade').change(function(){
			var plano_id, token, url, data;
			token = $('input[name=_token]').val();
			plano_id = $('#planos-ajax-atividade').val();
			url = "../chama-programas-atividade";
			data = {plano_id: plano_id};
			$.ajax({
				url: url,
				headers: {'X-CSRF-TOKEN': token},
				data: data,
				type: 'POST',
				datatype: 'JSON',
				success: function (resp) {
		        	//console.log(resp.programas.length);
		        	$('#programa_cadastra').empty();
		        	if(resp.programas.length > 0)
		        		{	$('#programa_cadastra').append('<option value="">Selecione</option>');
		        	$.each(resp.programas, function (key, value) {
		        		$('#programa_cadastra').append('<option value="'+ value.id +'">'+ value.nome +'</option>');
		        	});
		        }
		        else
		        {
		        	$('#programa_cadastra').append('<option value="">Plano sem programas atrelados</option>');
		        }
		    }
		});
		});
	}

	//campo na edição de ATIVIDADES com chamada Ajax
	if($('#planos-ajax-atividade-edit').length > 0)
	{
		$('#planos-ajax-atividade-edit').change(function(){
			var plano_id, token, url, data;
			token = $('input[name=_token]').val();
			plano_id = $('#planos-ajax-atividade-edit').val();
			url = "../chama-programas-atividade";
			data = {plano_id: plano_id};
			$.ajax({
				url: url,
				headers: {'X-CSRF-TOKEN': token},
				data: data,
				type: 'POST',
				datatype: 'JSON',
				success: function (resp) {
		        	//console.log(resp.programas.length);
		        	$('#programa').empty();
		        	if(resp.programas.length > 0)
		        		{	$('#programa').append('<option value="">Selecione</option>');
		        	$.each(resp.programas, function (key, value) {
		        		$('#programa').append('<option value="'+ value.id +'">'+ value.nome +'</option>');
		        	});
		        }
		        else
		        {
		        	$('#programa').append('<option value="">Plano sem programas atrelados</option>');
		        }
		    }
		});
		});
	}

	//campo no cadastro de PERMISSÕES DE USUÁRIO Produtor com chamada Ajax
	if($('#planos-ajax-permissao').length > 0)
	{
		$('#planos-ajax-permissao').change(function(){
			var plano_id, token, url, data;
			token = $('input[name=_token]').val();
			plano_id = $('#planos-ajax-permissao').val();
			user_id = $('#usuario').val();
		    //url = $('#urlAjaxPermissoesUsuariosEdit').val();
		    url = '../chama-programas-permissao';
		    data = {plano_id: plano_id,user_id: user_id};
		    $.ajax({
		    	url: url,
		    	headers: {'X-CSRF-TOKEN': token},
		    	data: data,
		    	type: 'POST',
		    	datatype: 'JSON',
		    	success: function (resp) 
		    	{
		        	//console.log(resp.programas.length);
		        	//console.log(resp);
		        	$('#programa').empty();
		        	if(resp.programas.length > 0)
		        	{	//$('#programa').append('<option value="">Selecione</option>');
		        $.each(resp.programas, function (key, value) {
		        	$('#programa').append('<li><div class="checkbox"><label><input name="programas[]" type="checkbox" value="'+ value.id +'">'+ value.nome +'</label></div></li>');
		        });
		    }
		    else
		    {
		    	$('#programa').append('<li><p>Programas Indisponíveis</p></li>');
		    }
		}
	});
		});
	}

	//campo de hora no cadastro da atividade
	if($('#horario').length > 0)
	{
		var input = $('#horario');
		input.clockpicker({
			autoclose: true
		});
	}

	//data de cadastro de atividades
	if($( "#data" ).length > 0)
	{
		$( "#data" ).datepicker({
			dateFormat: 'dd/mm/yy'
		});
	}

	if($( "#data_fim" ).length > 0)
	{
		$( "#data_fim" ).datepicker({
			dateFormat: 'dd/mm/yy'
		});
	}
});