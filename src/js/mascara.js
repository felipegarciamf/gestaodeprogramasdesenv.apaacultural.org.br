console.log("subiu");



function adicionarPonto(campo) {
    if(!campo.value)
    	return campo.value      
    var nvalue = Number(RemoverPonto(campo)).toLocaleString('pt-BR')
    return nvalue;
}

function RemoverPonto(campo)
	{	
		campo.value = campo.value.split(".").join("");
		return campo.value;
	}






