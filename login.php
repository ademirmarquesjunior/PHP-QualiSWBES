<?php session_start(); 
if(isset($_SESSION['user_login'])) {
	echo "<script> window.location.assign('index2.php')</script>";
	//header('Location:index.php');
	exit();
} 
?>
<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type" />
<!-- <link rel="stylesheet" href="estilo.css" type="text/css" media="screen" /> -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link rel="icon" type="image/png" href="favicon.png">
<title>Avalia SEWebS</title>
</head>

<body>

<div class="container-fluid">
            <?php
            include 'header.php';
            include 'navbar.php';
            ?>

	<h3>Login de usuário</h3>

	<form action="login.php" class="form-group" method="post" name="form1">
		<p><label>Email</label></p>
		<p>
		<input id="entravalor" class="form-control" name="txt_user" required="" type="text" /></p>
		<p><label>Senha</label></p>
		<p>
		<input id="entravalor" class="form-control" name="txt_password" required="" type="password" /></p>
		<p><input class="btn btn-default" type="submit" value="login"></p>
	</form>
	Clique <a href="cadastrar.php">aqui</a> para se cadastrar e ter acesso ao sistema.
	<?php

	include "conecta.php";
	
	if (isset($_POST['txt_user'])) {
		$user = trim($_POST['txt_user']); 
		$password = md5(trim($_POST['txt_password']));
		
		$Sql="SELECT * FROM `tbuser` WHERE `tbUserEmail` = '".$user."' AND `tbUserPassword` = '".$password."'";
		//$Sql = "SELECT * FROM `tbuser` WHERE `tbEmail` = 'admin@admin' AND `tbPassword` = '21232f297a57a5a743894a0e4a801fc3'";
		$rs=mysqli_query($conexao, $Sql) or die ("<script language='javascript' type='text/javascript'> alert('Usuário ou senha incorreta'); window.location.href='login.php'; </script>");
		
		//echo $rs;
		
		while($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
			$user_id=$row["idtbUser"];
			$user_name=$row["tbUserName"];
		}
			
		if ($rs!=false) {		
			$_SESSION['user_login'] = $user_name;
			$_SESSION['user_id']=$user_id;
			mysql_close($conexao);
			echo "<script> window.location.assign('index2.php')</script>";
			//header('Location:index2.php');
			
		}
	}
			
?>
            <?php
            include 'footer.php';
            ?>
</div>

</body>

</html>
