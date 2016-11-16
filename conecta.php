<?php
	$conexao = mysql_connect("localhost","root",'') or die ("Conexão não estabelecida!");
	$DB = mysql_select_db("mydb", $conexao) or die ("Erro ao selecionar Banco de Dados!");
	
	mysql_query("SET NAMES 'utf8'");
	mysql_query('SET character_set_connection=utf8');
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_results=utf8');

?>
