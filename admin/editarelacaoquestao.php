<!DOCTYPE html>
<html><head>

<meta http-equiv="content-type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="estilo.css" type="text/css" media="screen"><title>Novo</title>

</head>

<body>

<?php
include "conecta.php";

if (isset($_GET['submitRelationDel'])) {

	$user = $_GET['sel_user'];
	$objective = $_GET['sel_objective'];
	$question = $_GET['txt_question'];

	if (($user != "") AND ($objective != "")) {
	
		$Sql = "DELETE FROM `tbobjectives_has_tbuserquestion` WHERE `tbObjectives_idtbObjectives` = ".$objective." AND `tbUserQuestion_idtbUserQuestion` = ".$question." AND `tbUserType_idtbUserType` = ".$user.";";
		echo $Sql;
		$rs = mysql_query($Sql, $conexao) or die ("Erro ao apagar");
	}
	echo  "<script type='text/javascript'>";
	echo "window.close();";
	echo "</script>";
	
}

if (isset($_GET['submitRelationAdd'])) {

	$user = $_GET['sel_user'];
	$objective = $_GET['sel_objective'];
	$weight = $_GET['txt_weight'];
	$question = $_GET['txt_question'];

	if (($user != "") AND ($objective != "")) {
	
		$Sql = "SELECT * FROM tbobjectives_has_tbuserquestion WHERE tbObjectives_idtbObjectives = ".$objective." AND tbUserQuestion_idtbUserQuestion = ".$question." AND tbUserType_idtbUserType = ".$user." ";
		echo $Sql;
		$rs = mysql_query($Sql, $conexao) or die ("Erro");
		
		$i = 0;
		while($linha = mysql_fetch_array($rs)){
			$i = 1;
		}	
	
		if ($i == 0) {
			$Sql = "INSERT INTO `tbobjectives_has_tbuserquestion` (`idtbObjectives_has_tbUserQuestion`, `tbObjectives_idtbObjectives`, `tbUserQuestion_idtbUserQuestion`, `tbUserType_idtbUserType`, `tbObjectives_has_tbUserWeight`) VALUES (NULL, '".$objective."', '".$question."', '".$user."', '".$weight."')";
			$rs = mysql_query($Sql, $conexao) or die ("Erro ao inserir");
		}
	
		if ($i == 1) {
			if (!is_numeric($weight)) $weight = 1.00;
			$Sql = "UPDATE `tbobjectives_has_tbuserquestion` SET `tbObjectives_has_tbUserWeight` = '".$weight."' WHERE `tbobjectives_has_tbuserquestion`.`tbObjectives_idtbObjectives` = ".$objective." AND `tbobjectives_has_tbuserquestion`.`tbUserQuestion_idtbUserQuestion` = ".$question." AND `tbobjectives_has_tbuserquestion`.`tbUserType_idtbUserType` = ".$user.";";
			$rs = mysql_query($Sql, $conexao) or die ("Erro ao editar");
		}
		
		
	}
	echo  "<script type='text/javascript'>";
	echo "window.close();";
	echo "</script>";
}



?>

</body>



</html>