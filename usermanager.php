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
            
         function anti_injection($string) {
				// remove palavras que contenham sintaxe sql
				$string = preg_replace("/(from|FROM|select|SELECT|insert|INSERT|delete|DELETE|where|WHERE|drop table|DROP TABLE|show tables|SHOW TABLES|#|\*|--|\\\\)/","",$string);
				$string = trim($string);//limpa espaços vazio
				$string = strip_tags($string);//tira tags html e php
				$string = addslashes($string);//Adiciona barras invertidas a uma string
				return $string;
			}
			
//----------Formulário para edição de usuário 
            if (isset($_POST['submitEditUser'])) {
            	$user_id = anti_injection($_POST['txt_user_id']);
            	$user = anti_injection($_POST['txt_nome']);
	            $email = anti_injection($_POST['txt_email']);
	            $user_level = anti_injection($_POST['txt_user_level']);
	            
	            echo '<h4>Editar usuário</h4>
						<form action="usermanager.php" method="post" name="form1" class="form-group" autocomplete="off">
						<input name="txt_user_id" value="'.$user_id.'" size="12" type="text" hidden/>
						<input maxlength="60" name="txt_nome" id="entravalor2" value="'.$user.'" size="50" class="form-control" required placeholder="Nome" autocomplete="off"/>
						<input name="txt_email" id="entravalor3" value="'.$email.'" size="40" type="email" class="form-control" required placeholder="Email/login" autocomplete="off"/>';
						//echo '<input name="txt_password1" id="entravalor4" value="    " size="12" type="password" class="form-control" required placeholder="Senha" autocomplete="off"/>';
		            //echo '<p>Repita a senha:</p> <p><input name="txt_password2" id="entravalor" size="12" maxlength="13" type="password" class="form-control" required /></p>';
		            //echo '<p><input value="Limpar" type="reset" class="btn btn-default">';
		            echo '<select name="sel_user_level" id="user_type" class="form-control">
				            	<option value="1" ';
				            	if ($user_level == 1) { echo 'selected';}
				            	echo '>Avaliador</option>
				            	<option value="2"';
				            	if ($user_level == 2) { echo 'selected';}
				            	echo '>Gerente</option>
				            	<option value="3"';
				            	if ($user_level == 3) { echo 'selected';}
				            	echo '>Administrador</option>			            	
		      				</select>';      	
		            echo '<input value="Salvar" type="submit" class="btn btn-default" name="submitUpdateUser">';
		            echo '</form>';
            	
            }
//----------Atualizar usuário            
            elseif (isset($_POST['submitUpdateUser'])) {
            	$user_id = anti_injection($_POST['txt_user_id']);
            	$user = anti_injection($_POST['txt_nome']);
	            $email = anti_injection($_POST['txt_email']);
	            $user_level = anti_injection($_POST['sel_user_level']);
	            
	            $Sql = "UPDATE `tbuser` SET `tbUserName` = '".$user."',`tbUserLevel` = '".$user_level."',`tbUserEmail` = '".$email."' WHERE `tbuser`.`idtbUser` = ".$user_id;
	            $rs = mysqli_query($conexao, $Sql) or die ("Erro na atualização!<br>");
								
	            if (mysqli_affected_rows($conexao)>0) {
	            	$to = $email;
						$subject = "[AvaliaQASWebE] Usuário cadastrado";
						$txt = "<html><head><title>HTML email</title></head>
							<body>
							<h3>Olá ".$user."</h3>
							<p>O seu cadastrado no sistema de avaliação AvaliaQASWebE foi alterado. Para saber mais sobre o sistema de avaliação clique <a href='http://avaliasewebs.caed-lab.com/index.php' target='_blank'>aqui</a>.</p>
							<p>Utilize o email abaixo como usuário de login juntamente com a senha criada anteriormente. Caso não saiba a sua senha clique <a href='http://avaliasewebs.caed-lab.com/cadastrar.php?password=forgoten' target='_blank'>aqui</a> para criar uma nova senha.</p>
							<p>Usuário ".$user." </p>
							<p><strong>Login: </strong>".$email."<p>";
							
							if ($user_level == 1) { $txt .= '<p>Usuário cadastrado como "Avaliador". O usuário "Avaliador" avalia sistemas educacionais disponíveis na sua página inicial indicados por um "Gerente de avaliações". </p>';}
							if ($user_level == 2) { $txt .= '<p>Usuário cadastrado como "Gerente". O usuário "Gerente" é o gerente de avaliações, criar e configurar avaliações de sistemas educacionais baseados em web semântica indicando usuários "Avaliadores", além de poder também se colocar como "Avaliador".</p>';}
							if ($user_level == 3) { $txt .= '<p>Usuário cadastrado como "Administrador". O usuário "Administrador" gerencia  usuários, o questionário de avaliações e sistemas avaliados. O usuário "Administrador" ainda pode se colocar como "Gerente". O usuário "Gerente" é o gerente de avaliações, podendo criar e configurar avaliações de sistemas educacionais baseados em web semântica, indicando usuários "Avaliadores", além de também se colocar como "Avaliador" caso necessário.</p>';}
							$txt .= "</body></html>";
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
						$headers .= 'From: <no-reply@caed-lab.com>' . "\r\n";
						
						mail($to,$subject,$txt,$headers);
	                echo "<script language='javascript' type='text/javascript'>
									swal({   title: '',   text: 'Alterado com sucesso',    type: 'success'  },function() { window.location.assign('usermanager.php'); });
								</script>";
	            } else {
	                echo "<script language='javascript' type='text/javascript'> swal('Erro!');";
	            }
	            
	            
            
            }        
            
            
