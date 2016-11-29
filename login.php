<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type" />
<!-- <link rel="stylesheet" href="estilo.css" type="text/css" media="screen" /> -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<title></title>
</head>

<body>

<div class="container-fluid">
	<div class="jumbotron">
		<h2>Modelo de Avaliação de Qualidade dos Sistemas Educacionais
baseados em Web Semântica (SEWebS) </h2>
	</div>
	<div id="login">
		<?php
//include("valida.php");
echo 'Bem vindo visitante';
?></div>
	<h3>Login de usuário</h3>
	<?php
session_start();
if(isset($_SESSION['user_login'])) {
echo "Bem vindo '".$_SESSION['user_login']."' ";
echo "<a href='logout.php'>Sair</a>";
exit();
} 
?>
	<form action="login.php" class="form-group" method="post" name="form1">
		<p><label>Email</label></p>
		<p>
		<input id="entravalor" class="form-control" name="txt_usuario" required="" type="text" /></p>
		<p><label>Senha</label></p>
		<p>
		<input id="entravalor" class="form-control" name="txt_senha" required="" type="password" /></p>
		<p><input class="btn btn-default" type="submit" value="login"></p>
	</form>
	Clique <a href="cadastrar.php">aqui</a> para se cadastrar e ter acesso ao sistema.
	<?php

	include "conecta.php";
	
	if (isset($_POST['txt_usuario'])) {
		$usuario = trim($_POST['txt_usuario']); 
		$senha = trim($_POST['txt_senha']);
		
		$senha = md5($senha);
	

		$Sql="SELECT * FROM `tbuser` WHERE `tbEmail` = '".$usuario."' AND `tbPassword` = '".$senha."'";
		//$Sql = "SELECT * FROM `tbuser` WHERE `tbEmail` = 'admin@admin' AND `tbPassword` = '21232f297a57a5a743894a0e4a801fc3'";
		echo $Sql;
		$rs=mysql_query($Sql, $conexao) or die ("<script language='javascript' type='text/javascript'> alert('Usuário ou senha incorreta'); window.location.href='login.php'; </script>");
		
		echo $rs;
		
		while($linha = mysql_fetch_array($rs))
			{
			$user_id=$linha["idtbUser"];
			$user_name=$linha["tbNome"];
			echo $user_name;
			$user_type=$linha["tbUserType_idtbUserType"];
			}
			
			echo $user_name;
			
			if ($rs!=false) {		
			$_SESSION['user_login'] = $user_name;
			$_SESSION['user_id']=$user_id;
			$_SESSION['user_type']=$user_type;
			mysql_close($conexao);
			header('Location:index2.php');
			
			}
	}
			
?>
	<div id="footer" class="well well-sm">
		Desenvolvimento: Ademir Marques Junior - 2016 </div>
</div>

</body>

</html>
