<!DOCTYPE html>
<html><head>

<meta http-equiv="content-type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="estilo.css" type="text/css" media="screen"><title>Novo</title>

</head>

<body>

<?php
include "conecta.php";

if (isset($_GET['submituser'])) {

	$user = $_GET['Checkbox1'];
	$question = $_GET['txt_questao'];

	$Sql = "DELETE FROM `tbusertype_has_tbUserquestion` WHERE `tbUserQuestion_idtbUserQuestion` = ".$question;
	$rs = mysql_query($Sql, $conexao) or die ("Erro ao apagar");
	
	foreach ($user as $usertype){
		$Sql = "INSERT INTO `tbusertype_has_tbUserquestion` (`tbUserType_idtbUserType`, `tbUserQuestion_idtbUserQuestion`) VALUES ('".$usertype."', '".$question."')";
		$rs = mysql_query($Sql, $conexao) or die ("Erro na inserção");
		echo $usertype."<br>";
	}

	echo  "<script type='text/javascript'>";
	echo "window.close();";
	echo "</script>";

}





?>

</body>



</html>