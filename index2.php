<?php session_start(); ?>
<!DOCTYPE html>
<html>

    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
        <!-- <link rel="stylesheet" href="estilo.css" type="text/css" media="screen" /> -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link rel="icon" type="image/png" href="favicon.png">
        <title>Avalia SEWebS</title>
    </head>

    <body>

        <div class="container-fluid">
            <div class="jumbotron">
                <h2>Modelo de Avaliação de Qualidade dos Sistemas Educacionais
                    baseados em Web Semântica (SEWebS) </h2>
            </div>
            <div id="login" class="well well-sm">
                <?php
                include("valida.php");
                ?></div>
                
            <?php
            include 'navbar.php';
            ?>
                
            <?php
            include "conecta.php";

            $user = $_SESSION['user_id'];
            $type = $_SESSION['user_type'];
			
		if (isset($_POST['txt_aplic']) OR isset($_POST['sel_aplic'])) {
			
            $applic_id = NULL;

            if ((isset($_POST['txt_aplic'])) AND ( $_POST['txt_aplic'] != '')) {
                $aplic = $_POST['txt_aplic'];
                //$aplic_desc = $_POST['txt_aplic_desc'];

                $Sql = "SELECT * FROM tbapplication WHERE tbapplicationname = '" . $aplic . "'";
                $rs = mysqli_query($conexao, $Sql) or die("Erro busca aplicação");

                $i = 0;
				while ($linha = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
            		$i++;
				}
                //$applic_id = $linha["idtbApplication"];
				

                if ($i) {
                    //Trocar confirm() por equivalente em 'SweetAlert'
                    echo "<script language='javascript' type='text/javascript'> if(!confirm('Já existe uma aplicação com esse nome. Deseja avaliar a aplicação encontrada?')) {window.location.href='index2.php'; } </script>";

                    $linha = mysqli_fetch_array($rs, MYSQLI_ASSOC);
                    $applic_id = $linha["idtbApplication"];
                    $_SESSION['appic_id'] = $applic_id;
                } else {
                    $Sql2 = "INSERT INTO `tbapplication` (`idtbApplication`, `tbApplicationName`, `tbApplicationDescription`) VALUES (NULL, '" . $aplic . "', '" . $aplic_desc . "')";
                    $rs2 = mysqli_query($conexao, $Sql2) or die ("Erro insere aplicação");

                    //obter o id da aplicação inserida. Mudar essa sessão por uma função do mysql para obter autoincrement
                    if ($rs2) {
                        $Sql3 = "SELECT * FROM `tbapplication` WHERE `tbApplicationName` = '" . $aplic . "'";
                        $rs3 = mysqli_query($conexao, $Sql3) or die("Erro busca id aplicação");
                        $linha3 = mysqli_fetch_array($rs3, MYSQLI_ASSOC);
                        $applic_id = $linha3["idtbApplication"];
                        $_SESSION['appic_id'] = $applic_id;
                    }
                }
            }
            if (isset($_POST['sel_aplic'])) {
            	$applic_id = $_POST['sel_aplic'];
                $_SESSION['appic_id'] = $_POST['sel_aplic'];
            }


            if (isset($_SESSION['appic_id'])) {
                //inserir um novo formulário em tbform
                $Sql = "SELECT * FROM tbform WHERE tbapplication_idtbapplication = '" . $applic_id . "' AND tbuser_idtbUser = '" . $user . "'";
                $rs = mysqli_query($conexao, $Sql);
                $linha = mysqli_fetch_array($rs, MYSQLI_ASSOC);
                $form = $linha["idtbForm"];
                print_r($form);
                
                if ($form == '') {
	                $Sql2 = "INSERT INTO `tbform` (`idtbform`, `tbapplication_idtbapplication`, `tbuser_idtbuser`) VALUES (NULL, '" . $applic_id . "', '" . $user . "')";
	                $rs2 = mysqli_query($conexao, $Sql2);
	                
	                
	
	                //obter o id do form inserido
	                if ($rs) {
	                    $Sql3 = "SELECT * FROM `tbform` WHERE `tbapplication_idtbapplication` = '" . $applic_id . "' AND `tbuser_idtbuser` = '" . $user . "'";
	                    $rs3 = mysqli_query($conexao, $Sql3) or die("Erro busca id formulário");
	                    $linha3 = mysqli_fetch_array($rs3, MYSQLI_ASSOC);
	                    $form_id = $linha3["idtbForm"];
	                    $_SESSION['form_id'] = $form_id;
	                    echo "<script> window.location.assign('form.php')</script>";
	                    //header('Location:form.php');
	                }
                }
			}		
        }
            ?>
            <h3>Cadastrar uma nova aplicação e iniciar avaliação</h3>
            <form action="index2.php" class="form-group" method="post" name="form1">
                <p><label>Nome da aplicação</label></p>
                <p>
                    <input id="entravalor" class="form-control" name="txt_aplic" required="" type="text" /></p>
                <p>
                    <input class="btn btn-default" type="submit" value="Iniciar avaliação" /></p>
            </form>
            <hr>
            <h3>Avalie uma dessas aplicações</h3>
            <form action="index2.php" class="form-group" method="post" name="form2">
                <p><label>Nome da aplicação</label></p>
                <p><select id="aplication" class="form-control" name="sel_aplic">
                	<option value="">Escolha uma das opções</option>
					<?php $Sql = mysql_query("SELECT * FROM tbapplication INNER JOIN tbform ON tbapplication.idtbApplication = tbform.tbApplication_idtbApplication WHERE tbform.tbUser_idtbUser != ".$_SESSION['user_id']);
					while ($rr = mysql_fetch_array($Sql)) {
					    echo "<option value=" . $rr['idtbApplication'] . ">" . $rr['tbApplicationName'] . "</option>";
					} ?>
                    </select> </p>
                <p>
                    <input class="btn btn-default" type="submit" value="Iniciar avaliação" /></p>
            </form>
            <?php
            $user = $_SESSION['user_id'];
            $type = $_SESSION['user_type'];
            ?><hr>
            <h3>Minhas avaliações concluídas</h3>
            <?php
            $i = 0;
            $Sql = "SELECT * FROM tbform INNER JOIN tbapplication ON tbapplication.idtbApplication = tbform.tbApplication_idtbApplication WHERE tbform.tbformCompleted = 1 AND tbform.tbUser_idtbUser = " . $_SESSION['user_id'];
            $rs = mysqli_query($conexao, $Sql);
            while ($linha = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
                echo "<span class='glyphicon glyphicon-check'></span><a href='results.php?form=" . $linha['idtbForm'] . "'>" . $linha['tbApplicationName'] . "</a><br>";
 				$i++;
           }
            if ($i == 0) echo "Não há avaliações concluídas<br>";
            echo "<p></p>";

            
            ?><hr>
            
            
            <h3>Avaliações pendentes</h3>
            <?php
            $i = 0;
            $Sql = "SELECT * FROM tbform INNER JOIN tbapplication ON tbapplication.idtbApplication = tbform.tbApplication_idtbApplication WHERE tbform.tbformCompleted = 0 AND tbform.tbUser_idtbUser = " . $_SESSION['user_id'];
            $rs = mysqli_query($conexao, $Sql);
            
            while ($linha = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
                echo "<span class='glyphicon glyphicon-expand'></span><a href='results.php?form=" . $linha['idtbForm'] . "'>" . $linha['tbApplicationName'] . "</a><br>";
                $i++;
            }
            if ($i == 0) echo "Não há nenhuma avaliação pendente<br>";
            echo "<p></p>";
            ?>
			<?php
				//unset($_SESSION['applic_id']);
				//unset($_SESSION['form_id']);
			
			?>
			<hr>
            <div id="footer" class="well well-sm">
                Desenvolvimento: Ademir Marques Junior - 2016 </div>
        </div>

    </body>

</html>
