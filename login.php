<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="estilo.css" type="text/css" media="screen" />
<title></title>
</head>
<body>

<div id="header">
Cabeçalho aqui
</div>
<div id="login">
<?php
//include("valida.php");
echo 'Bem vindo visitante';
?>
</div>


<h3>Login de usuário</h3>
<?php
session_start();
if(isset($_SESSION['user_login'])) {
echo "Bem vindo '".$_SESSION['user_login']."' ";
echo "<a href='logout.php'>Sair</a>";
exit();
} 
?>

<div id="content">

<form action="login.php" method="post" name="form1">
<p><label>Email</label></p>
<p>
<input name="txt_usuario" type="text" id="entravalor"  size="13" style="width: 219px" required/></p>
<p><label>Senha</label></p>
<p>
<input name="txt_senha" type="password" id="entravalor"  size="13" maxlength="13" style="width: 218px"  required/></p>
<p><input type="submit" value="login"/></p>
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
</div>

<div id="footer">
Desenvolvimento: Ademir Marques Junior - 2016
</div>


</body>
</html>