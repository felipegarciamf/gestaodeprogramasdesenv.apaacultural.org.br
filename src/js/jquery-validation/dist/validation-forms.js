//VALIDACAO DE FORM DE LOGIN
$("#form-login").validate( {
	rules: {
		email: {
			required: true,
			email: true
		},
		senha: {
			required: true
		}
	},
	messages: {
		email: {
			required: "Insira o email de acesso",
			email: "Por favor, insira um e-mail válido!"
		},
		senha: {
			required: "Insira a senha!"
		}
	}
} );

//VALIDACAO DE FORM DE CADASTRO DE USUARIO
$("#form-cadastro-usuario").validate( {
	rules: {
		email: {
			required: true,
			email: true
		},
		nome: {
			required: true
		},
		sobrenome: {
			required: true
		},
		senha: {
			required: true
		},
		perfil: {
			required: true
		}
	},
	messages: {
		email: {
			required: "Insira o email de acesso"
		},
		nome: {
			required: "Insira o nome do usuário"
		},
		sobrenome: {
			required: "Insira o sobrenome do usuário"
		},
		senha: {
			required: "Insira uma senha para o usuário"
		},
		perfil: {
			required: "Escolha um perfil de usuário"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE USUARIO
$("#form-editar-usuario").validate( {
	rules: {
		email: {
			required: true,
			email: true
		},
		nome: {
			required: true
		},
		sobrenome: {
			required: true
		},
		perfil: {
			required: true
		}
	},
	messages: {
		email: {
			required: "Insira o email de acesso"
		},
		nome: {
			required: "Insira o nome do usuário"
		},
		sobrenome: {
			required: "Insira o sobrenome do usuário"
		},
		perfil: {
			required: "Escolha um perfil de usuário"
		}
	}
} );

//VALIDACAO DE FORM DE CADASTRO DE CG
$("#form-cadastro-cg").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do cg"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE CG
$("#form-editar-cg").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do cg"
		}
	}
} );

//VALIDACAO DE FORM DE CADASTRO DE OBJETO
$("#form-cadastro-objeto").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do objeto"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE OBJETO
$("#form-editar-objeto").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do objeto"
		}
	}
} );

//VALIDACAO DE FORM DE CADASTRO DE OS
$("#form-cadastro-os").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do os"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE OS
$("#form-editar-os").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do os"
		}
	}
} );

//VALIDACAO DE FORM DE CADASTRO DE TIPAGEM
$("#form-cadastro-tipagem").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do tipagem"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE TIPAGEM
$("#form-editar-tipagem").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do tipagem"
		}
	}
} );

//VALIDACAO DE FORM DE CADASTRO DE TIPO OBJETO
$("#form-cadastro-tipo-objeto").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do tipo objeto"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE TIPO OBJETO
$("#form-editar-tipo-objeto").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do tipo objeto"
		}
	}
} );

//VALIDACAO DE FORM DE CADASTRO DE UGE
$("#form-cadastro-uge").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do uge"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE UGE
$("#form-editar-uge").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do uge"
		}
	}
} );

//VALIDACAO DE FORM DE CADASTRO DE OS
$("#form-cadastro-os").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do os"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE OS
$("#form-editar-os").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do os"
		}
	}
} );

