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
	<p>Clique <a href="cadastrar.php">aqui</a> para se cadastrar e ter acesso ao sistema.</p>
	
	<p>Caso tenha esquecido a senha clique <a href="cadastrar.php?password=forgotten">aqui</a>.</p>
	<?php

	include "conecta.php";
	
         function anti_injection($string) {
				// remove palavras que contenham sintaxe sql
				$string = preg_replace("/(from|FROM|select|SELECT|insert|INSERT|delete|DELETE|where|WHERE|drop table|DROP TABLE|show tables|SHOW TABLES|#|\*|--|\\\\)/","",$string);
				$string = trim($string);//limpa espaços vazio
				$string = strip_tags($string);//tira tags html e php
				$string = addslashes($string);//Adiciona barras invertidas a uma string
				return $string;
			}	
	
	if (isset($_POST['txt_user'])) {
		$user = anti_injection($_POST['txt_user']); 
		$password = anti_injection($_POST['txt_password']);
		
		$password = md5($password);
		
		$Sql="SELECT * FROM `tbuser` WHERE `tbuseremail` = '".$user."' AND `tbuserpassword` = '".$password."'";
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
						
			//Verificar se o usuário já tem perfil preenchido			
			$Sql = "SELECT * FROM `tbuserprofile` WHERE `tbUser_idtbUser` = ".$_SESSION['user_id'];
			$rs = mysqli_query($conexao, $Sql);			
			if(mysqli_num_rows($rs) == 1) {
				echo "<script> window.location.assign('index3.php')</script>";
			} else {
				echo "<script> window.location.assign('profile.php')</script>";
			}
			
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
