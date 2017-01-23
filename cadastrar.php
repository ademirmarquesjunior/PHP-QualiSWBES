<?php session_start(); 
if(isset($_SESSION['user_login'])) {
	echo "<script> window.location.assign('index2.php')</script>";
	//header('Location:index.php');
	exit();
}
?>
<!DOCTYPE html>

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
            
			<h3>Cadastre-se:</h3>
			<?php
        include "conecta.php";

        if (isset($_POST['txt_nome'])) {

				//salvar usuario
            $user = $_POST['txt_nome'];
            $email = $_POST['txt_email'];
            $password = md5($_POST['txt_password1']);

            $Sql = "INSERT INTO `tbuser` (`idtbUser`, `tbUserName`, `tbUserEmail`, `tbUserPassword`) VALUES (NULL, '" . $user . "', '" . $email . "', '" . $password . "')";

            $rs = mysqli_query($conexao, $Sql) or die ("<script language='javascript' type='text/javascript'>
								swal({   title: '',   text: 'Já existe um usuário com esse email!',    type: 'error'  },  function(){    window.location.href = 'cadastrar.php';});
							</script>");
            

            if ($rs) {
            	$_SESSION['user_login'] = $user;
					$_SESSION['user_id'] = mysqli_insert_id($conexao);
                echo "<script language='javascript' type='text/javascript'>
								swal({   title: '',   text: 'Cadastro realizado com sucesso',    type: 'success'  },  function(){    window.location.href = 'index2.php';});
							</script>";
            } else {
                echo "<script language='javascript' type='text/javascript'> swal('Erro!'); window.location.href='index.html';</script>";
            }
        } else {
            echo '<form action="cadastrar.php" method="post" name="form1" class="form-group">';
            echo '<p>Nome Completo: </p> <p><input maxlength="60" name="txt_nome" id="entravalor2" size="50" class="form-control" required /></p>';
            echo '<p>Email para login:</p> <p><input name="txt_email" id="entravalor3" size="40" type="email" class="form-control" required /></p>';
            echo '<p>Senha:</p> <p><input name="txt_password1" id="entravalor4" size="12" type="password" class="form-control" required /></p>';
            echo '<p>Repita a senha:</p> <p><input name="txt_password2" id="entravalor" size="12" maxlength="13" type="password" class="form-control" required /></p>';
            //echo '<p><input value="Limpar" type="reset" class="btn btn-default">';
            echo '<p><input value="Enviar" type="submit" class="btn btn-default" onclick="return validar()"></p>';
            echo '</form>';
        }
        ?>
        
        <script language="javascript" type="text/javascript">
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
