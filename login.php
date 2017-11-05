<?php session_start(); 
	if(isset($_SESSION['user_login'])) {
		echo "<script> window.location.assign('index3.php')</script>";
		//header('Location:index.php');
		exit();
	} 
	include "language.php";
	include "conecta.php";
	include "function.inc.php";
?>
<!DOCTYPE html>
<html>
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="content-type" />
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet">
		<script src="dist/sweetalert.js"></script>
		<link rel="stylesheet" href="dist/sweetalert.css">
		<link rel="icon" type="image/png" href="favicon.png">
		<title><?php echo $lang['PAGE_TITLE']; ?></title>
	</head>
	<body>
		<div class="container-fluid">
<?php
	include 'header.php';
	include 'navbar.php';
?>
			<div class='panel panel-default'>
				<div class='panel-heading'><h3><?php echo $lang['LOGIN_USER_LOGIN']; ?></h3>
				</div>
				<div class='panel-body'>
					<form action="login.php" class="form-group" method="post" name="form1">
						<p><label><?php echo $lang['LOGIN_EMAIL']; ?></label></p>
						<p><input id="entravalor" class="form-control" name="txt_user" required="" type="email" /></p>
						<p><label><?php echo $lang['LOGIN_PASSWORD']; ?></label></p>
						<p><input id="entravalor" class="form-control" name="txt_password" required="" type="password" /></p>
						<p><input class="btn btn-default" type="submit" value="login"></p>
					</form>
					<p><?php echo $lang['LOGIN_MESSAGE_SIGNUP']; ?></p>
					<p><?php echo $lang['LOGIN_MESSAGE_CHANGE_PASSWORD']; ?></p>
				</div>
			</div>

<?php
	if (isset($_POST['txt_user'])) {
		$user = anti_injection($_POST['txt_user']); 
		$password = anti_injection($_POST['txt_password']);
		$password = md5($password);

		$Sql="SELECT * FROM `tbuser` WHERE `tbuseremail` = '".$user."' AND `tbuserpassword` = '".$password."'";
		$rs = mysqli_query($conexao, $Sql) or die ("<script language='javascript' type='text/javascript'>
			swal({   title: '',   text: '".$lang['LOGIN_WRONG_LOGIN']."',    type: 'error'  },  function(){    window.location.href = 'login.php';});
		</script>");

		if (mysqli_num_rows($rs)) {	
			$row = mysqli_fetch_array($rs, MYSQLI_ASSOC);
			if($row['tbUserValid'] > 1) { //Verifica se o usuário já foi validado
				echo "<script language='javascript' type='text/javascript'>
					swal({   title: '".$lang['LOGIN_NOT_VALIDATED_1']."',   text: '".$lang['LOGIN_NOT_VALIDATED_2']."',    type: 'error'  },  function(){    window.location.href = 'login.php';});
				</script>";
				exit();
			}

			//Iniciar as variáveis de sessão
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
					swal({   title: '',   text: '".$lang['LOGIN_WRONG_LOGIN']."',    type: 'error'  },  function(){    window.location.href = 'login.php';});
				  </script>";
		}
	}

include 'footer.php';
?>
		</div>
	</body>
</html>