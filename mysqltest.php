<?php

$servidor = "127.0.0.1";
$usuario = "root";
$banco = "apaacult_homologacao";
$senha = "";

$conmysql = mysql_connect($servidor.":1433", $usuario,$senha);
$db = mysql_select_db($banco, $conmysql);

if($conmysql && $db){
	echo "parabens deu certo";
}else{
	echo "não encontramos o banco";
}

?>