//VALIDACAO DE FORM DE CADASTRO DE PLANO
$("#form-cadastro-plano").validate( {
	rules: {
		nome:{
			required: true
		},
		uge:{
			required: true
		},
		cg:{
			required: true
		},
		objeto:{
			required: true
		},
		os:{
			required: true
		},
		tipo_objeto:{
			required: true
		},
		data_limite:{
			required: true
		}
	},
	messages: {
		nome:{
			required: "Por favor, insira o nome do plano"
		},
		uge:{
			required: "Por favor, insira uma uge"
		},
		cg:{
			required: "Por favor, insira uma cg"
		},
		objeto:{
			required: "Por favor, insira um objeto"
		},
		os:{
			required: "Por favor, insira uma os"
		},
		tipo_objeto:{
			required: "Por favor, insira um tipo de objeto"
		},
		data_limite:{
			required: "Por favor, insira a data limite do plano"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE PLANO
$("#form-editar-plano").validate( {
	rules: {
		nome:{
			required: true
		},
		uge:{
			required: true
		},
		cg:{
			required: true
		},
		objeto:{
			required: true
		},
		os:{
			required: true
		},
		tipo_objeto:{
			required: true
		},
		data_limite:{
			required: true
		}
	},
	messages: {
		nome:{
			required: "Por favor, insira o nome do plano"
		},
		uge:{
			required: "Por favor, insira uma uge"
		},
		cg:{
			required: "Por favor, insira uma cg"
		},
		objeto:{
			required: "Por favor, insira um objeto"
		},
		os:{
			required: "Por favor, insira uma os"
		},
		tipo_objeto:{
			required: "Por favor, insira um tipo de objeto"
		},
		data_limite:{
			required: "Por favor, insira a data limite do plano"
		}
	}
} );

//VALIDACAO DE FORM DE CADASTRO DE OS
$("#form-cadastro-rotina").validate( {
	rules: {
		nome: {
			required: true
		},
		plano: {
			required: true
		},
		data_limite: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome da rotina"
		},
		plano: {
			required: "Escolha um plano"
		},
		data_limite: {
			required: "Escolha uma data limite"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE OS
$("#form-editar-rotina").validate( {
	rules: {
		nome: {
			required: true
		},
		plano: {
			required: true
		},
		data_limite: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome da rotina"
		},
		plano: {
			required: "Escolha um plano"
		},
		data_limite: {
			required: "Escolha uma data limite"
		}
	}
} );

//VALIDACAO DE FORM DE CADASTRO DE TIPO DE PUBLICO
$("#form-cadastro-tipo-publico").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do tipo de público"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE TIPO DE PUBLICO
$("#form-editar-tipo-publico").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do tipo de público"
		}
	}
} );

//VALIDACAO DE FORM DE CADASTRO DE ENGAJAMENTO DE PUBLICO
$("#form-cadastro-engajamento-publico").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do engajamento do público"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE ENGAJAMENTO DE PUBLICO
$("#form-editar-engajamento-publico").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do engajamento do público"
		}
	}
} );

//VALIDACAO DE FORM DE CADASTRO DE SEGMENTO DE PUBLICO
$("#form-cadastro-segmento-publico").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do segmento de público"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE SEGMENTO DE PUBLICO
$("#form-editar-segmento-publico").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do segmento de público"
		}
	}
} );

$("#form-cadastro-realizador").validate( {
	rules: {
		nome: {
			required: true
		},
		num_total_pessoas: {
			required: true
		},
		num_apresentacoes: {
			required: true
		},
		municipio: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do realizador"
		},
		num_total_pessoas: {
			required: "Insira o número total de pessoas"
		},
		num_apresentacoes: {
			required: "Insira o número de apresentações"
		},
		municipio: {
			required: "Selecione a Cidade"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE MUNICIPIO
$("#form-editar-realizador").validate( {
	rules: {
		nome: {
			required: true
		},
		num_total_pessoas: {
			required: true
		},
		num_apresentacoes: {
			required: true
		},
		municipio: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do realizador"
		},
		num_total_pessoas: {
			required: "Insira o número total de pessoas"
		},
		num_apresentacoes: {
			required: "Insira o número de apresentações"
		},
		municipio: {
			required: "Selecione a Cidade"
		}
	}
} );

//VALIDACAO DE FORM DE CADASTRO DE MUNICIPIO
$("#form-cadastro-municipio").validate( {
	rules: {
		nome: {
			required: true
		},
		regiao_administrativa: {
			required: true
		},
		distancia: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do município"
		},
		regiao_administrativa: {
			required: "Selecione uma das opções"
		},
		distancia: {
			required: "Insira a distância"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE MUNICIPIO
$("#form-editar-municipio").validate( {
	rules: {
		nome: {
			required: true
		},
		regiao_administrativa: {
			required: true
		},
		distancia: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do município"
		},
		regiao_administrativa: {
			required: "Selecione uma das opções"
		},
		distancia: {
			required: "Insira a distância"
		}
	}
} );

//VALIDACAO DE FORM DE CADASTRO DE LINGUAGEM DE PROGRAMA
$("#form-cadastro-linguagem-programa").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome da linguagem"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE LINGUAGEM DE PROGRAMA
$("#form-editar-linguagem-programa").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome da linguagem"
		}
	}
} );

