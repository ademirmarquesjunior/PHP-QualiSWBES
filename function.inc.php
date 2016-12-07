<?php                       


function extraiResultados ($form, $objective) {
	include "conecta.php";
	$total = 0;
    $counter = 0;
    $sum = array(array(array()));
    $sym_weight = array(array(array()));
    //$form = $_GET['form'];
    if ($form != '') {
        //$Sql = "SELECT * FROM `tbform_has_tbuserquestion` WHERE `tbForm_idtbForm` = " . $form;
        $Sql = "SELECT * FROM tbuserquestion INNER JOIN tbobjectives_has_tbuserquestion on tbuserquestion.idtbUserQuestion = tbobjectives_has_tbuserquestion.tbUserQuestion_idtbUserQuestion INNER JOIN tbform_has_tbuserquestion ON tbuserquestion.idtbUserQuestion = tbform_has_tbuserquestion.tbUserQuestion_idtbUserQuestion WHERE tbform_has_tbuserquestion.tbForm_idtbForm = ".$form." AND tbobjectives_has_tbuserquestion.tbObjectives_idtbObjectives = ".$objective;
        $rs = mysql_query($Sql, $conexao) or die("Formulário não existe");

        while ($linha = mysql_fetch_array($rs)) {
            $total = $total + $linha["tbForm_has_tbUserQuestionAnswer"]*$linha["tbObjectives_has_tbUserWeight"];
            $counter = $counter + $linha["tbObjectives_has_tbUserWeight"];
            
            if (!isset($sum[$linha["tbArtifact_idtbArtifact"]][$linha["tbCriterion_idtbCriterion"]][$linha["tbSubCriterion_idtbSubCriterion"]])){
            	$sum[$linha["tbArtifact_idtbArtifact"]][$linha["tbCriterion_idtbCriterion"]][$linha["tbSubCriterion_idtbSubCriterion"]] = NULL;
            	$sum_weight[$linha["tbArtifact_idtbArtifact"]][$linha["tbCriterion_idtbCriterion"]][$linha["tbSubCriterion_idtbSubCriterion"]] = NULL;
            }
            
            $sum[$linha["tbArtifact_idtbArtifact"]][$linha["tbCriterion_idtbCriterion"]][$linha["tbSubCriterion_idtbSubCriterion"]] = $sum[$linha["tbArtifact_idtbArtifact"]][$linha["tbCriterion_idtbCriterion"]][$linha["tbSubCriterion_idtbSubCriterion"]] + $linha["tbForm_has_tbUserQuestionAnswer"]*$linha["tbObjectives_has_tbUserWeight"];
            $sum_weight[$linha["tbArtifact_idtbArtifact"]][$linha["tbCriterion_idtbCriterion"]][$linha["tbSubCriterion_idtbSubCriterion"]] = $sum_weight[$linha["tbArtifact_idtbArtifact"]][$linha["tbCriterion_idtbCriterion"]][$linha["tbSubCriterion_idtbSubCriterion"]] + $linha["tbObjectives_has_tbUserWeight"];
        }
        if ($counter != 0)
			echo "<p>Atende:";
			echo "<h2>";
            printf("%0.2f %%",($total/$counter*20)); 
            echo "</h2></p>";
            echo "<p>Questões nessa categoria: ".$counter."</p>";
        }
        
        return array($sum, $sum_weight);
}

function detalhaResultados ($resultados) {
	$sum = $resultados[0];
	$sum_weight = $resultados[1];
    include "conecta.php";
    foreach ($sum as $i=>$valueartifact) {
    	echo "<p><br>";
    	$Sql = mysql_query("SELECT * FROM `tbArtifact` WHERE idtbArtifact = ".$i);
        	while ($rr = mysql_fetch_array($Sql)) {
        		echo $rr['tbArtifactDescription']."<br> "; 
        	} 
    	foreach ($valueartifact as $j=>$valuecriterion) {
        	$Sql = mysql_query("SELECT * FROM `tbCriterion` WHERE idtbCriterion = ".$j);
            	while ($rr = mysql_fetch_array($Sql)) {
            		echo $rr['tbCriterionDesc']."<br> "; 
				}                           	
    	
    		//echo "...".$j."<br>";
    		foreach ($valuecriterion as $k=>$valuesubcriterion) {
    			$Sql = mysql_query("SELECT * FROM `tbSubCriterion` WHERE idtbSubCriterion = ".$k);
            	while ($rr = mysql_fetch_array($Sql)) {
            		echo $rr['tbSubCriterionDesc']." "; 
				}  
    		
    			$result = $valuesubcriterion/$sum_weight[$i][$j][$k]*20;
    			printf("%0.2f %%",$result); 
    			//echo " ".$result;
    			echo "<br>";
    			
			}
		}
	}
}


?>