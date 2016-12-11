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
    <?php
    	include "conecta.php";
	?>
        <div class="container-fluid">
            <div class="jumbotron">
                <h2>Modelo de Avaliação de Qualidade dos Sistemas Educacionais baseados 
                    em Web Semântica (SEWebS) </h2>
            </div>
            <div id="login" class="well well-sm">
                <?php
                include("valida.php");
                ?></div>
	            <?php
	            include 'navbar.php';
	            ?>

                
                
            <form action="form.php" class="form-group" method="get">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h1>Questionário</h1>
                        Você está avaliando <strong>'<?php $Sql = mysql_query("SELECT * FROM `tbapplication` WHERE `idtbapplication` = ".$_SESSION['appic_id']); while ($rr = mysql_fetch_array($Sql)) { echo $rr['tbApplicationName']; } ?></strong>'
                         como <strong>'<?php $Sql = mysql_query("SELECT * FROM `tbUserType` WHERE `idtbUserType` = ".$_SESSION['user_type']); while ($rr = mysql_fetch_array($Sql)) { echo $rr['tbUserTypeDescripton']; } ?>'  
						</strong>  
					</div>
               	 </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                            
                        <?php
                        $inserted = 0;
                        /* verificar se o formulário já foi preenchido antes */
                        foreach ($_GET as $key => $value) {
                            echo "<p>" . $key . "</p>"; //id da questão na tabela de questões
                            echo "<p>" . $value . "</p>"; //resposta
                            echo "<p>" . $_SESSION['form_id'] . "</p>";

                            $Sql = "INSERT INTO `tbform_has_tbuserquestion` (`tbForm_idtbForm`, `tbUserQuestion_idtbUserQuestion`, `tbForm_has_tbUserQuestionAnswer`) VALUES ('" . $_SESSION['form_id'] . "', '" . $key . "', '" . $value . "')";
                            $rs = mysql_query($Sql, $conexao) or die("Erro insere formulário");
                            $inserted = 1;
                        }

                        if ($inserted == 1) {
                        	$Sql = "UPDATE `tbform` SET `tbformCompleted` = '1' WHERE `tbform`.`idtbForm` = ".$_SESSION['form_id']." AND `tbform`.`tbApplication_idtbApplication` = ".$_SESSION['appic_id']." AND `tbform`.`tbUser_idtbUser` = ".$_SESSION['user_id']."";
                            $rs = mysql_query($Sql, $conexao) or die("Erro completa formulário");
                            header('Location:results.php?form=' . $_SESSION['form_id']);
                        }

                        $order = "ORDER BY tbArtifact_idtbArtifact";
                        $artifact_change = '';

//$Sql = "SELECT * FROM `tbuserquestion` WHERE tbusertype_idtbUsertype = '".$_SESSION['user_type']."' ".$order;
                        $Sql = "SELECT * FROM tbuserquestion INNER JOIN tbobjectives_has_tbuserquestion ON tbuserquestion.idtbUserQuestion = tbobjectives_has_tbuserquestion.tbUserQuestion_idtbUserQuestion WHERE tbobjectives_has_tbuserquestion.tbUserType_idtbUserType =  ".$_SESSION['user_type']." ".$order;
                        //echo $Sql;
                        $rs = mysql_query($Sql, $conexao) or die("Erro na pesquisa");

                        while ($linha = mysql_fetch_array($rs)) {
                            $id = $linha["idtbUserQuestion"];
                            $artifact = $linha["tbArtifact_idtbArtifact"];
                            $criterion = $linha["tbCriterion_idtbCriterion"];
                            $question = $linha["tbUserQuestionText"];
                            $howto = $linha["tbUserQuestionHowTo"];

                            if ($artifact_change != $artifact) {
                                $Sql2 = "SELECT * FROM `tbArtifact` WHERE `idtbArtifact` = '" . $artifact . "'";
                                $rs2 = mysql_query($Sql2, $conexao) or die("Erro na pesquisa");
                                while ($linha2 = mysql_fetch_array($rs2)) {
                                    echo '<div class="panel panel-default"><div class="panel-body"><h2>';
                                    echo $linha2['tbArtifactDescription'];
                                    echo '</h2></div></div>';
                                    $artifact_change = $artifact;
                                }
                            }


                            echo "<h4 id='question'>" . $question . "</h4>";
                            echo "<h5 id='howto'>" . $howto . "</h5>";
                            echo "<div class='radio'><label><input type='radio' name=" . $id . " value='0' class='optradio' required/>0</label></div>";
                            echo "<div class='radio'><label><input type='radio' name=" . $id . " value='1' class='optradio' />1</label></div>";
                            echo "<div class='radio'><label><input type='radio' name=" . $id . " value='2' class='optradio'  />2</label></div>";
                            echo "<div class='radio'><label><input type='radio' name=" . $id . " value='3' class='optradio' />3</label></div>";
                            echo "<div class='radio'><label><input type='radio' name=" . $id . " value='4' class='optradio' />4</label></div>";
                            echo "<div class='radio'><label><input type='radio' name=" . $id . " value='5' class='optradio' />5</label></div>";
                            echo "<hr>";
                        }
                        ?><input class="btn btn-default" type="submit" value="Salvar" /> </div>
                </div>
            </form>
        </div>
    </div>
    <div id="footer" class="well well-sm">
        Desenvolvimento: Ademir Marques Junior - 2016 </div>
</div>
<script>
         window.onload = function(){
    		swal("Neste aviso estará um pequeno texto explicando sobre o que será avaliado em geral, destacando que os objetos avaliados dependem do tipo de avaliador que está usando o sistema de avaliação.");
          }           
         </script>
</body>

</html>
