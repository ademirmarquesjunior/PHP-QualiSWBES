<!DOCTYPE html>
<html>

    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
        <!-- <link rel="stylesheet" href="estilo.css" type="text/css" media="screen" /> -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
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
            $applic_id = NULL;

            if ((isset($_POST['txt_aplic'])) AND ( $_POST['txt_aplic'] != '')) {
                $aplic = $_POST['txt_aplic'];
                //$aplic_desc = $_POST['txt_aplic_desc'];

                $Sql = "SELECT * FROM `tbapplication` WHERE `tbApplicationName` = '" . $aplic . "'";
                $rs = mysql_query($Sql, $conexao) or die("Erro busca aplicação");

                $linha = mysql_fetch_array($rs);
                $applic_id = $linha["idtbApplication"];


                if ($linha) {
                    //Trocar confirm() por equivalente em 'SweetAlert'
                    echo "<script language='javascript' type='text/javascript'> if(!confirm('Já existe uma aplicação com esse nome. Deseja avaliar a aplicação encontrada?')) {window.location.href='index2.php'; } </script>";

                    $linha = mysql_fetch_array($rs);
                    $applic_id = $linha["idtbApplication"];
                    $_SESSION['appic_id'] = $applic_id;
                } else {
                    $Sql2 = "INSERT INTO `tbapplication` (`idtbApplication`, `tbApplicationName`, `tbApplicationDescription`) VALUES (NULL, '" . $aplic . "', '" . $aplic_desc . "')";
                    $rs2 = mysql_query($Sql2, $conexao) or die("Erro insere aplicação");

                    //obter o id da aplicação inserida. Mudar essa sessão por uma função do mysql para obter autoincrement
                    if ($rs2) {
                        $Sql = "SELECT * FROM `tbapplication` WHERE `tbApplicationName` = '" . $aplic . "'";
                        $rs = mysql_query($Sql, $conexao) or die("Erro busca id aplicação");
                        $linha2 = mysql_fetch_array($rs);
                        $applic_id = $linha2["idtbApplication"];
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
                $Sql2 = "SELECT * FROM `tbform` WHERE `tbApplication_idtbApplication` = '" . $applic_id . "' AND `tbUser_idtbUser` = '" . $user . "'";
                $rs2 = mysql_query($Sql2, $conexao);
                $linha = mysql_fetch_array($rs2);
                $form = $linha["idtbForm"];
                print_r($form);
                
                if ($form == '') {
	                $Sql = "INSERT INTO `tbform` (`idtbForm`, `tbApplication_idtbApplication`, `tbUser_idtbUser`) VALUES (NULL, '" . $applic_id . "', '" . $user . "')";
	                $rs = mysql_query($Sql, $conexao);
	                
	                
	
	                //obter o id do form inserido
	                if ($rs) {
	                    $Sql2 = "SELECT * FROM `tbform` WHERE `tbApplication_idtbApplication` = '" . $applic_id . "' AND `tbUser_idtbUser` = '" . $user . "'";
	                    $rs2 = mysql_query($Sql2, $conexao) or die("Erro busca id formulário");
	                    $linha = mysql_fetch_array($rs2);
	                    $form_id = $linha["idtbForm"];
	                    $_SESSION['form_id'] = $form_id;
	                    header('Location:form.php');
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
            $Sql = mysql_query("SELECT * FROM tbform INNER JOIN tbapplication ON tbapplication.idtbApplication = tbform.tbApplication_idtbApplication WHERE tbform.tbformCompleted = 1 AND tbform.tbUser_idtbUser = " . $_SESSION['user_id']);
            while ($rr = mysql_fetch_array($Sql)) {
                echo "<span class='glyphicon glyphicon-check'></span><a href='results.php?form=" . $rr['idtbForm'] . "'>" . $rr['tbApplicationName'] . "</a><br>";
            }
            ?>
            <h3>Avaliações pendentes</h3>
            <?php
            $Sql = mysql_query("SELECT * FROM tbform INNER JOIN tbapplication ON tbapplication.idtbApplication = tbform.tbApplication_idtbApplication WHERE tbform.tbformCompleted = 0 AND tbform.tbUser_idtbUser = " . $_SESSION['user_id']);
            while ($rr = mysql_fetch_array($Sql)) {
                echo "<span class='glyphicon glyphicon-expand'></span><a href='results.php?form=" . $rr['idtbForm'] . "'>" . $rr['tbApplicationName'] . "</a><br>";
            }
            ?>
			<?php
				unset($_SESSION['applic_id']);
				unset($_SESSION['form_id']);
			
			?>

            <div id="footer" class="well well-sm">
                Desenvolvimento: Ademir Marques Junior - 2016 </div>
        </div>

    </body>

</html>
