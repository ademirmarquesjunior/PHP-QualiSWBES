<?php                       


function extraiResultados ($value, $set) {
	include "conecta.php";
	$total = 0;
	$total_weight = 0;
    $counter = 0;
    $sum = array(array(array()));
    $sym_weight = array(array(array()));

    if ($value != '') {
		if ($set == 1) { //lista por aplicação
			$Sql = "SELECT distinct idtbform_has_tbuserquestion, tbForm_has_tbUserQuestionAnswer, tbUserType_has_tbUserQuestionWeight, tbArtifact_idtbArtifact, tbFactor_idtbFactor, tbSubFactor_idtbSubFactor FROM `tbform_has_tbuserquestion` INNER JOIN tbform ON  `tbForm_idtbForm` = tbform.idtbform inner join tbquestion ON tbuserquestion_idtbuserquestion = tbquestion.idtbquestion inner join tbquestionid on tbquestion.tbquestionid_idtbquestionid = tbquestionid.idtbquestionid inner join tbusertype_has_tbuserquestion on tbquestionid.idtbQuestionId = tbusertype_has_tbuserquestion.tbQuestionId_idtbQuestionId WHERE tbform.tbapplication_idtbapplication = ".$value;
		} elseif($set == 2) { //lista por formulário
			$Sql = "SELECT distinct tbuserquestion_idtbuserquestion, tbForm_has_tbUserQuestionAnswer, tbUserType_has_tbUserQuestionWeight, tbArtifact_idtbArtifact, tbFactor_idtbFactor, tbSubFactor_idtbSubFactor FROM `tbform_has_tbuserquestion` inner join tbquestion ON tbuserquestion_idtbuserquestion = tbquestion.idtbquestion inner join tbquestionid on tbquestion.tbquestionid_idtbquestionid = tbquestionid.idtbquestionid inner join tbform on tbform_has_tbuserquestion.tbform_idtbform = tbform.idtbform inner join tbusertype_has_tbuserquestion on tbform.tbUserType_idtbUserType =  tbusertype_has_tbuserquestion.tbUserType_idtbUserType WHERE `tbForm_idtbForm` = ".$value;
		}	
        $rs = mysqli_query($conexao, $Sql) or die("Formulário não existe");
        
        

        while ($linha = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
            $total = $total + $linha["tbForm_has_tbUserQuestionAnswer"]*$linha["tbUserType_has_tbUserQuestionWeight"];
            $total_weight = $total_weight + $linha["tbUserType_has_tbUserQuestionWeight"];
            $counter++;
            
            if (!isset($sum[$linha["tbArtifact_idtbArtifact"]][$linha["tbFactor_idtbFactor"]][$linha["tbSubFactor_idtbSubFactor"]])){
            	$sum[$linha["tbArtifact_idtbArtifact"]][$linha["tbFactor_idtbFactor"]][$linha["tbSubFactor_idtbSubFactor"]] = NULL;
            	$sum_weight[$linha["tbArtifact_idtbArtifact"]][$linha["tbFactor_idtbFactor"]][$linha["tbSubFactor_idtbSubFactor"]] = NULL;
            }
            
            $sum[$linha["tbArtifact_idtbArtifact"]][$linha["tbFactor_idtbFactor"]][$linha["tbSubFactor_idtbSubFactor"]] = $sum[$linha["tbArtifact_idtbArtifact"]][$linha["tbFactor_idtbFactor"]][$linha["tbSubFactor_idtbSubFactor"]] + $linha["tbForm_has_tbUserQuestionAnswer"]*$linha["tbUserType_has_tbUserQuestionWeight"];
            $sum_weight[$linha["tbArtifact_idtbArtifact"]][$linha["tbFactor_idtbFactor"]][$linha["tbSubFactor_idtbSubFactor"]] = $sum_weight[$linha["tbArtifact_idtbArtifact"]][$linha["tbFactor_idtbFactor"]][$linha["tbSubFactor_idtbSubFactor"]] + $linha["tbUserType_has_tbUserQuestionWeight"];
        }
     }
        
     return array($sum, $sum_weight, $total, $total_weight, $counter);
}

