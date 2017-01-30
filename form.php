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
        <link rel="stylesheet" href="css/sweetalert.css">
        <link rel="stylesheet" type="text/css" href="slider/rzslider.css"/>
			<script src="js/jquery-3.1.1.min.js"></script>        
        <script src="js/bootstrap.min.js"></script>
			<script src="js/sweetalert.js"></script>
			<script src="slider/angular.min.js"></script>
			<script src="slider/rzslider.min.js"></script>        
        <link rel="icon" type="image/png" href="favicon.png">
        <title>Avalia SEWebS</title>
    </head>

    <body ng-app>

        <div class="container-fluid">
            <?php
            include 'header.php';
            include 'navbar.php';
            ?>

            <?php
            $Sql = "SELECT * FROM `tbform` INNER JOIN tbusertypetext ON tbform.tbUserType_idtbUserType = tbusertypetext.tbUserType_idtbUserType INNER JOIN `tbapplication` ON tbapplication.idtbapplication = tbform.tbapplication_idtbapplication WHERE tbform.idtbForm = " . $_SESSION['form_id'] . " AND tbLanguage_idtbLanguage = " . $_SESSION['language'];
            $rs = mysqli_query($conexao, $Sql) or die("Formulário não encontrado");
            while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
                $applic_name = $row['tbApplicationName'];
                $type_name = $row['tbUserTypeDesc'];
                $status = $row['tbFormStatus'];
                $user_type = $row['tbUserType_idtbUserType'];
            }

            $inserted = 0;

            //Insere as questões respondidas
            foreach ($_POST as $key => $value) {
                if (is_numeric($key) AND is_numeric($value)) {
                    $Sql = "INSERT INTO `tbform_has_tbuserquestion` (`tbForm_idtbForm`, `tbUserQuestion_idtbUserQuestion`, `tbForm_has_tbUserQuestionAnswer`) VALUES ('" . $_SESSION['form_id'] . "', '" . $key . "', '" . $value . "')";
                    $rs = mysqli_query($conexao, $Sql) or die("Erro insere formulário");
                    $inserted = 1;
                }
            }

				//Se a variável new foi recebida o status é incrementado indicando que o formulário deve prosseguir para outro artefato
            if ((isset($_GET['new']))) {
                $status++;
                $Sql2 = "UPDATE `tbform` SET `tbFormStatus` = '" . $status . "' WHERE `tbform`.`idtbForm` = " . $_SESSION['form_id'] . " AND `tbform`.`tbApplication_idtbApplication` = " . $_SESSION['appic_id'] . " AND `tbform`.`tbUser_idtbUser` = " . $_SESSION['user_id'];
                $rs2 = mysqli_query($conexao, $Sql2) or die("Erro na atualização de estado");
             }


            //Se questões foram inseridas na tabela de respostas incrementar o status
            if ($inserted == 1) {

                //Se o Artefato for Ontologia ou Objeto de Aprendizagem perguntar ao usuário se ele quer continuar avaliando o mesmo tipo novamente									
                if ($status < 3) {
                    $Sql = "SELECT * FROM tbartifacttext WHERE tblanguage_idtblanguage = " . $_SESSION['language'] . " AND tbartifact_idtbartifact = " . $status;
                    $rs = mysqli_query($conexao, $Sql);
                    $row = mysqli_fetch_array($rs, MYSQLI_ASSOC);
                    echo '<script> swal({
  title: "' . $row['tbArtifactName'] . '",
  text: "Deseja avaliar outro ' . $row['tbArtifactName'] . '?",
  type: "warning",
  showCancelButton: true,
  timer: 100000,
  confirmButtonClass: "btn-danger",
  confirmButtonText: "Sim, continuar",
  cancelButtonText: "Não, seguir para outro artefato",
  closeOnConfirm: false,
  closeOnCancel: false
},
function(isConfirm) {
  if (isConfirm) {
    setTimeout(window.location.assign("form.php"),100000);
  } 
  if (!(isConfirm)) {
    setTimeout(window.location.assign("form.php?new=1"),100000);
  } 
});
</script>';
                    exit();
                }



                $status++;
                $Sql2 = "UPDATE `tbform` SET `tbFormStatus` = '" . $status . "' WHERE `tbform`.`idtbForm` = " . $_SESSION['form_id'] . " AND `tbform`.`tbApplication_idtbApplication` = " . $_SESSION['appic_id'] . " AND `tbform`.`tbUser_idtbUser` = " . $_SESSION['user_id'];
                echo $Sql;
                $rs2 = mysqli_query($conexao, $Sql2) or die("Erro na atualização de estado");
                if ($rs2) {
                    echo "<script> window.location.assign('form.php'); </script>";
                }
            }

            //Se o status for maior que 4 marcar o formulário como terminado
            if ($status > 4) {
                $Sql = "UPDATE `tbform` SET `tbformCompleted` = '1' WHERE `tbform`.`idtbForm` = " . $_SESSION['form_id'] . " AND `tbform`.`tbApplication_idtbApplication` = " . $_SESSION['appic_id'] . " AND `tbform`.`tbUser_idtbUser` = " . $_SESSION['user_id'] . "";
                $rs = mysqli_query($conexao, $Sql) or die("Erro completa formulário");
                echo "<script> window.location.assign('results.php?form=" . $_SESSION['form_id'] . "')</script>";
            }
            ?>


            <form action="form.php" class="form-group" method="post">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h1>Questionário</h1>
                        <img src="img/form.png" height="113" alt="">
                        <?php
                        echo "<h3>Você está avaliando <strong>" . $applic_name . "</strong> como <strong>" . $type_name . "</strong><h3>";
                        ?> 
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">

                        <?php
                        $order = "ORDER BY tbArtifact_idtbArtifact";
                        $artifact_change = '';
                        $factor_change = '';
                        $id_change = '';

                        //Listar as questões do formulário
                        $Sql = "SELECT * FROM tbuserquestion INNER JOIN tbquestiontext ON tbuserquestion.idtbUserQuestion = tbquestiontext.tbUserQuestion_idtbUserQuestion INNER JOIN tbobjectives_has_tbuserquestion ON tbuserquestion.idtbUserQuestion = tbobjectives_has_tbuserquestion.tbUserQuestion_idtbUserQuestion WHERE tbartifact_idtbArtifact = " . $status . " AND tbquestiontext.tbLanguage_idtbLanguage = " . $_SESSION['language'] . " AND tbobjectives_has_tbuserquestion.tbUserType_idtbUserType = " . $user_type . " " . $order;
                        $rs = mysqli_query($conexao, $Sql) or die("Erro na pesquisa");

                        if (mysqli_num_rows($rs) == 0) {
                            $status++;
                            $Sql2 = "UPDATE `tbform` SET `tbFormStatus` = '" . $status . "' WHERE `tbform`.`idtbForm` = " . $_SESSION['form_id'] . " AND `tbform`.`tbApplication_idtbApplication` = " . $_SESSION['appic_id'] . " AND `tbform`.`tbUser_idtbUser` = " . $_SESSION['user_id'];
                            $rs2 = mysqli_query($conexao, $Sql2) or die("Erro na atualização de estado");
                            echo "<script> location.reload(); </script>";
                        }

                        while ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
                            $id = $row["idtbUserQuestion"];
                            $artifact = $row["tbArtifact_idtbArtifact"];
                            $Factor = $row["tbFactor_idtbFactor"];
                            $question = $row["tbQuestionText"];
                            $howto = $row["tbQuestionTextHowTo"];
                            $i = 0;
                            if ($id != $id_change) {

                                if ($artifact_change != $artifact) {
                                    $Sql2 = "SELECT * FROM `tbartifact` INNER JOIN tbartifacttext ON tbartifact.idtbArtifact = tbartifacttext.tbArtifact_idtbArtifact WHERE tbartifact.idtbArtifact = '" . $artifact . "' AND tbLanguage_idtbLanguage = " . $_SESSION['language'];
                                    $rs2 = mysqli_query($conexao, $Sql2) or die("Erro na pesquisa");
                                    while ($row2 = mysqli_fetch_array($rs2, MYSQLI_ASSOC)) {
                                        echo '<div class="panel panel-default"><div class="panel-body"><h2>';
                                        echo $row2['tbArtifactName'];
                                        echo '</h2></div></div><span class="glyphicon glyphicon-comment"></span>  Passe o ponteiro sobre a questão ou opções para ver instruções de como avaliar cada critério.';
                                        $artifact_change = $artifact;
                                    }
                                }

                                if ($factor_change != $Factor) {
                                    $Sql2 = "SELECT * FROM `tbfactor` INNER JOIN tbfactortext ON tbfactor.idtbFactor = tbfactortext.tbFactor_idtbFactor WHERE tbfactor.idtbFactor = '" . $Factor . "' AND tbLanguage_idtbLanguage = " . $_SESSION['language'];
                                    $rs2 = mysqli_query($conexao, $Sql2) or die("Erro na pesquisa");
                                    while ($row2 = mysqli_fetch_array($rs2, MYSQLI_ASSOC)) {
                                        echo "<hr><h6>" . $row2['tbFactorName'] . "</h6>";
                                        $factor_change = $Factor;
                                    }
                                }


											echo '<div class="panel panel-default">'; 
											
											echo '<div class="panel-heading" data-toggle="tooltip" data-placement="bottom" title="'.$howto.'">';
                                echo '<h3 id="question">   <span class="glyphicon glyphicon-hand-right"></span>  '. $question .'</h3>';
											echo '</div>';                               
                                echo '<div class="panel-body">';
                                echo '<div class="row">';
                                //echo '<h3 id="question" data-toggle="tooltip" title="'.$howto.'">   <span class="glyphicon glyphicon-hand-right"></span>  '. $question .'</h3>';
                                //echo "<h5 id='howto'>" . $howto . "</h5>";
                                $i++;
                                //echo '<rzslider rz-slider-model="slider_ticks.value" rz-slider-options="slider_ticks.options"></rzslider>';
                                echo '<div class="col-md-4" align="right">';
                                //echo '<input type="range" name="' . $id . '" min="0" max="5">';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                                echo '<div class="radio">';
                                 echo "<div class='radio-inline'><label><input type='radio' name=" . $id . " value='0' class='optradio' required/>0</label></div>";
                                  echo "<div class='radio-inline'><label><input type='radio' name=" . $id . " value='1' class='optradio' />1</label></div>";
                                  echo "<div class='radio-inline'><label><input type='radio' name=" . $id . " value='2' class='optradio'  />2</label></div>";
                                  echo "<div class='radio-inline'><label><input type='radio' name=" . $id . " value='3' class='optradio' />3</label></div>";
                                  echo "<div class='radio-inline'><label><input type='radio' name=" . $id . " value='4' class='optradio' />4</label></div>";
                                  echo "<div class='radio-inline'><label><input type='radio' name=" . $id . " value='5' class='optradio' />5</label></div>";
												echo '</div>';
												echo '</div>';                                  
                                  //echo "<hr>";
                                $id_change = $id;
                            }
                        }
                        ?><input class="btn btn-primary btn-block btn-lg" type="submit" value="Seguir" />
                    </div>
                </div>
            </form>
<div ng-app="rzSliderDemo">
    <div ng-controller="MainCtrl">
<article>
<rzslider rz-slider-model="slider_ticks_values.value" rz-slider-options="slider_ticks_values.options"></rzslider>
</article>
</div>
</div>
            <?php
            include 'footer.php';
            ?>
        </div>

			
        <script>
            window.onload = function () {
                swal("Artefato", "Neste aviso estará um pequeno texto explicando sobre o que será avaliado em geral, destacando que os objetos avaliados dependem do tipo de avaliador que está usando o sistema de avaliação.");
            }

            $(function(){
    				$('[data-toggle=tooltip]').tooltip();
 				});
        </script>
        <script>
        		var app = angular.module('rzSliderDemo', ['rzModule', 'ui.bootstrap']);

				app.controller('MainCtrl', function ($scope, $rootScope, $timeout, $modal) {
					$scope.slider_ticks_values = {
        value: 5,
        options: {
            ceil: 10,
            floor: 0,
            showTicksValues: true
        }
    };
					
					
				}
        
        
        
        </script>
        
    </body>

</html>
