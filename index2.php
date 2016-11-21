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



?>



<h3>Cadastrar uma nova aplicação e iniciar avaliação</h3>

<form action="index2.php" method="post" name="form1">
<p><label>Nome da aplicação</label></p>
<p>
<input name="txt_aplic" type="text" id="entravalor"  size="13" maxlength="13" style="width: 516px"  required/></p>
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