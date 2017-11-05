<?php 
	//Expulsar o usuário da página caso o mesmo já estiver logado
	session_start(); 
	if(isset($_SESSION['user_login'])) {
		echo "<script> window.location.assign('index3.php')</script>";
		exit();
	}

	//Lista de includes
	include "language.php";
	include "conecta.php";
	include "function.inc.php";
?>
<!DOCTYPE html>
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
				<div class='panel-heading'><h3><?php echo $lang['SIGNUP_SIGNUP']; ?></h3>
				</div>
				<div class='panel-body'>
<?php
	//-------Verificar se o valor de validação é válido e pedir nova senha ou ativar o usuário se ele já tiver uma senha			
	if (isset($_GET['valid'])) {
		$valid = anti_injection($_GET['valid']);

		$Sql = "SELECT * FROM `tbuser` WHERE `tbUserValid` = '".$valid."'";
		$rs = mysqli_query($conexao, $Sql) or die ("Erro ao buscar usuário");

		if (mysqli_num_rows($rs) == 0) {
			echo "<script language='javascript' type='text/javascript'>
				swal({   title: '',   text: '".$lang['SIGNUP_MESSAGE_NOT_VALID']."',    type: 'error'  },  function(){    window.location.href = 'index.php';});
				</script>";	
		} else {
			$row = mysqli_fetch_assoc($rs);
			$user = $row['tbUserName'];
			$email = $row['tbUserEmail'];
			$user_id = $row['idtbUser'];
			$password = $row['tbUserPassword'];
			$user_level = $row['tbUserLevel'];

			if ($password == 0) {
				echo  $lang['SIGNUP_MESSAGE_PASSWORD'].':
				<form action="cadastrar.php" method="post" name="form1" class="form-group">
					<input name="txt_user_id" value="'.$user_id.'" size="12" type="text" hidden/>
					<p>'.$lang['SIGNUP_PASSWORD'].':</p> <p><input name="txt_password1" id="entravalor4" size="12" type="password" class="form-control" required /></p>
					<p>'.$lang['SIGNUP_REPEAT_PASSWORD'].':</p> <p><input name="txt_password2" id="entravalor" size="12" maxlength="13" type="password" class="form-control" required /></p>
					<p><input value="'.$lang['SIGNUP_BTN_SAVE'].'" type="submit" name="submitUpdatePassword" class="btn btn-default" onclick="return validar()"></p>
				</form>';
			} else {
				$Sql = "UPDATE `tbuser` SET `tbUserValid` = '1' WHERE `tbuser`.`idtbUser` = ".$user_id;
				$rs = mysqli_query($conexao, $Sql) or die ("<br>Erro ao atualizar usuário");
				$_SESSION['user_login'] = $user;
				$_SESSION['user_id'] = $user_id;
				$_SESSION['user_level'] = $user_level;
				echo "<script language='javascript' type='text/javascript'>
				swal({   title: '',   text: '".$lang['SIGNUP_MESSAGE_SUCCESS_ACTIVATED']."',    type: 'success'  },  function(){    window.location.href = 'index3.php';});
				</script>";
			}	
		}
	}	elseif (isset($_POST['submitUpdatePassword'])) {

		$user_id = anti_injection($_POST['txt_user_id']);
		$password = md5($_POST['txt_password1']);
		$Sql = "UPDATE `tbuser` SET `tbUserPassword` = '".$password."',`tbUserValid` = '1' WHERE `tbuser`.`idtbUser` = ".$user_id;
		$rs = mysqli_query($conexao, $Sql) or die ("Erro ao atualizar senha");
		$_SESSION['user_login'] = $user;
		$_SESSION['user_id'] = $user_id;
		$_SESSION['user_level'] = $user_level;
		echo "<script language='javascript' type='text/javascript'>
				swal({   title: '',   text: '".$lang['SIGNUP_MESSAGE_SUCCESS_PASSWORD']."',    type: 'success'  },  function(){    window.location.href = 'index3.php';});
				</script>";

	}	elseif (isset($_POST['submitNewUser'])) {
		$user = anti_injection($_POST['txt_nome']);
		$email = anti_injection($_POST['txt_email']);
		$password = md5($_POST['txt_password1']);
		$valid = md5(time());            

		$Sql = "INSERT INTO `tbuser` (`idtbUser`, `tbUserName`, `tbUserEmail`, `tbUserPassword`, `tbUserLevel`, `tbUserValid`) VALUES (NULL, '" . $user . "', '" . $email . "', '" . $password . "', '1', '".$valid."')";            
		$rs = mysqli_query($conexao, $Sql) or die ("<script language='javascript' type='text/javascript'>
			swal({   title: '',   text: '".$lang['SIGNUP_MESSAGE_FAIL_ACCOUNT']."',    type: 'error'  },  function(){    window.location.href = 'cadastrar.php';});
		</script>");

		if (mysqli_affected_rows($conexao)>0) {

			notifyNewUser($email,$user,NULL);            	

			echo "<script language='javascript' type='text/javascript'>
			swal({   title: '',   text: '".$lang['SIGNUP_MESSAGE_SUCCESS']."',    type: 'success'  },  function(){    window.location.href = 'login.php';});
		</script>";
		} else {
			echo "<script language='javascript' type='text/javascript'> swal('Erro!'); window.location.href='index.html';</script>";
		}
	} elseif (isset($_POST['submitEmail'])) {
		$email = anti_injection($_POST['txt_email']);
		$valid = md5(time());

		$Sql = "SELECT * FROM `tbuser` WHERE tbUserEmail = '".$email."'";
		$rs = mysqli_query($conexao, $Sql) or die ("Erro");
		$rs = mysqli_query($conexao, $Sql) or die ("Erro ao buscar usuário");

		if (mysqli_num_rows($rs) == 0) {
			echo "<script> window.location.assign('index3.php')</script>";	
		} else {
			$row = mysqli_fetch_assoc($rs);
			$user = $row['tbUserName'];
			$user_id = $row['idtbUser'];
		}

		$Sql = "UPDATE `tbuser` SET `tbUserPassword` = 0,`tbUserValid` = '".$valid."' WHERE `tbuser`.`idtbUser` = ".$user_id;
		$rs = mysqli_query($conexao, $Sql) or die ("Erro ao atualizar usuário");

		if (mysqli_affected_rows($conexao)>0) {			

			notifyNewPassword ($email,$user,NULL);

			echo "<script language='javascript' type='text/javascript'>
			swal({   title: '',   text: '".$lang['SIGNUP_MESSAGE_SUCCESS']."',    type: 'success'  },  function(){    window.location.href = 'login.php';});
		</script>";
		} else {
			echo "<script language='javascript' type='text/javascript'> swal('Erro!'); window.location.href='index.html';</script>";
		}

	} elseif (isset($_GET['password'])) {
		echo $lang['SIGNUP_MESSAGE_FORGOTTEN_PASSWORD'].':
		<form action="cadastrar.php" method="post" name="form1" class="form-group">
			<input name="txt_email" id="entravalor4" type="text" class="form-control" required /></p>
			<p><input value="'.$lang['SIGNUP_BTN_SAVE'].'" type="submit" name="submitEmail" class="btn btn-default" onclick="return validar()"></p>
		</form>';

	} else {
		echo '<form action="cadastrar.php" method="post" name="form1" class="form-group">
				<p>'.$lang['SIGNUP_NAME'].': </p> <p><input maxlength="60" name="txt_nome" id="entravalor2" size="50" class="form-control" required /></p>
				<p>'.$lang['SIGNUP_EMAIL'].':</p> <p><input name="txt_email" id="entravalor3" size="40" type="email" class="form-control" required /></p>
				<p>'.$lang['SIGNUP_PASSWORD'].':</p> <p><input name="txt_password1" id="entravalor4" size="12" type="password" class="form-control" required /></p>
				<p>'.$lang['SIGNUP_REPEAT_PASSWORD'].':</p> <p><input name="txt_password2" id="entravalor" size="12" maxlength="13" type="password" class="form-control" required /></p>
				<p><input value="'.$lang['SIGNUP_BTN_SAVE'].'" type="submit" name="submitNewUser" class="btn btn-default" onclick="return validar()"></p>
			</form>';
	}
?>

				</div>
			</div>
			<script language="javascript" type="text/javascript">
				/**
				 * Validar se os dois campos de senha são iguais
				 * @return type
				 */
				function validar() {
					var password1 = document.form1.txt_password1.value;
					var password2 = document.form1.txt_password2.value;

					if (password1 != password2) {
						alert('Senhas diferentes');
						form1.txt_password1.focus();
						return false;
					}
				}
			</script>
<?php
include 'footer.php';
?>
		</div>
	</body>
</html>