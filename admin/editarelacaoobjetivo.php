<!DOCTYPE html>
<html><head>

<meta http-equiv="content-type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="estilo.css" type="text/css" media="screen"><title>Novo</title>

</head>

<body>

<?php
include "conecta.php";

if (isset($_GET['submitobjective'])) {

	$objectives = $_GET['Checkbox2'];
	$question = $_GET['txt_questao'];

	$Sql = "DELETE FROM `tbobjectives_has_tbUserquestion` WHERE `tbUserQuestion_idtbUserQuestion` = ".$question;
	$rs = mysql_query($Sql, $conexao) or die ("Erro ao apagar");
	
	foreach ($objectives as $objective){
		$Sql = "INSERT INTO `tbObjectives_has_tbUserquestion` (`tbObjectives_idtbObjectives`, `tbUserQuestion_idtbUserQuestion`) VALUES ('".$objective."', '".$question."')";
		$rs = mysql_query($Sql, $conexao) or die ("Erro na inserção");
		echo $objective."<br>";
	}

	echo  "<script type='text/javascript'>";
	echo "window.close();";
	echo "</script>";



}





?>

</body>



</html>