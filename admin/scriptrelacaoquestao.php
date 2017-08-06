<!DOCTYPE html>
<html><head>

<meta http-equiv="content-type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="estilo.css" type="text/css" media="screen"><title>Novo</title>

</head>

<body>

<?php
include "conecta.php";

if (isset($_POST['submitRelationDel'])) {

	$user = $_POST['sel_user'];
	$weight = $_POST['txt_value'];
	$min = $_POST['txt_min'];
	$max = $_POST['txt_max'];

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

if (isset($_POST['submitRelationAdd'])) {

	$user = $_POST['sel_user'];
	$weight = $_POST['txt_value'];
	$min = $_POST['txt_min'];
	$max = $_POST['txt_max'];

$Sql0 = "SELECT * FROM tbquestionid WHERE idtbQuestionId >= ".$min." AND idtbQuestionId <= ".$max;
$rs0 = mysqli_query($conexao, $Sql0) or die ("Erro ao listar");
while($linha0 = mysqli_fetch_array($rs0, MYSQLI_ASSOC)){
/*
1 - Engenheiro do conhecimento + Autor
2 - Autor
3 - Professor/Tutor
4 - Desenvolvedor
5 - Estudante
6 - Gestor
7 - Especialista
*/	

	$question = $linha0['idtbQuestionId'];

	if ($user != "") {
	
		$Sql = "SELECT * FROM `tbusertype_has_tbuserquestion` WHERE tbQuestionId_idtbQuestionId = ".$question." AND tbUserType_idtbUserType = ".$user." ";
		echo $Sql;
		$rs = mysqli_query($conexao, $Sql) or die ("Erro obter quantidade");
		
		$i = 0;
		while($linha = mysqli_fetch_array($rs, MYSQLI_ASSOC)){
			$i = 1;
		}	
	
		if ($i == 0) {
			$Sql = "INSERT INTO `tbusertype_has_tbuserquestion` (`tbUserType_idtbUserType`, `tbUserType_has_tbUserQuestionWeight`, `tbQuestionId_idtbQuestionId`) VALUES ('".$user."', '".$weight."', '".$question."')";
			//$Sql = "INSERT INTO `tbobjectives_has_tbuserquestion` (`idtbObjectives_has_tbUserQuestion`, `tbObjectives_idtbObjectives`, `tbUserQuestion_idtbUserQuestion`, `tbUserType_idtbUserType`, `tbObjectives_has_tbUserWeight`) VALUES (NULL, '".$objective."', '".$question."', '".$user."', '".$weight."')";
			$rs = mysqli_query($conexao, $Sql) or die ("Erro ao inserir");
		}
	
		if ($i == 1) {
			if (!is_numeric($weight)) $weight = 1.00;
			$Sql = "UPDATE `tbusertype_has_tbuserquestion` SET `tbUserType_has_tbUserQuestionWeight` = '".$weight."' WHERE  `tbusertype_has_tbuserquestion`.`tbUserType_idtbUserType` = ".$user." AND `tbusertype_has_tbuserquestion`.`tbQuestionId_idtbQuestionId` = ".$question;
			//$Sql = "UPDATE `tbobjectives_has_tbuserquestion` SET `tbObjectives_has_tbUserWeight` = '".$weight."' WHERE `tbobjectives_has_tbuserquestion`.`tbObjectives_idtbObjectives` = ".$objective." AND `tbobjectives_has_tbuserquestion`.`tbUserQuestion_idtbUserQuestion` = ".$question." AND `tbobjectives_has_tbuserquestion`.`tbUserType_idtbUserType` = ".$user.";";
			$rs = mysqli_query($conexao, $Sql) or die ("Erro ao editar");
		}
		
		
	}
	echo  "<script type='text/javascript'>";
	//echo "window.close();";
	//echo "history.go(-1)";
	//header("Location:listaquestao.php");
	//echo "window.location.assign('listaquestao.php');";
	echo "</script>";
}


}

?>

<form action="scriptrelacaoquestao.php" method="POST">
	<input type="text" name="txt_min" value="" />
	<input type="text" name="txt_max" value="" />
	
	<select name="sel_user">
		<option value="1">Engenheiro do conhecimento</option>
		<option value="2">Autor</option>	
		<option value="3">Professor/Tutor</option>	
		<option value="4">Desenvolvedor</option>	
		<option value="5">Estudante</option>	
		<option value="6">Gestor</option>	
		<option value="7">Especialista</option>	
	</select>
	<input type="text" name="txt_value" value="1" />
	<input type="submit" name="submitRelationAdd" value="adicionar" />
	<input type="submit" name="submitRelationDel" value="excluir" />
</form>


</body>



</html>