//VALIDACAO DE FORM DE CADASTRO DE LINGUAGEM DE PROGRAMA
$("#form-cadastro-genero-linguagem").validate( {
	rules: {
		nome: {
			required: true
		},
		linguagem: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome da gênero"
		},
		linguagem: {
			required: "Selecione a linguagem para o gênero"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE LINGUAGEM DE PROGRAMA
$("#form-editar-genero-linguagem").validate( {
	rules: {
		nome: {
			required: true
		},
		linguagem: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome da gênero"
		},
		linguagem: {
			required: "Selecione a linguagem para o gênero"
		}
	}
} );

//VALIDACAO DE FORM DE CADASTRO DE TIPO DE EVENTO
$("#form-cadastro-tipo-evento").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do tipo de evento"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE TIPO DE EVENTO
$("#form-editar-tipo-evento").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do tipo de evento"
		}
	}
} );

//VALIDACAO DE FORM DE CADASTRO DE ESPECIE AÇÃO
$("#form-cadastro-especie-acao").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome da espécie de ação"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE ESPECIE AÇÃO
$("#form-editar-especie-acao").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome da espécie de ação"
		}
	}
} );

//VALIDACAO DE FORM DE CADASTRO DE LINGUAGEM AÇÃO
$("#form-cadastro-linguagem-acao").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome da linguagem de ação"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE LINGUAGEM AÇÃO
$("#form-editar-linguagem-acao").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome da linguagem de ação"
		}
	}
} );

//VALIDACAO DE FORM DE CADASTRO DE FUNÇÃO AÇÃO
$("#form-cadastro-funcao-acao").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome da função da ação"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE FUNÇÃO AÇÃO
$("#form-editar-funcao-acao").validate( {
	rules: {
		nome: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome da função da ação"
		}
	}
} );

//VALIDACAO DE FORM DE CADASTRO DE PROGRAMA
$("#form-cadastro-programa").validate( {
	rules: {
		nome: {
			required: true
		},
		plano: {
			required: true
		},
		tipagem: {
			required: true
		},
		descrição: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do programa"
		},
		plano: {
			required: "Selecione um plano para o programa"
		},
		tipagem: {
			required: "Selecione uma tipagem para o programa"
		},
		descricao: {
			required: "Insira a descrição para o programa"
		}
	}
});

//VALIDACAO DE FORM DE EDICAO DE PROGRAMA
$("#form-editar-programa").validate( {
	rules: {
		nome: {
			required: true
		},
		plano: {
			required: true
		},
		tipagem: {
			required: true
		},
		descrição: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do programa"
		},
		plano: {
			required: "Selecione um plano para o programa"
		},
		tipagem: {
			required: "Selecione uma tipagem para o programa"
		},
		descricao: {
			required: "Insira a descrição para o programa"
		}
	}
});

