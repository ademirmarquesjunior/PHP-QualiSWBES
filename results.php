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
        <link rel="stylesheet" href="css/sweetalert.css">
        <script src="js/jquery-3.1.1.min.js"></script>        
        <script src="js/bootstrap.min.js"></script>
        <script src="js/Chart.bundle.min.js"></script>
			<script src="js/sweetalert.js"></script>
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
            $graph_id = 0;
            $loadgraph = '';

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
            

                        
                     
		   for ($i = 1; $i < 2; $i++) {
		      if ($values[$i-1] != 0) {
		               $artifact_value = $resultados_exp[$i][0];
		               $artifact_name = $resultados_exp[$i][1];
		               
		               $Factor_name = $resultados_exp[$i][3];
		               $Factor_value = $resultados_exp[$i][2];
		               
		               $subFactor_name = $resultados_exp[$i][5];
		               $subFactor_value = $resultados_exp[$i][4];
		               
		               //Gerar dados para o gráfico de fatores
		               $sum_factor = array();
		               $count_factor = array();
		               $names_factor = array();
		               for ($k = 0; $k < 6; $k++) {
		               	if (array_key_exists($k,  $Factor_value)) {
		               		foreach ($Factor_name[$k] as $key => $value){
		               			if ( ! isset($sum_factor[$key])) $sum_factor[$key] = null;
										if ( ! isset($count_factor[$key])) $count_factor[$key] = null;
										
		                  		$sum_factor[$key] =  $sum_factor[$key] + $Factor_value[$k][$key];
		                  		$count_factor[$key]++;
		               		}
		               		$names_factor = $names_factor + $Factor_name[$k];
		               	}
		               }
		               
		               foreach ($sum_factor as $key => $value){
								$sum_factor[$key] = $sum_factor[$key]/$count_factor[$key];                                    
		               } 
		               
		               $mean_factor = array_sum($sum_factor)/count($sum_factor);
		               
            echo '<div class="panel panel-default">
               <div class="panel-body">
                  <div class="row">
                        <div class="col-md-6">
                           <h2>Visão Geral 
                           <strong>';
                           printf("%0.2f %% :", $mean_factor);
                           echo '</strong></h2>';
                           
                           echo "<h2>";
                           if ($mean_factor < 50){
                           	echo "O sistema é inadequado!";
                           } elseif ($mean_factor < 75) {
                           	echo "O sistema é adequado com restrições!";
                           } else { 
                           	echo "O sistema é adequado!";
                        	}		               
		               		echo "</h2>";
		               		echo '<progress value="'.$mean_factor.'" max="100"></progress>';
		               		               
		               
		               $graph_id = $graph_id + 1;				
							graficoRadar($names_factor, $sum_factor, $graph_id);
							$loadgraph = $loadgraph . 'var ctx' . $graph_id . ' = document.getElementById("Geral' . $graph_id . '").getContext("2d");
		var GeralChart' . $graph_id . ' = new Chart(ctx' . $graph_id . ').Radar(data' . $graph_id . ', options' . $graph_id . ');';
		                  
		               echo '</div>
				               
				            </div>
				         
                     <div class="row">';                               
		               echo '<div class="col-md-8">';
		               echo '<h2>Artefatos avaliados</h2><p>';
		               echo 'Questões respondidas <strong>'.$counter[1];
		               echo '</strong><p>';
		               //echo $texts[$i-1].'</p>';
		               
		               $graph_id = $graph_id + 1;
		               graficoBarra2($artifact_name,$artifact_value,$graph_id);
		            	
		            	 echo '</div>';
		            	 echo  '';                             	
		            	
		            	foreach (array_keys($artifact_name) as $key) {
		            			echo '<div class="row"><div class="col-md-6">';											      
							      echo '<h3>' . $artifact_name[$key] . '</h3>';
							      
							      
							      $graph_id = $graph_id + 1;				
									graficoBarra2($Factor_name[$key], $Factor_value[$key], $graph_id);
									echo '</div>'; 
									
									
									foreach (array_keys($Factor_name[$key]) as $key2) {
										echo '<div class="col-md-4">';
										echo '<h4>'.$Factor_name[$key][$key2].'</h4>';
										
										$graph_id = $graph_id + 1;				
										graficoPolar($subFactor_name[$key][$key2], $subFactor_value[$key][$key2], $graph_id);										
										echo '</div>';
									}
									
									echo '</div>';
								
						   }
		      	echo '</div></div></div></div>';
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