function detalhaResultados ($resultados) {
	$sum = $resultados[0];
	$sum_weight = $resultados[1];
	
	$subFactor_value = array(array(array()));
	$subFactor_name = array(array(array()));	
	$Factor_value = array(array());
	$Factor_name = array(array());
	$artifac_value = array();
	$artifact_name = array();
	
    include "conecta.php";
   
    foreach ($sum as $i=>$valueartifact) {
    	echo "<p>";
    	$artifact_sum = 0;
    	$artifact_weight = 0;

    	foreach ($valueartifact as $j=>$valueFactor) {
    		$Factor_sum = 0;
    		$Factor_weight = 0;
    		
    		//echo "...".$j."<br>";
    		foreach ($valueFactor as $k=>$valuesubFactor) {
    			$Sql = "SELECT * FROM `tbsubfactor` INNER JOIN tbsubfactortext ON tbsubfactor.idtbsubfactor = tbsubfactortext.tbsubfactor_idtbSubfactor  WHERE idtbSubFactor = ".$k." AND tblanguage_idtblanguage = ".$_SESSION['language'];   			
    			$rs = mysqli_query($conexao, $Sql);
            	while ($linha = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
            		//echo $rr['tbSubFactorDesc']." "; 
            		$subFactor = $linha['tbSubFactorName'];
				}
    		
    			if ($sum_weight[$i][$j][$k] != 0) {
	    			$result = $valuesubFactor/$sum_weight[$i][$j][$k]*20;
	    			$Factor_sum = $Factor_sum + $valuesubFactor;
	    			$Factor_weight = $Factor_weight + $sum_weight[$i][$j][$k];
	    			$subFactor_value[$i][$j][$k] = $result;
	    			$subFactor_name[$i][$j][$k] = $subFactor;
    			}
    	   }	
		
		
        	$Sql = "SELECT * FROM `tbfactor` INNER JOIN tbfactortext ON tbfactor.idtbfactor = tbfactortext.tbfactor_idtbfactor WHERE idtbfactor = ".$j." AND tblanguage_idtblanguage = ".$_SESSION['language'];
        	$rs = mysqli_query($conexao, $Sql);
         while ($linha = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
            //echo "<strong>".$rr['tbFactorDesc']."</strong> "; 
            $Factor = $linha['tbFactorName'];
			}
			if ($Factor_weight != 0){
				$result = $Factor_sum/$Factor_weight*20;
	
			    $Factor_value[$i][$j] = $result;
				$Factor_name[$i][$j] = $Factor;
			}
						
	    	$artifact_sum = $artifact_sum + $Factor_sum;
	    	$artifact_weight = $artifact_weight + $Factor_weight;
			
		}
		
		
    	$Sql = "SELECT * FROM `tbartifact` INNER JOIN tbartifacttext ON tbartifact.idtbartifact = tbartifacttext.tbartifact_idtbartifact WHERE idtbartifact = ".$i." AND tblanguage_idtblanguage = ".$_SESSION['language'];
    	$rs = mysqli_query($conexao, $Sql);
       	while ($linha = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
        		//echo "<strong>".$rr['tbArtifactDescription']."</strong> ";
        		$artifact = $linha['tbArtifactName'];
        } 
		if ($artifact_weight != 0) {
			$result = $artifact_sum/$artifact_weight*20;
			$artifact_name[$i] = $artifact;
			$artifact_value[$i] = $result;
		}

	}
	
	return array($artifact_value,$artifact_name,$Factor_value,$Factor_name,$subFactor_value,$subFactor_name);

}