//----------Incluir novo usuário            
            elseif (isset($_POST['submitNewUser'])) {
	            $user = anti_injection($_POST['txt_nome']);
	            $email = anti_injection($_POST['txt_email']);
	            $user_level = anti_injection($_POST['sel_user_type']);
	            $password = 0;
	            $valid = md5(time());
	            $Sql = "INSERT INTO `tbuser` (`idtbUser`, `tbUserName`, `tbUserEmail`, `tbUserPassword`, `tbUserLevel`, `tbUserValid`) VALUES (NULL, '" . $user . "', '" . $email . "', '" . $password . "', '".$user_level."', '" . $valid . "')";
	            
	            $rs = mysqli_query($conexao, $Sql) or die ("Erro na inserção!<br>");
								
	            if (mysqli_affected_rows($conexao)>0) {
	            	$to = $email;
						$subject = "[AvaliaQASWebE] Usuário cadastrado";
						$txt = "<html><head><title>HTML email</title></head>
							<body>
							<h3>Olá ".$user."</h3>
							<p>Você foi cadastrado com sucesso nos sistema de avaliação AvaliaQASWebE. Para saber mais sobre o sistema de avaliação clique <a href='http://avaliasewebs.caed-lab.com/index.php' target='_blank'>aqui</a>.</p>
							<p>Para validar o seu cadastro e criar uma senha clique no link abaixo:</p>
							<a href='http://avaliasewebs.caed-lab.com/cadastrar.php?valid=".$valid."' target='_blank'>http://avaliasewebs.caed-lab.com/cadastrar.php?valid=".$valid."</a>";
							if ($user_level == 1) { $txt .= '<p>Usuário cadastrado como "Avaliador". O usuário "Avaliador" avalia sistemas educacionais disponíveis na sua página inicial indicados por um "Gerente de avaliações". </p>';}
							if ($user_level == 2) { $txt .= '<p>Usuário cadastrado como "Gerente". O usuário "Gerente" é o gerente de avaliações, cria e configura avaliações de sistemas educacionais baseados em web semântica indicando usuários "Avaliadores", além de poder também se colocar como "Avaliador".</p>';}
							if ($user_level == 3) { $txt .= '<p>Usuário cadastrado como "Administrador". O usuário "Administrador" gerencia  usuários, o questionário de avaliações e sistemas avaliados. O usuário "Administrador" ainda pode se colocar como "Gerente". O usuário "Gerente" é o gerente de avaliações, podendo criar e configurar avaliações de sistemas educacionais baseados em web semântica, indicando usuários "Avaliadores", além de também se colocar como "Avaliador" caso necessário.</p>';}
							echo"</body></html>";
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
            } else {
	            
	//----------Exibir lista de usuários            
	            echo "<div class='panel panel-default'>
								<div class='panel-heading'><h3>Gerencie usuários</h3></div>
								<div class='panel-body'>";
	            
	            $Sql = "SELECT * FROM `tbuser` WHERE idtbUser > 1";
					$rs = mysqli_query($conexao, $Sql);
					echo '<table class="table table-bordered table-condensed table-hover">';
					echo '<th>Nome</th><th>Tipo</th><th>Email</th><th>Validado</th><th>Solicita gerência</th><th></th>';
					while ($row = mysqli_fetch_assoc($rs)) {
						echo '<form method="post" action="usermanager.php">
									<input name="txt_user_id" value="'.$row['idtbUser'].'" size="12" type="text" hidden/>
									<input name="txt_nome" id="entravalor2" value="'.$row['tbUserName'].'" size="40" type="text" hidden/>
									<input name="txt_email" id="entravalor3" value="'.$row['tbUserEmail'].'" size="40" type="text" hidden/>
									<input name="txt_user_level" id="entravalor5" value="'.$row['tbUserLevel'].'" size="40" type="text" hidden/>';
						echo '<tr>';
						
						echo '<td>'.$row['tbUserName'].'</td>';
						
						echo '<td>';			
						if ($row['tbUserLevel'] == 1) { echo 'Avaliador'; }
						elseif ($row['tbUserLevel'] == 2) { echo 'Gerente'; }
						else { echo 'Administrador'; }	
						echo '</td>';
						
						
						echo '<td>'.$row['tbUserEmail'].'</td>';
						echo '<td>';
						if ($row['tbUserValid'] != 1) { echo 'não validado'; }
						echo '</td>';
						
						echo '<td></td>';						
						
						echo '<td><div style="text-align: right;"><button type="submit" class="btn btn-danger btn-sm disabled" name="submitDelUser" data-toggle="tooltip" data-placement="bottom" title="Excluir usuário!"> <span class="glyphicon glyphicon-remove"></span> </button>
						<button type="submit" class="btn btn-default btn-sm" name="submitEditUser" data-toggle="tooltip" data-placement="bottom" title="Editar usuário!"> <span class="glyphicon glyphicon-pencil"></span> </button>
						<a class="btn btn-info btn-sm" href="profile.php?user='.$row['idtbUser'].'" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Visualizar perfil em uma nova página."> <span class="glyphicon glyphicon-zoom-in"></span> </a>
						</form></div></td></tr>';
						
						
					}
					echo '</table>';
					
					
						echo 'Inserir novo usuário<br>
						<form action="usermanager.php" method="post" name="form1" class="form-inline" autocomplete="off">
						<input maxlength="60" name="txt_nome" id="entravalor2" value="" size="50" class="form-control" required placeholder="Nome" autocomplete="off"/>
						<input name="txt_email" id="entravalor3" value=" " size="40" type="email" class="form-control" required placeholder="Email/login" autocomplete="off"/>';
						//echo '<input name="txt_password1" id="entravalor4" value="    " size="12" type="password" class="form-control" required placeholder="Senha" autocomplete="off"/>';
		            //echo '<p>Repita a senha:</p> <p><input name="txt_password2" id="entravalor" size="12" maxlength="13" type="password" class="form-control" required /></p>';
		            //echo '<p><input value="Limpar" type="reset" class="btn btn-default">';
		            echo '<select name="sel_user_type" id="user_type" class="form-control">
				            	<option value="" disabled selected hidden required>Tipo de usuário</option>
				            	<option value="1">Avaliador</option>
				            	<option value="2">Gerente</option>
				            	<option value="3">Administrador</option>			            	
		      				</select>';      	
		            echo '<input value="Salvar" type="submit" class="btn btn-default" name="submitNewUser" onclick="return validar()">';
		            echo '</form>';
	            
	            	echo "</div></div>";
         	}
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
