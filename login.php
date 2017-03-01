<?php session_start(); 
if(isset($_SESSION['user_login'])) {
	echo "<script> window.location.assign('index3.php')</script>";
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
    <script src="dist/sweetalert.js"></script>
    <link rel="stylesheet" href="dist/sweetalert.css">
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
		<input id="entravalor" class="form-control" name="txt_user" required="" type="email" /></p>
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
		$password = trim($_POST['txt_password']);
		
		$password = md5($password);
		
		$Sql="SELECT * FROM `tbuser` WHERE `tbuseremail` = '".$user."' AND `tbuserpassword` = '".$password."'";
		//$Sql = "SELECT * FROM `tbuser` WHERE `tbEmail` = 'admin@admin' AND `tbPassword` = '21232f297a57a5a743894a0e4a801fc3'";
		$rs = mysqli_query($conexao, $Sql) or die ("<script language='javascript' type='text/javascript'>
								swal({   title: '',   text: 'Usuário ou senha incoreta!',    type: 'error'  },  function(){    window.location.href = 'login.php';});
							</script>");
		
		if (mysqli_num_rows($rs)) {	
			$row = mysqli_fetch_array($rs, MYSQLI_ASSOC);
			
			if($row['tbUserValid'] > 1) {
				echo "<script language='javascript' type='text/javascript'>
								swal({   title: 'Usuário ainda não validado!',   text: 'Verifique em sua caixa de email o email com o link de validação. ',    type: 'error'  },  function(){    window.location.href = 'login.php';});
							</script>";
							exit();
				}
			$_SESSION['user_login'] = $row["tbUserName"];
			$_SESSION['user_id'] = $row["idtbUser"];
			$_SESSION['user_level'] = $row["tbUserLevel"];	
			echo "<script> window.location.assign('index3.php')</script>";
			//header('Location:index2.php');
			
		} else { 
			echo "<script language='javascript' type='text/javascript'>
								swal({   title: '',   text: 'Usuário ou senha incoreta!',    type: 'error'  },  function(){    window.location.href = 'login.php';});
							</script>";
	   }
	}
			
?>
            <?php
            include 'footer.php';
            ?>
</div>

</body>

</html>
