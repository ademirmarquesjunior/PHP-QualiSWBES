<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="estilo.css" type="text/css" media="screen" />
<title>Cabeçalho aqui</title>
</head>
<body>

<div id="header">
Cabeçalho aqui
</div>

<div id="login">
<?php
include("valida.php");
?>
</div>


<div id="content">

<?php

include "conecta.php";

$user = $_SESSION['user_id'];
$type = $_SESSION['user_type'];

if (isset($_POST['txt_aplic'])) {
	$aplic = $_POST['txt_aplic'];
	$aplic_desc = $_POST['txt_aplic_desc'];

	$Sql = "SELECT * FROM `tbapplication` WHERE `tbApplicationName` = '".$aplic."'";
	$rs = mysql_query($Sql, $conexao) or die ("Erro busca aplicação");
	
	$linha = mysql_fetch_array($rs);
	$applic_id=$linha["idtbApplication"];

	
	if ($linha) {
		echo "<script language='javascript' type='text/javascript'> if(!confirm('Já existe uma aplicação com esse nome. Deseja avaliar a aplicação encontrada?')) {window.location.href='index2.php'; } </script>";

		$linha = mysql_fetch_array($rs);
		$applic_id=$linha["idtbApplication"];
		
	} else {
		$Sql2 = "INSERT INTO `tbapplication` (`idtbApplication`, `tbApplicationName`, `tbApplicationDescription`) VALUES (NULL, '".$aplic."', '".$aplic_desc."')";
		$rs2 = mysql_query($Sql2, $conexao) or die ("Erro insere aplicação");

		//obter o id da aplicação inserida. Mudar essa sessão por uma função do mysql para obter autoincrement
		if ($rs2) {
			$Sql = "SELECT * FROM `tbapplication` WHERE `tbApplicationName` = '".$aplic."'";
			$rs = mysql_query($Sql, $conexao) or die ("Erro busca id aplicação");
			$linha2 = mysql_fetch_array($rs);
			$applic_id=$linha2["idtbApplication"];
		}	
	}

	$_SESSION['appic_id'] = $applic_id;
	
	//inserir um novo formulário em tbform
	$Sql = "INSERT INTO `tbform` (`idtbForm`, `tbApplication_idtbApplication`, `tbUser_idtbUser`) VALUES (NULL, '".$applic_id."', '".$user."')";
	$rs = mysql_query($Sql, $conexao) or die ("Erro insere formulário");

	//obter o id do form inserido
	if ($rs) {
		$Sql2 = "SELECT * FROM `tbform` WHERE `tbApplication_idtbApplication` = '".$applic_id."' AND `tbUser_idtbUser` = '".$user."'";
		$rs2 = mysql_query($Sql2, $conexao) or die ("Erro busca id formulário");
		$linha = mysql_fetch_array($rs2);
		$form_id=$linha["idtbForm"];
		$_SESSION['form_id'] = $form_id;
		header('Location:form.php');
	}
}

if (isset($_POST['sel_aplic'])) {





}

?>



<h3>Cadastrar uma nova aplicação e iniciar avaliação</h3>

<form action="index2.php" method="post" name="form1">
<p><label>Nome da aplicação</label></p>
<p>
<input name="txt_aplic" type="text" id="entravalor"  size="13" style="width: 516px"  required/></p>
<p><input type="submit" value="Iniciar avaliação"/></p>
</form>






	
	<br><hr><br>
	<h3>Avaliar uma dessas aplicações</h3>
	
<form action="index2.php" method="post" name="form2">
<p><label>Nome da aplicação</label></p>
<p>
<select name="sel_aplic" id="aplication"><option value="">Escolha uma das opções</option><?php $Sql = mysql_query("SELECT * FROM `tbapplication` "); while ($rr = mysql_fetch_array($Sql)) { echo "<option value=".$rr['idtbApplication'].">".$rr['tbApplicationName']."</option>"; } ?></select>
</p>
<p><input type="submit" value="Iniciar avaliação"/></p>
</form>

	
<?php

$user = $_SESSION['user_id'];
$type = $_SESSION['user_type'];



?>

	
	
	
	<br>
	<hr>
	<br><br>
	<h3>Minhas avaliações</h3>
	<br>&nbsp;&nbsp;&nbsp; Obs: Abre na tela de 
	resultados<br>
</div>


<div id="footer">
Desenvolvimento: Ademir Marques Junior - 2016
</div>

</body>
</html>