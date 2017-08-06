<?php session_start();
include "valida.php"; 
include "language.php";
include "conecta.php";
include "function.inc.php";
?>
<!DOCTYPE html>
<html>

   <head>
      <meta content="text/html; charset=utf-8" http-equiv="content-type" />
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="css/style.css" rel="stylesheet">
      <script src="js/Chart.min.js"></script>
      <script src="js/sweetalert.js"></script>
      <link rel="stylesheet" href="css/sweetalert.css">
      <link rel="icon" type="image/png" href="favicon.png">
      <title>Avalia SEWebS</title>
   </head>

   <body>
      <div class="container-fluid">
            <?php
            include 'header.php';
            include 'navbar.php';
            
            if (isset($_GET['form']) AND is_numeric($_GET['form']) AND !isset($_GET['applic']))  {
                  //Verificar se o formulário existe
                  $Sql = "SELECT * FROM tbform INNER JOIN tbapplication ON tbapplication.idtbApplication = tbform.tbApplication_idtbApplication INNER JOIN tbusertypetext ON tbform.tbUserType_idtbUserType = tbusertypetext.tbUserType_idtbUserType INNER JOIN tbuser ON tbform.tbUser_idtbUser = tbuser.idtbUser WHERE tbform.idtbForm = " . $_GET['form']."  AND tblanguage_idtblanguage =  ".$_SESSION['language'];
                  $rs = mysqli_query($conexao, $Sql) or die ('Erro amarelo');
                  $i = 0;
                  $completed = 0;
                  $applic_id = 0;
                  while ($linha = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
                  	$user_name = $linha['tbUserName'];
                  	$applic_name = $linha['tbApplicationName'];
                  	$type_name = $linha['tbUserTypeDesc'];

                     $completed = $linha['tbFormCompleted'];
                     $applic_id = $linha['idtbApplication'];
                     $i++;
                  } 
                  
                  //Verificar se o formulário foi finalizado
                  if ((!($completed)) AND ($i)) { //Se o formulário não foi finalizado
                     $_SESSION['form_id'] = $_GET['form'];
                     $_SESSION['appic_id'] = $applic_id;
                     echo "<script> window.location.assign('form.php')</script>";
                     //header('Location:form.php'); 
                  }
                  if (!($i)) {
                  	echo "<script> window.location.assign('index.php')</script>";
                  }
                  
             } elseif (isset($_GET['applic']) AND is_numeric($_GET['applic']) AND !isset($_GET['form']))  {
             	
             	$Sql = "SELECT * FROM tbapplication INNER JOIN tbform ON tbapplication.idtbApplication = tbform.tbApplication_idtbApplication WHERE tbform.tbFormCompleted = 1 AND tbapplication.idtbApplication = ".$_GET['applic'];
             	$rs = mysqli_query($conexao, $Sql);
             	
             	if(mysqli_num_rows($rs)) {
             		while ($linha = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
             			$applic_name = $linha['tbApplicationName'];
             			$applic_id = $linha['idtbApplication'];
             		}
             	} else {
             		echo "<script> window.location.assign('index.php')</script>";
             	}
					             	
            	//exit();
             } else {
             	echo "<script> window.location.assign('index3.php')</script>";
             }     
              
            ?>
                  
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h1>Resultado de avaliação:</h1>
 						<?php
 							if (isset($_GET['form'])) {
 								echo '<div class="panel panel-default">
               				<div class="panel-body">';
								echo "<h3><img src='img/result.png' height='113' alt=''><strong>".$user_name."</strong> avaliou <strong>".$applic_name."</strong> como <strong>".$type_name."</strong></h3>";
								echo "</div></div>";
						}      
							
						if (isset($_GET['applic'])) {
							echo '<div class="panel panel-default">
               				<div class="panel-body">';
							echo "<h1><img src='img/result.png' height='113' alt=''><strong>".$applic_name."</strong></h1>";
							echo "</div></div>";
							echo '<p><strong>Sistema avaliado por:</strong><br>';
							
							$Sql = "SELECT * FROM `tbform` INNER JOIN tbuser ON tbform.tbUser_idtbUser = tbuser.idtbUser INNER JOIN tbusertypetext ON tbform.tbUserType_idtbUserType = tbusertypetext.tbUserType_idtbUserType WHERE tbform.tbFormCompleted = 1 AND tbusertypetext.tbLanguage_idtbLanguage = 1 AND tbform.tbApplication_idtbApplication =" . $applic_id;
            			$rs = mysqli_query($conexao, $Sql);
            			while ($row = mysqli_fetch_assoc($rs)) {
            				echo '<span class="glyphicon glyphicon-user"></span> <strong>'.$row['tbUserName'].'</strong> como <strong>'.$row['tbUserTypeDesc'].'</strong><br>';
            			}
            			echo '</p>';
            			          			
            			
						}
						
						/*
						$Sql = "SELECT * FROM `tblearningobjects` WHERE tbApplication_idtbApplication = " . $applic_id;
				         $rs = mysqli_query($conexao, $Sql);
				         if (mysqli_num_rows($rs) == 0) {
				             echo "<p>Não há Objetos de aprendizagem avaliados</p>";
				         } else {
				         	echo '<p><strong>Objetos de Aprendizagem avaliados:</strong><br>';
				             while ($row = mysqli_fetch_assoc($rs)) {
				                 echo '<span class="glyphicon glyphicon-unchecked"></span> '.$row['tbLearningObjectsName'].'<br>';
				             }
				             echo '</p>';
				         }  
				         
				         $Sql = "SELECT * FROM `tbontologies` WHERE tbApplication_idtbApplication = " . $applic_id;
				         $rs = mysqli_query($conexao, $Sql);
				         if (mysqli_num_rows($rs) == 0) {
				             echo "<p>Não há Objetos de aprendizagem avaliados</p>";
				         } else {
				         	echo '<p><strong>Ontologias avaliadas:</strong><br>';
				             while ($row = mysqli_fetch_assoc($rs)) {
				                 echo '<span class="glyphicon glyphicon-unchecked"></span> '.$row['tbOntologiesName'].'<br>';
				             }
				             echo '</p>';
				         }     */                        
 						?>
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
               
               if (isset($_GET['applic'])) {
               	$resultados[$linha["idtbObjectives"]] = extraiResultados($_GET['applic'], $linha["idtbObjectives"], 1);
               } 
               
               if (isset($_GET['form'])) {
               	$resultados[$linha["idtbObjectives"]] = extraiResultados($_GET['form'], $linha["idtbObjectives"], 2);
               } 
               	
               $value = $resultados[$linha["idtbObjectives"]][2] / $resultados[$linha["idtbObjectives"]][3] * 20;
               $values[$linha["idtbObjectives"] - 1] = $value;
               $counter[$linha["idtbObjectives"]] = $resultados[$linha["idtbObjectives"]][4];
               $resultados_exp[$linha["idtbObjectives"]] = detalhaResultados($resultados[$linha["idtbObjectives"]]);
            }
            ?>
            <div class="panel panel-default">
               <div class="panel-body">

                  <div class="row">
                        <div class="col-md-4">
                           <h2>Visão Geral 
                           <strong><?php printf("%0.2f %% :", array_sum($values) / count(array_filter($values))); ?></strong></h2>
                           <canvas id="Geral" style="width:100%;"></canvas>
                           <?php
                           $loadgraph = '';

                           echo '
                  <script type="text/javascript">
               var options = {
               	responsive:true,
               	scaleOverride:true,
               	scaleSteps:10,
               	scaleStartValue:0,
               	scaleStepWidth:10
               };

               var data = {
                  labels: [';
                           foreach ($names as $name) {
                              echo '"' . $name . '",';
                           }
                           echo '],
                  datasets: [
                        {
                           label: "Dados primários",
                           fillColor: "rgba(102, 153, 255,0.5)",
                           strokeColor: "rgba(220,220,220,0.8)",
                           highlightFill: "rgba(220,220,220,0.75)",
                           highlightStroke: "rgba(220,220,220,1)",
                           data: [';
                           foreach ($values as $value) {
                              //echo json_encode($value) . ',';
                              printf("%0.2f,",$value);
                           }
                           echo ']
                        }
                  ]
               }</script>;';
									
                           $loadgraph = $loadgraph . 'var ctx = document.getElementById("Geral").getContext("2d");
                  var geralChart = new Chart(ctx).Radar(data, options);';
                           ?>

                        </div>
               </div>
            </div>
         </div>
                     <div class="row">
                           <?php
                        for ($i = 1; $i < 2; $i++) {
                           if ($values[$i-1] != 0) {
                                    $artifact_value = $resultados_exp[$i][0];
                                    $artifact_name = $resultados_exp[$i][1];
                                    
                                    $Factor_name = $resultados_exp[$i][3];
                                    
                                    print_r($Factor_name);
                                    
												foreach ($Factor_name[3] as $name) {
                                       echo '"' . $name . '",';
                                    }         
                                    
                                    foreach ($artifact_value as $value) {
                                       //echo json_encode($value) . ',';
                                       printf("%0.2f,",$value);
                                    }
                                    
                                                               
                                    
                                    echo ' <div class="col-md-6"><div class="panel panel-default"><div class="panel-body">
';
                                    echo '<h2>' . $names[$i-1] ." ";
                                    printf("%0.2f%% ", $values[$i-1]);
                                    echo '</h2><p>';
                                    //echo 'Questões nessa categoria <strong>'.$counter[$i];
                                    echo '</strong><p>';
                                    echo $texts[$i-1].'</p>';
                                    
                                    echo '<canvas id="GraficoArtefato' . $i . '" style="width:100%;"></canvas>';
                                    echo '<script type="text/javascript">
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
						                           fillColor: "rgba(51, 51, 153, 0.6)",
						                           strokeColor: "rgba(220,220,220,0.8)",
						                           highlightFill: "rgba(140, 140, 217,0.75)",
						                           highlightStroke: "rgba(220,220,220,1)",
						                           data: [';
                                    foreach ($artifact_value as $value) {
                                       //echo json_encode($value) . ',';
                                       printf("%0.2f,",$value);
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
