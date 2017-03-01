<?php
session_start();
include "valida.php";
include "language.php";
include "conecta.php";
?>
<!DOCTYPE html>
<html>

    <head>
        <meta content="text/html; charset=utf-8" http-equiv="content-type" />
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="slider/rzslider.css"/>
        <link rel="stylesheet" href="css/sweetalert.css">
        <script src="js/jquery-3.1.1.min.js"></script>        
        <script src="js/bootstrap.min.js"></script>
        <script src="js/sweetalert.js"></script>
<!-- 			<script src="slider/angular.min.js"></script>
        <script src="slider/rzslider.min.js"></script> -->
        <link rel="icon" type="image/png" href="favicon.png">
        <title>Avalia SEWebS</title>
    </head>

    <body>

        <div class="container-fluid">
            <?php
            include 'header.php';
            include 'navbar.php';
            ?>


            <?php
            
            if ($_SESSION['user_level'] <3) {
            	echo "<script> window.location.assign('index.php')</script>";	
            }

//----------Incluir novo usuário            
            if (isset($_POST['submitNewUser'])) {
	            $user = $_POST['txt_nome'];
	            $email = $_POST['txt_email'];
	            $password = 0;
	            $valid = md5(time());
	            $Sql = "INSERT INTO `tbuser` (`idtbUser`, `tbUserName`, `tbUserEmail`, `tbUserPassword`, `tbUserLevel`, `tbUserValid`) VALUES (NULL, '" . $user . "', '" . $email . "', '" . $password . "', '1', '" . $valid . "')";
	            
	            $rs = mysqli_query($conexao, $Sql) or die ("Erro na inserção!<br>");
								
	            if (mysqli_affected_rows($conexao)>0) {
	            	$to = $email;
						$subject = "[AvaliaQASWebE] Usuário cadastrado";
						$txt = "<html><head><title>HTML email</title></head>
							<body>
							<h3>Olá ".$user."</h3>
							<p>Você foi cadastrado com sucesso nos sistema de avaliação AvaliaQASWebE. Para saber mais sobre o sistema de avaliação clique <a href='http://avaliasewebs.caed-lab.com/index.php' target='_blank'>aqui</a>.</p>
							<p>Para validar o seu cadastro e criar uma senha clique no link abaixo:</p>
							<a href='http://avaliasewebs.caed-lab.com/cadastrar.php?valid=".$valid."' target='_blank'>http://avaliasewebs.caed-lab.com/cadastrar.php?valid=".$valid."</a>
							</body>
							</html>";
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
						$headers .= 'From: <no-reply@caed-lab.com>' . "\r\n";
						
						mail($to,$subject,$txt,$headers);
	                echo "<script language='javascript' type='text/javascript'>
									swal({   title: '',   text: 'Cadastro realizado com sucesso',    type: 'success'  });
								</script>";
	            } else {
	                echo "<script language='javascript' type='text/javascript'> swal('Erro!');";
	            }
            }
            
//----------Exibir lista de usuários            
            echo "<div class='panel panel-default'>
							<div class='panel-heading'><h3>Gerencie usuários</h3></div>
							<div class='panel-body'>";
            
            $Sql = "SELECT * FROM `tbuser` WHERE idtbUser > 1";
				$rs = mysqli_query($conexao, $Sql);
				while ($row = mysqli_fetch_assoc($rs)) {
					echo '<form method="post" action="usermanager.php">';
					echo '<strong>Nome:</strong> '.$row['tbUserName'].'<br><strong>Login/email:</strong> '.$row['tbUserEmail'].'<br>';
					echo '
					
					<button type="submit" class="btn btn-danger btn-sm disabled" name="submitDelUser" data-toggle="tooltip" data-placement="bottom" title="Excluir usuário!"> <span class="glyphicon glyphicon-remove"></span> </button>
					<button type="submit" class="btn btn-default btn-sm" name="submitEditUser" data-toggle="tooltip" data-placement="bottom" title="Editar usuário!"> <span class="glyphicon glyphicon-pencil"></span> </button>
					<button type="submit" class="btn btn-info btn-sm" name="submitViewUser" data-toggle="tooltip" data-placement="bottom" title="Visualizar perfil!"> <span class="glyphicon glyphicon-zoom-in"></span> </button>
					</form><hr>';
					
					
				}
					echo 'Inserir novo usuário<br>
					<form action="usermanager.php" method="post" name="form1" class="form-inline" autocomplete="off">
					<input maxlength="60" name="txt_nome" id="entravalor2" value="" size="50" class="form-control" required placeholder="Nome" autocomplete="off"/>
					<input name="txt_email" id="entravalor3" value=" " size="40" type="email" class="form-control" required placeholder="Email/login" autocomplete="off"/>';
					//echo '<input name="txt_password1" id="entravalor4" value="    " size="12" type="password" class="form-control" required placeholder="Senha" autocomplete="off"/>';
	            //echo '<p>Repita a senha:</p> <p><input name="txt_password2" id="entravalor" size="12" maxlength="13" type="password" class="form-control" required /></p>';
	            //echo '<p><input value="Limpar" type="reset" class="btn btn-default">';
	            echo '<input value="Salvar" type="submit" class="btn btn-default" name="submitNewUser" onclick="return validar()">';
	            echo '</form>';
            
            echo "</div></div>";
            ?>

            <hr>

<?php
include 'footer.php';
?>
        </div>
        <script>

            $(function(){
    				$('[data-toggle=tooltip]').tooltip();
 				});
        </script>        
        
    </body>
</html>
