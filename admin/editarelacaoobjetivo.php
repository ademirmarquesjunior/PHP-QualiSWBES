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
	$weight = $_GET['txt_peso'];

	$Sql = "DELETE FROM `tbobjectives_has_tbUserquestion` WHERE `tbUserQuestion_idtbUserQuestion` = ".$question;
	$rs = mysql_query($Sql, $conexao) or die ("Erro ao apagar");
	
	foreach ($objectives as $i=>$objective){
		$Sql = "INSERT INTO `tbObjectives_has_tbUserquestion` (`tbObjectives_idtbObjectives`, `tbUserQuestion_idtbUserQuestion`, `tbObjectives_has_tbUserWeight` ) VALUES ('".$objective."', '".$question."', '".$weight[$objective-1]."')";
		$rs = mysql_query($Sql, $conexao) or die ("Erro na inserção");
		echo $objective." ".$weight[$objective-1]."<br>";
	}
	
	
	echo  "<script type='text/javascript'>";
	echo "window.close();";
	echo "</script>";



}





?>

</body>



</html>