function graficoBarraHorizontal ($names, $values, $id) {
	echo "
<canvas id='myChart".$id."' ></canvas>
<script>
var ctx".$id." = document.getElementById('myChart".$id."');
var myChart".$id." = new Chart(ctx".$id.", {
    type: 'horizontalBar',
    data: {
        labels: [";
        foreach ($names as $name) {
      	echo "'" . $name . "',";
   		}
        echo "],
        datasets: [{
            label: '',
            data: [";
            foreach ($values as $value) {
			      //echo json_encode($value) . ',';
			      printf("%0.2f,",$value);
			   }
            echo "],
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                'rgba(75, 192, 192, 0.5)',
                'rgba(153, 102, 255, 0.5)',
                'rgba(255, 159, 64, 0.5)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: { 
    	legend: {
			display: false,
					labels: {
						display: false
					}    	
    	},
        scales: {
            xAxes: [{
                ticks: {
                    beginAtZero:true,
                    max: 100,
                    min: 0
                }
            }]
        }
    }
});
</script>
	";	
}


function graficoBarra2 ($names, $values, $id) {
	echo "
<canvas id='myChart".$id."' ></canvas>
<script>
var ctx".$id." = document.getElementById('myChart".$id."');
var myChart".$id." = new Chart(ctx".$id.", {
    type: 'bar',
    data: {
        labels: [";
        foreach ($names as $name) {
      	echo "'" . $name . "',";
   		}
        echo "],
        datasets: [{
            label: '',
            data: [";
            foreach ($values as $value) {
			      //echo json_encode($value) . ',';
			      printf("%0.2f,",$value);
			   }
            echo "],
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                'rgba(75, 192, 192, 0.5)',
                'rgba(153, 102, 255, 0.5)',
                'rgba(255, 159, 64, 0.5)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: { 
    	legend: {
			display: false,
					labels: {
						display: false
					}    	
    	},
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true,
                    max: 100,
                    min: 0
                }
            }]
        }
    }
});
</script>
	";	
}


function graficoRadar ($names, $values, $id) {
	echo '<canvas id="myRadarChart'.$id.'" style="height:90%;"></canvas>
	      <script type="text/javascript">
	      var ctx'.$id.' = document.getElementById("myRadarChart'.$id.'");
	      var myRadarChart'.$id.' = new Chart(ctx'.$id.', {
   			 type: "radar",
	      data: {
	         labels: [';
	                  foreach ($names as $name) {
	                     echo '"' . $name . '",';
	                  }
	                  echo '],
	         datasets: [
	               {
	                  label: "Dados primários",
	                  backgroundColor: "rgba(200, 153, 255,0.8)",
	                  borderColor: "rgba(50,50,50,0.8)",
	                  borderWidth: 1,
	                  data: [';
	                  foreach ($values as $value) {
	                     //echo json_encode($value) . ',';
	                     printf("%0.2f,",$value);
	                  }
	                  echo ']
	               }
	         ]},
	       options: {
	       	defaultFontSize: 12,
	      	responsive:true,
	      	legend: {
	      		display: false,
					labels: {
						display: false
					}	      	
	      	
	      	},
      	
	      	scale: {
		                ticks: {
		                    beginAtZero: true,
		                    max: 100,
		                    display: true,
		                    fontSize: 12
		                },
		                pointLabels: {
									fontSize: 18
		                }  
		        }
	      }
	      });</script>;';
}


function graficoPolar ($names, $values, $id) {
	echo '<canvas id="myPolarChart'.$id.'" style="width:40%;"></canvas>
	      <script type="text/javascript">
	      var ctx'.$id.' = document.getElementById("myPolarChart'.$id.'");
	      var myPolarChart'.$id.' = new Chart(ctx'.$id.', {
   			 type: "polarArea",
	      data: {
	         labels: [';
	                  foreach ($names as $name) {
	                     echo '"' . $name . '",';
	                  }
	                  echo '],
	         datasets: [
	               {
	                  label: "Dados primários",
	                  backgroundColor: [
                "rgba(255, 99, 132, 0.5)",
                "rgba(54, 162, 235, 0.5)",
                "rgba(255, 206, 86, 0.5)",
                "rgba(75, 192, 192, 0.5)",
                "rgba(153, 102, 255, 0.5)",
                "rgba(255, 159, 64, 0.5)",
                "rgba(130, 206, 86, 0.5)",
                "rgba(24, 192, 192, 0.5)",
                "rgba(100, 102, 255, 0.5)",
                "rgba(0, 159, 64, 0.5)",
                "rgba(100, 102, 34, 0.5)",
                "rgba(0, 245, 155, 0.5)"
            ],
            borderColor: [
                "rgba(255,99,132,1)",
                "rgba(54, 162, 235, 1)",
                "rgba(255, 206, 86, 1)",
                "rgba(75, 192, 192, 1)",
                "rgba(153, 102, 255, 1)",
                "rgba(255, 159, 64, 1)",
                "rgba(130, 206, 86, 1)",
                "rgba(24, 192, 192, 1)",
                "rgba(100, 102, 255, 1)",
                "rgba(0, 159, 64, 1)",
                "rgba(100, 102, 34, 1)",
                "rgba(0, 245, 155, 1)"
            ],
            borderWidth: 1,
	                  data: [';
	                  foreach ($values as $value) {
	                     printf("%0.2f,",$value);
	                  }
	                  echo ']
	               }
	         ]},
	       options: {
	       	defaultFontSize: 12,
	      	responsive:true,
	      	legend: {
	      		display: true,
					labels: {
						display: true
					}	      	
	      	
	      	},
	      	scale: {
		                ticks: {
		                    beginAtZero: true,
		                    max: 100,
		                    display: true,
		                    fontSize: 12
		                },
		                pointLabels: {
									fontSize: 18
		                }  
		        }
	      }
	      });</script>;';
}


function anti_injection($string) {
	// remove palavras que contenham sintaxe sql
	$string = preg_replace("/(from|FROM|select|SELECT|insert|INSERT|delete|DELETE|where|WHERE|drop table|DROP TABLE|show tables|SHOW TABLES|#|\*|--|\\\\)/","",$string);
	$string = trim($string);//limpa espaços vazio
	$string = strip_tags($string);//tira tags html e php
	$string = addslashes($string);//Adiciona barras invertidas a uma string
	return $string;
}
?>