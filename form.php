<?php session_start(); 
include "valida.php";
include "language.php";
include "conecta.php";
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

                
                
            <form action="form.php" class="form-group" method="get">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h1>Questionário</h1>
                        Você está avaliando <strong>'
                        <?php
                        $Sql = "SELECT * FROM `tbapplication` WHERE `idtbapplication` = ".$_SESSION['appic_id'];
                        $rs = mysqli_query($conexao,$Sql);
                        while ($linha = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
                        	echo $linha['tbApplicationName']; 
                        } ?> </strong>'como 
                        <strong>'
                        <?php 
                        $Sql = "SELECT * FROM `tbform` INNER JOIN tbusertypetext ON tbform.tbUserType_idtbUserType = tbusertypetext.tbUserType_idtbUserType WHERE tbform.idtbForm = ".$_SESSION['form_id']." AND tbLanguage_idtbLanguage = ".$_SESSION['language'];
                        $rs = mysqli_query($conexao,$Sql);
                        while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
                        	echo $row['tbUserTypeDesc']; 
                        	$user_type = $row['tbUserType_idtbUserType'];
                        } ?>'  
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
                            $rs = mysqli_query($conexao, $Sql) or die("Erro insere formulário");
                            $inserted = 1;
                        }

                        if ($inserted == 1) {
                        	$Sql = "UPDATE `tbform` SET `tbformCompleted` = '1' WHERE `tbform`.`idtbForm` = ".$_SESSION['form_id']." AND `tbform`.`tbApplication_idtbApplication` = ".$_SESSION['appic_id']." AND `tbform`.`tbUser_idtbUser` = ".$_SESSION['user_id']."";
                            $rs = mysqli_query($conexao, $Sql) or die("Erro completa formulário");
                            echo "<script> window.location.assign('results.php?form=".$_SESSION['form_id']."')</script>";
                            //header('Location:results.php?form=' . $_SESSION['form_id']);
                        }

                        $order = "ORDER BY tbArtifact_idtbArtifact";
                        $artifact_change = '';
                        $factor_change = '';
						$id_change = '';

						//$Sql = "SELECT * FROM `tbuserquestion` WHERE tbusertype_idtbUsertype = '".$_SESSION['user_type']."' ".$order;
                        $Sql = "SELECT * FROM tbuserquestion INNER JOIN tbquestiontext ON tbuserquestion.idtbUserQuestion = tbquestiontext.tbUserQuestion_idtbUserQuestion INNER JOIN tbobjectives_has_tbuserquestion ON tbuserquestion.idtbUserQuestion = tbobjectives_has_tbuserquestion.tbUserQuestion_idtbUserQuestion WHERE tbquestiontext.tbLanguage_idtbLanguage = ".$_SESSION['language']." AND tbobjectives_has_tbuserquestion.tbUserType_idtbUserType = ".$user_type." ".$order;
                        $rs = mysqli_query($conexao, $Sql) or die("Erro na pesquisa");

                        while ($linha = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
                            $id = $linha["idtbUserQuestion"];
                            $artifact = $linha["tbArtifact_idtbArtifact"];
                            $Factor = $linha["tbFactor_idtbFactor"];
                            $question = $linha["tbQuestionText"];
                            $howto = $linha["tbQuestionTextHowTo"];
										$i = 0;
							if ($id != $id_change) {

                            if ($artifact_change != $artifact) {
                                $Sql2 = "SELECT * FROM `tbartifact` INNER JOIN tbartifacttext ON tbartifact.idtbArtifact = tbartifacttext.tbArtifact_idtbArtifact WHERE tbartifact.idtbArtifact = '" . $artifact . "' AND tbLanguage_idtbLanguage = ".$_SESSION['language'];
                                $rs2 = mysqli_query($conexao,$Sql2) or die("Erro na pesquisa");
                                while ($linha2 = mysqli_fetch_array($rs2, MYSQLI_ASSOC)) {
                                    echo '<div class="panel panel-default"><div class="panel-body"><h2>';
                                    echo $linha2['tbArtifactName'];
                                    echo '</h2></div></div>';
                                    $artifact_change = $artifact;
                                }
                            }
                            
                            if ($factor_change != $Factor) {
                                $Sql2 = "SELECT * FROM `tbfactor` INNER JOIN tbfactortext ON tbfactor.idtbFactor = tbfactortext.tbFactor_idtbFactor WHERE tbfactor.idtbFactor = '" . $Factor . "' AND tbLanguage_idtbLanguage = ".$_SESSION['language'];
                                $rs2 = mysqli_query($conexao,$Sql2) or die("Erro na pesquisa");
                                while ($linha2 = mysqli_fetch_array($rs2, MYSQLI_ASSOC)) {
                                    echo "<hr><h6>".$linha2['tbFactorName']."</h6>";
                                    $factor_change = $Factor;
                                }
                            }
                            
                            
										echo '<div class="panel-body">';
										echo '<div class="row">';
                            echo "<h3 id='question'>" . $question . "</h3>";
                            echo "<h5 id='howto'>" . $howto . "</h5>";
                            $i++;
                            echo '<div class="col-md-4" align="right">';
                            echo '<input type="range" name="'. $id .'" min="0" max="5">';
									echo '</div>';
									echo '</div>';
									echo '</div>';
                            /*echo "<div class='radio'><label><input type='radio' name=" . $id . " value='0' class='optradio' required/>0</label></div>";
                            echo "<div class='radio'><label><input type='radio' name=" . $id . " value='1' class='optradio' />1</label></div>";
                            echo "<div class='radio'><label><input type='radio' name=" . $id . " value='2' class='optradio'  />2</label></div>";
                            echo "<div class='radio'><label><input type='radio' name=" . $id . " value='3' class='optradio' />3</label></div>";
                            echo "<div class='radio'><label><input type='radio' name=" . $id . " value='4' class='optradio' />4</label></div>";
                            echo "<div class='radio'><label><input type='radio' name=" . $id . " value='5' class='optradio' />5</label></div>";
                            echo "<hr>"; */
							$id_change = $id;
							}
                        }
                        ?><input class="btn btn-default" type="submit" value="Salvar" />
					</div>
                </div>
            </form>
            
       
            
            <?php
            include 'footer.php';
            ?>
		</div>
<script>
         window.onload = function(){
    		swal("Neste aviso estará um pequeno texto explicando sobre o que será avaliado em geral, destacando que os objetos avaliados dependem do tipo de avaliador que está usando o sistema de avaliação.");
         }
			
         </script>
</body>

</html>
