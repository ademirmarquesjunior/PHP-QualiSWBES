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
      <script src="Chart.min.js"></script>
      <script src="dist/sweetalert.js"></script>
      <link rel="stylesheet" href="dist/sweetalert.css">
      <link rel="icon" type="image/png" href="favicon.png">
      <title>Avalia SEWebS</title>
   </head>
   <?php
   if (!isset($_GET['form']))
      exit;
   include "conecta.php";
   include "function.inc.php";
   
   
   ?>
   <body>
      <div class="container-fluid">
            <?php
            include 'header.php';
            include 'navbar.php';
            ?>
            <div class="panel panel-default">
               <div class="panel-body">
                  <h1>Resultado de avaliação</h1>
                  <img src="img/form.jpg" height="113" alt="">
                  <?php 
                  $Sql = "SELECT * FROM tbform INNER JOIN tbapplication ON tbapplication.idtbApplication = tbform.tbApplication_idtbApplication INNER JOIN tbusertypetext ON tbform.tbUserType_idtbUserType = tbusertypetext.tbUserType_idtbUserType INNER JOIN tbuser ON tbform.tbUser_idtbUser = tbuser.idtbUser WHERE tbform.idtbForm = " . $_GET['form']." AND tbUser_idtbUser = ".$_SESSION['user_id']." AND tbFormCompleted = 1";
                  $rs = mysqli_query($conexao, $Sql);
                  $i = 0;
                  $completed = 0;
                  $applic_id = 0;
                  while ($linha = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
                  	echo "<h3><strong>".$linha['tbUserName']."</strong> avaliou <strong>".$linha['tbApplicationName']."</strong> como <strong>".$linha['tbUserTypeDesc']."</strong></h3>";
                     $completed = $linha['tbFormCompleted'];
                     $applic_id = $linha['idtbApplication'];
                     $i++;
                  } 
                  if ((!($completed)) AND ($i)) { //Se o formulário não foi finalizado
                     $_SESSION['form_id'] = $_GET['form'];
                     $_SESSION['appic_id'] = $applic_id;
                     //echo "<script> window.location.assign('form.php')</script>";
                     //header('Location:form.php'); 
                  }
                  //if (!($i)) echo "<script> window.location.assign('index2.php')</script>";
                                    
                  ?>
 
                  </strong>  
               </div>
            </div>


            <?php
            $resultados = array();
            $values = array();
            $names = array();
            $texts = array();
            $counter = array();
            $resultados_exp = array();

            $Sql = "SELECT * FROM `tbobjectives` INNER JOIN tbobjectivestext ON tbobjectives.idtbObjectives = tbobjectivestext.tbobjectives_idtbobjectives WHERE tblanguage_idtblanguage = ".$_SESSION['language'];
            //echo $Sql;
            $rs = mysqli_query($conexao, $Sql) or die("Erro na pesquisa");
            while ($linha = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
               $names[$linha["idtbObjectives"] - 1] = $linha["tbObjectivesDesc"];
               $texts[$linha["idtbObjectives"] - 1] = $linha["tbObjectivesText"];

               $resultados[$linha["idtbObjectives"]] = array();
               $resultados[$linha["idtbObjectives"]] = extraiResultados($_GET['form'], $linha["idtbObjectives"]);
               $value = $resultados[$linha["idtbObjectives"]][2] / $resultados[$linha["idtbObjectives"]][3] * 20;
               $values[$linha["idtbObjectives"] - 1] = $value;
               $counter[$linha["idtbObjectives"]] = $resultados[$linha["idtbObjectives"]][4];
               $resultados_exp[$linha["idtbObjectives"]] = detalhaResultados($resultados[$linha["idtbObjectives"]]);
            }
            ?>
            <div class="panel panel-default">
               <div class="panel-body">

                  <div class="row">
                        <div class="col-md-6">
                           <h2>Visão Geral </h2>
                           <p>A aplicação obteve a média geral de <h3><?php printf("%0.2f %% :", array_sum($values) / count(array_filter($values))); ?></h3> distribuídos entre os objetivos abaixo: </p>
                           <canvas id="GraficoBarra" style="width:100%;"></canvas>
                           <?php
                           $loadgraph = '';

                           echo '
                  <script type="text/javascript">
               var options = { responsive:true, scaleOverride:true, scaleSteps:10, scaleStartValue:0, scaleStepWidth:10};

               var data = {
                  labels: [';
                           foreach ($names as $name) {
                              echo '"' . $name . '",';
                           }
                           echo '],
                  datasets: [
                        {
                           label: "Dados primários",
                           fillColor: "rgba(220,220,220,0.5)",
                           strokeColor: "rgba(220,220,220,0.8)",
                           highlightFill: "rgba(220,220,220,0.75)",
                           highlightStroke: "rgba(220,220,220,1)",
                           data: [';
                           foreach ($values as $value) {
                              echo json_encode($value) . ',';
                           }
                           echo ']
                        }
                  ]
               }</script>;';

                           $loadgraph = $loadgraph . 'var ctx = document.getElementById("GraficoBarra").getContext("2d");
                  var BarChart = new Chart(ctx).Bar(data, options);';
                           ?>

                        </div>
               </div>
            </div>
         </div>
                     <div class="row">
                           <?php
                              for ($i = 1; $i < 5; $i++) {
                           if ($values[$i-1] != 0) {
                                    $artifact_value = $resultados_exp[$i][0];
                                    $artifact_name = $resultados_exp[$i][1];
                                    echo ' <div class="col-md-6"><div class="panel panel-default"><div class="panel-body">
';
                                    echo '<h2>' . $names[$i-1] ." ";
                                    printf("%0.2f%% ", $values[$i-1]);
                                    echo '</h2><p>';
                                    echo 'Questões nessa categoria <strong>'.$counter[$i];
                                    echo '</strong><p>';
                                    echo $texts[$i-1];
                                    echo '</p><p>Os seguintes Artefatos foram avaliados para alcançar este objetivos. Cada artefato ainda é detalhado em fatores e subfatores avaliados:</p>';
                                    echo '<canvas id="GraficoArtefato' . $i . '" style="width:100%;"></canvas>';
                                    echo '
                  <script type="text/javascript">
               var options' . $i . ' = { responsive:true, scaleOverride:true, scaleSteps:10, scaleStartValue:0, scaleStepWidth:10, display:true };

               var data' . $i . ' = {
                  labels: [';
                                    foreach ($artifact_name as $name) {
                                       echo '"' . $name . '",';
                                    }
                                    echo '],
                  datasets: [
                        {
                           label: "Dados primários",
                           fillColor: "rgba(220,220,220,0.5)",
                           strokeColor: "rgba(220,220,220,0.8)",
                           highlightFill: "rgba(220,220,220,0.75)",
                           highlightStroke: "rgba(220,220,220,1)",
                           data: [';
                                    foreach ($artifact_value as $value) {
                                       echo json_encode($value) . ',';
                                    }
                                    echo ']
                        }
                  ]
               };</script>';
                                    $loadgraph = $loadgraph . 'var ctx' . $i . ' = document.getElementById("GraficoArtefato' . $i . '").getContext("2d");
               var BarChart' . $i . ' = new Chart(ctx' . $i . ').Bar(data' . $i . ', options' . $i . ');';
               
                           echo '</div></div></div>';
                           }
                        }
                              ?>
                        </div>
            <?php
            include 'footer.php';
            ?>
                  </div>
                  <?php
                     echo '<script>
                     window.onload = function(){
                     ' . $loadgraph . '     
                     }
                     </script>';
                  ?>        
                  </body>

                  </html>
