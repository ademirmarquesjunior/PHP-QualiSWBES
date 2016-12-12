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
		$rs = mysqli_query($conexao, $Sql) or die ("Erro ao apagar");
	}
	echo  "<script type='text/javascript'>";
	//echo "window.close();";
	//echo "history.go(-1)";
	//header("Location:listaquestao.php");
	echo "window.location.assign('listaquestao.php');";
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
		$rs = mysqli_query($conexao, $Sql) or die ("Erro");
		
		$i = 0;
		while($linha = mysqli_fetch_array($rs, MYSQLI_ASSOC)){
			$i = 1;
		}	
	
		if ($i == 0) {
			$Sql = "INSERT INTO `tbobjectives_has_tbuserquestion` (`idtbObjectives_has_tbUserQuestion`, `tbObjectives_idtbObjectives`, `tbUserQuestion_idtbUserQuestion`, `tbUserType_idtbUserType`, `tbObjectives_has_tbUserWeight`) VALUES (NULL, '".$objective."', '".$question."', '".$user."', '".$weight."')";
			$rs = mysqli_query($conexao, $Sql) or die ("Erro ao inserir");
		}
	
		if ($i == 1) {
			if (!is_numeric($weight)) $weight = 1.00;
			$Sql = "UPDATE `tbobjectives_has_tbuserquestion` SET `tbObjectives_has_tbUserWeight` = '".$weight."' WHERE `tbobjectives_has_tbuserquestion`.`tbObjectives_idtbObjectives` = ".$objective." AND `tbobjectives_has_tbuserquestion`.`tbUserQuestion_idtbUserQuestion` = ".$question." AND `tbobjectives_has_tbuserquestion`.`tbUserType_idtbUserType` = ".$user.";";
			$rs = mysqli_query($conexao, $Sql) or die ("Erro ao editar");
		}
		
		
	}
	echo  "<script type='text/javascript'>";
	//echo "window.close();";
	//echo "history.go(-1)";
	//header("Location:listaquestao.php");
	echo "window.location.assign('listaquestao.php');";
	echo "</script>";
}



?>

</body>



</html>