//VALIDACAO DE FORM DE CADASTRO DE AÇÃO
$("#form-cadastro-acao").validate( {
	rules: {
		codigo: {
			required: true
		},
		nome: {
			required: true
		},
		linguagem:{
			required: true
		},
		especie:{
			required: true
		},
		funcao:{
			required: true
		},
		plano:{
			required:true
		},
		programa:{
			required:true
		},
		regiao:{
			required: true
		}
	},
	messages: {
		codigo:{
			required: "Insira o código da ação"
		},
		nome: {
			required: "Insira o nome da ação"
		},
		linguagem:
		{
			required: "Escolha uma linguagem da ação"
		},
		especie:
		{
			required: "Escolha uma espécie da ação"
		},
		funcao:{
			required: "Escolha uma função da ação"
		},
		plano:
		{
			required: "Escolha um plano para a ação"
		},
		programa:
		{
			required: "Escolha um programa para a ação"
		},
		regiao:{
			required: "Insira uma região da ação"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE AÇÃO
$("#form-editar-acao").validate( {
	rules: {
		codigo: {
			required: true
		},
		nome: {
			required: true
		},
		linguagem:{
			required: true
		},
		especie:{
			required: true
		},
		funcao:{
			required: true
		},
		plano:{
			required:true
		},
		programa:{
			required:true
		},
		regiao:{
			required: true
		}
	},
	messages: {
		codigo:{
			required: "Insira o código da ação"
		},
		nome: {
			required: "Insira o nome da ação"
		},
		linguagem:
		{
			required: "Escolha uma linguagem da ação"
		},
		especie:
		{
			required: "Escolha uma espécie da ação"
		},
		funcao:{
			required: "Escolha uma função da ação"
		},
		plano:
		{
			required: "Escolha um plano para a ação"
		},
		programa:
		{
			required: "Escolha um programa para a ação"
		},
		regiao:{
			required: "Insira uma região da ação"
		}
	}
} );

//VALIDACAO DE FORM DE CADASTRO DE INDICADOR
$("#form-cadastro-indicador").validate( {
	rules: {
		nome: {
			required: true
		},
		plano: {
			required: true
		},
		acao: {
			required: true
		},
		meta_1_tri: {
			required: true
		},
		meta_2_tri: {
			required: true
		},
		meta_3_tri: {
			required: true
		},
		meta_4_tri: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do indicador"
		},
		plano: {
			required: "Selecione um plano para o indicador"
		},
		acao: {
			required: "Selecione uma ação para o indicador"
		},
		meta_1_tri: {
			required: "Insira a meta do 1º trimestre para o indicador"
		},
		meta_2_tri: {
			required: "Insira a meta do 2º trimestre para o indicador"
		},
		meta_3_tri: {
			required: "Insira a meta do 3º trimestre para o indicador"
		},
		meta_4_tri: {
			required: "Insira a meta do 4º trimestre para o indicador"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE INDICADOR
$("#form-editar-indicador").validate( {
	rules: {
		nome: {
			required: true
		},
		plano: {
			required: true
		},
		acao: {
			required: true
		},
		meta_1_tri: {
			required: true
		},
		meta_2_tri: {
			required: true
		},
		meta_3_tri: {
			required: true
		},
		meta_4_tri: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome do indicador"
		},
		plano: {
			required: "Selecione um plano para o indicador"
		},
		acao: {
			required: "Selecione uma ação para o indicador"
		},
		meta_1_tri: {
			required: "Insira a meta do 1º trimestre para o indicador"
		},
		meta_2_tri: {
			required: "Insira a meta do 2º trimestre para o indicador"
		},
		meta_3_tri: {
			required: "Insira a meta do 3º trimestre para o indicador"
		},
		meta_4_tri: {
			required: "Insira a meta do 4º trimestre para o indicador"
		}
	}
} );

//VALIDACAO DE FORM DE CADASTRO DE INDICADOR
$("#form-cadastro-atividade").validate( {
	rules: {
		nome: {
			required: true
		},
		data: {
			required: true
		},
		horario: {
			required: true
		},
		dia_semana: {
			required: true
		},
		data_fim: {
			required: true
		},
		local: {
			required: true
		},
		capacidade: {
			required: true
		},
		num_total_pessoas: {
			required: true
		},
		num_total_tecnicos: {
			required: true
		},
		num_total_artistas: {
			required: true
		},
		inteiras: {
			required: true
		},
		meias: {
			required: true
		},
		moradores_entorno: {
			required: true
		},
		prom: {
			required: true
		},
		total_pagantes: {
			required: true
		},
		convites_prod: {
			required: true
		},
		convites_apaa: {
			required: true
		},
		educativo_producao: {
			required: true
		},
		educativo_apaa: {
			required: true
		},
		atend_social_producao: {
			required: true
		},
		atend_social_apaa: {
			required: true
		},
		sessao_acessivel: {
			required: true
		},
		acessibilidade_acompanhante: {
			required: true
		},
		bilheteria: {
			required: true
		},
		porcentagem_bilheteria: {
			required: true
		},
		artista: {
			required: true
		},
		publico: {
			required: true
		},
		plano: {
			required: true
		},
		programa: {
			required: true
		},
		tipo_publico: {
			required: true
		},
		realizador: {
			required: true
		},
		linguagem_programa: {
			required: true
		},
		genero_linguagem: {
			required: true
		},
		municipio: {
			required: true
		},
		tipo_evento: {
			required: true
		},
		observacoes: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome da atividade"
		},
		data: {
			required: "Escolha a data"
		},
		horario: {
			required: "Insira a hora da atividade"
		},
		dia_semana: {
			required: "Escolha o dia da semana"
		},
		data_fim: {
			required: "Escolha a data de fim da atividade"
		},
		local: {
			required: "Insira o local da atividade"
		},
		capacidade: {
			required: "Insira a capacidade para a atividade"
		},
		num_total_pessoas: {
			required: "Insira o número total de pessoas"
		},
		num_total_tecnicos: {
			required: "Insira o número total de técnicos"
		},
		num_total_artistas: {
			required: "Insira o número total de artístas"
		},
		inteiras: {
			required: "Insira a quantidade de inteiras"
		},
		meias: {
			required: "Insira a quantidade de meias"
		},
		moradores_entorno: {
			required: "Insira a quantidade de moradores do entorno"
		},
		prom: {
			required: "Insira a quantidade de prom"
		},
		total_pagantes: {
			required: "Insira o total de pagantes"
		},
		convites_prod: {
			required: "Insira o total de convites produção"
		},
		convites_apaa: {
			required: "Insira o total de convites apaa"
		},
		educativo_producao: {
			required: "Insira o educativo de producao"
		},
		educativo_apaa: {
			required: "Insira o educativo de apaa"
		},
		atend_social_producao: {
			required: "Insira o atendimento social de produção"
		},
		atend_social_apaa: {
			required: "Insira o atendimento social da apaa"
		},
		sessao_acessivel: {
			required: "Insira se a sessão é acessível ou não"
		},
		acessibilidade_acompanhante: {
			required: "Insira se a sessão tem acessibilidade de acompanhante"
		},
		bilheteria: {
			required: "Insira a bilheteria"
		},
		porcentagem_bilheteria: {
			required: "Insira a porcentagem da bilheteria apaa"
		},
		artista: {
			required: "Insira o artísta/banda/apresentação"
		},
		publico: {
			required: "Insira o campo público"
		},
		plano: {
			required: "Selecione o Plano"
		},
		programa: {
			required: "Selecione o Programa"
		},
		tipo_publico: {
			required: "Selecione o Tipo do Público"
		},
		realizador: {
			required: "Selecione o Realizador"
		},
		linguagem_programa: {
			required: "Selecione a linguagem da atividade"
		},
		genero_linguagem: {
			required: "Selecione o gênero da linguagem"
		},
		municipio: {
			required: "Selecione a Cidade"
		},
		tipo_evento: {
			required: "Selecione o tipo de Evento"
		},
		observacoes: {
			required: "Insira a observação para a atividade"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE INDICADOR
$("#form-editar-atividade").validate( {
	rules: {
		nome: {
			required: true
		},
		data: {
			required: true
		},
		horario: {
			required: true
		},
		dia_semana: {
			required: true
		},
		data_fim: {
			required: true
		},
		local: {
			required: true
		},
		capacidade: {
			required: true
		},
		num_total_pessoas: {
			required: true
		},
		num_total_tecnicos: {
			required: true
		},
		num_total_artistas: {
			required: true
		},
		inteiras: {
			required: true
		},
		meias: {
			required: true
		},
		moradores_entorno: {
			required: true
		},
		prom: {
			required: true
		},
		total_pagantes: {
			required: true
		},
		convites_prod: {
			required: true
		},
		convites_apaa: {
			required: true
		},
		educativo_producao: {
			required: true
		},
		educativo_apaa: {
			required: true
		},
		atend_social_producao: {
			required: true
		},
		atend_social_apaa: {
			required: true
		},
		sessao_acessivel: {
			required: true
		},
		acessibilidade_acompanhante: {
			required: true
		},
		bilheteria: {
			required: true
		},
		porcentagem_bilheteria: {
			required: true
		},
		artista: {
			required: true
		},
		publico: {
			required: true
		},
		plano: {
			required: true
		},
		programa: {
			required: true
		},
		tipo_publico: {
			required: true
		},
		realizador: {
			required: true
		},
		linguagem_programa: {
			required: true
		},
		genero_linguagem: {
			required: true
		},
		municipio: {
			required: true
		},
		tipo_evento: {
			required: true
		},
		observacoes: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome da atividade"
		},
		data: {
			required: "Escolha a data"
		},
		horario: {
			required: "Insira a hora da atividade"
		},
		dia_semana: {
			required: "Escolha o dia da semana"
		},
		data_fim: {
			required: "Escolha a data de fim da atividade"
		},
		local: {
			required: "Insira o local da atividade"
		},
		capacidade: {
			required: "Insira a capacidade para a atividade"
		},
		num_total_pessoas: {
			required: "Insira o número total de pessoas"
		},
		num_total_tecnicos: {
			required: "Insira o número total de técnicos"
		},
		num_total_artistas: {
			required: "Insira o número total de artístas"
		},
		inteiras: {
			required: "Insira a quantidade de inteiras"
		},
		meias: {
			required: "Insira a quantidade de meias"
		},
		moradores_entorno: {
			required: "Insira a quantidade de moradores do entorno"
		},
		prom: {
			required: "Insira a quantidade de prom"
		},
		total_pagantes: {
			required: "Insira o total de pagantes"
		},
		convites_prod: {
			required: "Insira o total de convites produção"
		},
		convites_apaa: {
			required: "Insira o total de convites apaa"
		},
		educativo_producao: {
			required: "Insira o educativo de producao"
		},
		educativo_apaa: {
			required: "Insira o educativo de apaa"
		},
		atend_social_producao: {
			required: "Insira o atendimento social de produção"
		},
		atend_social_apaa: {
			required: "Insira o atendimento social da apaa"
		},
		sessao_acessivel: {
			required: "Insira se a sessão é acessível ou não"
		},
		acessibilidade_acompanhante: {
			required: "Insira se a sessão tem acessibilidade de acompanhante"
		},
		bilheteria: {
			required: "Insira a bilheteria"
		},
		porcentagem_bilheteria: {
			required: "Insira a porcentagem da bilheteria apaa"
		},
		artista: {
			required: "Insira o artísta/banda/apresentação"
		},
		publico: {
			required: "Insira o campo público"
		},
		plano: {
			required: "Selecione o Plano"
		},
		programa: {
			required: "Selecione o Programa"
		},
		tipo_publico: {
			required: "Selecione o Tipo do Público"
		},
		realizador: {
			required: "Selecione o Realizador"
		},
		linguagem_programa: {
			required: "Selecione a linguagem da atividade"
		},
		genero_linguagem: {
			required: "Selecione o gênero da linguagem"
		},
		municipio: {
			required: "Selecione a Cidade"
		},
		tipo_evento: {
			required: "Selecione o tipo de Evento"
		},
		observacoes: {
			required: "Insira a observação para a atividade"
		}
	}
} );

//VALIDACAO DE FORM DE CADASTRO DE INDICADOR
$("#form-cadastro-atividade-por-programa").validate( {
	rules: {
		nome: {
			required: true
		},
		data: {
			required: true
		},
		horario: {
			required: true
		},
		dia_semana: {
			required: true
		},
		data_fim: {
			required: true
		},
		local: {
			required: true
		},
		capacidade: {
			required: true
		},
		num_total_pessoas: {
			required: true
		},
		num_total_tecnicos: {
			required: true
		},
		num_total_artistas: {
			required: true
		},
		inteiras: {
			required: true
		},
		meias: {
			required: true
		},
		moradores_entorno: {
			required: true
		},
		prom: {
			required: true
		},
		total_pagantes: {
			required: true
		},
		convites_prod: {
			required: true
		},
		convites_apaa: {
			required: true
		},
		educativo_producao: {
			required: true
		},
		educativo_apaa: {
			required: true
		},
		atend_social_producao: {
			required: true
		},
		atend_social_apaa: {
			required: true
		},
		sessao_acessivel: {
			required: true
		},
		acessibilidade_acompanhante: {
			required: true
		},
		bilheteria: {
			required: true
		},
		porcentagem_bilheteria: {
			required: true
		},
		artista: {
			required: true
		},
		publico: {
			required: true
		},
		tipo_publico: {
			required: true
		},
		realizador: {
			required: true
		},
		linguagem_programa: {
			required: true
		},
		genero_linguagem: {
			required: true
		},
		municipio: {
			required: true
		},
		tipo_evento: {
			required: true
		},
		observacoes: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome da atividade"
		},
		data: {
			required: "Escolha a data"
		},
		horario: {
			required: "Insira a hora da atividade"
		},
		dia_semana: {
			required: "Escolha o dia da semana"
		},
		data_fim: {
			required: "Escolha a data de fim da atividade"
		},
		local: {
			required: "Insira o local da atividade"
		},
		capacidade: {
			required: "Insira a capacidade para a atividade"
		},
		num_total_pessoas: {
			required: "Insira o número total de pessoas"
		},
		num_total_tecnicos: {
			required: "Insira o número total de técnicos"
		},
		num_total_artistas: {
			required: "Insira o número total de artístas"
		},
		inteiras: {
			required: "Insira a quantidade de inteiras"
		},
		meias: {
			required: "Insira a quantidade de meias"
		},
		moradores_entorno: {
			required: "Insira a quantidade de moradores do entorno"
		},
		prom: {
			required: "Insira a quantidade de prom"
		},
		total_pagantes: {
			required: "Insira o total de pagantes"
		},
		convites_prod: {
			required: "Insira o total de convites produção"
		},
		convites_apaa: {
			required: "Insira o total de convites apaa"
		},
		educativo_producao: {
			required: "Insira o educativo de producao"
		},
		educativo_apaa: {
			required: "Insira o educativo de apaa"
		},
		atend_social_producao: {
			required: "Insira o atendimento social de produção"
		},
		atend_social_apaa: {
			required: "Insira o atendimento social da apaa"
		},
		sessao_acessivel: {
			required: "Insira se a sessão é acessível ou não"
		},
		acessibilidade_acompanhante: {
			required: "Insira se a sessão tem acessibilidade de acompanhante"
		},
		bilheteria: {
			required: "Insira a bilheteria"
		},
		porcentagem_bilheteria: {
			required: "Insira a porcentagem da bilheteria apaa"
		},
		artista: {
			required: "Insira o artísta/banda/apresentação"
		},
		publico: {
			required: "Insira o campo público"
		},
		tipo_publico: {
			required: "Selecione o Tipo do Público"
		},
		realizador: {
			required: "Selecione o Realizador"
		},
		linguagem_programa: {
			required: "Selecione a linguagem da atividade"
		},
		genero_linguagem: {
			required: "Selecione o gênero da linguagem"
		},
		municipio: {
			required: "Selecione a Cidade"
		},
		tipo_evento: {
			required: "Selecione o tipo de Evento"
		},
		observacoes: {
			required: "Insira a observação para a atividade"
		}
	}
} );

//VALIDACAO DE FORM DE EDICAO DE INDICADOR
$("#form-editar-atividade-por-programa").validate( {
	rules: {
		nome: {
			required: true
		},
		data: {
			required: true
		},
		horario: {
			required: true
		},
		dia_semana: {
			required: true
		},
		data_fim: {
			required: true
		},
		local: {
			required: true
		},
		capacidade: {
			required: true
		},
		num_total_pessoas: {
			required: true
		},
		num_total_tecnicos: {
			required: true
		},
		num_total_artistas: {
			required: true
		},
		inteiras: {
			required: true
		},
		meias: {
			required: true
		},
		moradores_entorno: {
			required: true
		},
		prom: {
			required: true
		},
		total_pagantes: {
			required: true
		},
		convites_prod: {
			required: true
		},
		convites_apaa: {
			required: true
		},
		educativo_producao: {
			required: true
		},
		educativo_apaa: {
			required: true
		},
		atend_social_producao: {
			required: true
		},
		atend_social_apaa: {
			required: true
		},
		sessao_acessivel: {
			required: true
		},
		acessibilidade_acompanhante: {
			required: true
		},
		bilheteria: {
			required: true
		},
		porcentagem_bilheteria: {
			required: true
		},
		artista: {
			required: true
		},
		publico: {
			required: true
		},
		tipo_publico: {
			required: true
		},
		realizador: {
			required: true
		},
		linguagem_programa: {
			required: true
		},
		genero_linguagem: {
			required: true
		},
		municipio: {
			required: true
		},
		tipo_evento: {
			required: true
		},
		observacoes: {
			required: true
		}
	},
	messages: {
		nome: {
			required: "Insira o nome da atividade"
		},
		data: {
			required: "Escolha a data"
		},
		horario: {
			required: "Insira a hora da atividade"
		},
		dia_semana: {
			required: "Escolha o dia da semana"
		},
		data_fim: {
			required: "Escolha a data de fim da atividade"
		},
		local: {
			required: "Insira o local da atividade"
		},
		capacidade: {
			required: "Insira a capacidade para a atividade"
		},
		num_total_pessoas: {
			required: "Insira o número total de pessoas"
		},
		num_total_tecnicos: {
			required: "Insira o número total de técnicos"
		},
		num_total_artistas: {
			required: "Insira o número total de artístas"
		},
		inteiras: {
			required: "Insira a quantidade de inteiras"
		},
		meias: {
			required: "Insira a quantidade de meias"
		},
		moradores_entorno: {
			required: "Insira a quantidade de moradores do entorno"
		},
		prom: {
			required: "Insira a quantidade de prom"
		},
		total_pagantes: {
			required: "Insira o total de pagantes"
		},
		convites_prod: {
			required: "Insira o total de convites produção"
		},
		convites_apaa: {
			required: "Insira o total de convites apaa"
		},
		educativo_producao: {
			required: "Insira o educativo de producao"
		},
		educativo_apaa: {
			required: "Insira o educativo de apaa"
		},
		atend_social_producao: {
			required: "Insira o atendimento social de produção"
		},
		atend_social_apaa: {
			required: "Insira o atendimento social da apaa"
		},
		sessao_acessivel: {
			required: "Insira se a sessão é acessível ou não"
		},
		acessibilidade_acompanhante: {
			required: "Insira se a sessão tem acessibilidade de acompanhante"
		},
		bilheteria: {
			required: "Insira a bilheteria"
		},
		porcentagem_bilheteria: {
			required: "Insira a porcentagem da bilheteria apaa"
		},
		artista: {
			required: "Insira o artísta/banda/apresentação"
		},
		publico: {
			required: "Insira o campo público"
		},
		tipo_publico: {
			required: "Selecione o Tipo do Público"
		},
		realizador: {
			required: "Selecione o Realizador"
		},
		linguagem_programa: {
			required: "Selecione a linguagem da atividade"
		},
		genero_linguagem: {
			required: "Selecione o gênero da linguagem"
		},
		municipio: {
			required: "Selecione a Cidade"
		},
		tipo_evento: {
			required: "Selecione o tipo de Evento"
		},
		observacoes: {
			required: "Insira a observação para a atividade"
		}
	}
} );