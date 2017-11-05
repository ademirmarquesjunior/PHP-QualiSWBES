<?php
	$machine = "localhost";
	$dbuser = "caede741_cida";
	$dbpassword = "caede741_cida";
	$database = "caede741_cida";

	$conexao = mysqli_connect($machine, $dbuser, $dbpassword, $database) or die ("Conexão não estabelecida!");
	
	mysqli_set_charset($conexao, "utf8");
?>
