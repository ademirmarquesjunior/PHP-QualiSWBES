<?php                       


function extraiResultados ($form, $objective) {
	include "conecta.php";
	$total = 0;
	$total_weight = 0;
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
            $total_weight = $total_weight + $linha["tbObjectives_has_tbUserWeight"];
            $counter++;
            
            if (!isset($sum[$linha["tbArtifact_idtbArtifact"]][$linha["tbCriterion_idtbCriterion"]][$linha["tbSubCriterion_idtbSubCriterion"]])){
            	$sum[$linha["tbArtifact_idtbArtifact"]][$linha["tbCriterion_idtbCriterion"]][$linha["tbSubCriterion_idtbSubCriterion"]] = NULL;
            	$sum_weight[$linha["tbArtifact_idtbArtifact"]][$linha["tbCriterion_idtbCriterion"]][$linha["tbSubCriterion_idtbSubCriterion"]] = NULL;
            }
            
            $sum[$linha["tbArtifact_idtbArtifact"]][$linha["tbCriterion_idtbCriterion"]][$linha["tbSubCriterion_idtbSubCriterion"]] = $sum[$linha["tbArtifact_idtbArtifact"]][$linha["tbCriterion_idtbCriterion"]][$linha["tbSubCriterion_idtbSubCriterion"]] + $linha["tbForm_has_tbUserQuestionAnswer"]*$linha["tbObjectives_has_tbUserWeight"];
            $sum_weight[$linha["tbArtifact_idtbArtifact"]][$linha["tbCriterion_idtbCriterion"]][$linha["tbSubCriterion_idtbSubCriterion"]] = $sum_weight[$linha["tbArtifact_idtbArtifact"]][$linha["tbCriterion_idtbCriterion"]][$linha["tbSubCriterion_idtbSubCriterion"]] + $linha["tbObjectives_has_tbUserWeight"];
        }
     }
        
        return array($sum, $sum_weight, $total, $total_weight, $counter);
}

function detalhaResultados ($resultados) {
	$sum = $resultados[0];
	$sum_weight = $resultados[1];
	
	$subcriterion_value = array(array(array()));
	$subcriterion_name = array(array(array()));	
	$criterion_value = array(array());
	$criterion_name = array(array());
	$artifac_value = array();
	$artifact_name = array();
	
    include "conecta.php";
   
    foreach ($sum as $i=>$valueartifact) {
    	echo "<p>";
    	$artifact_sum = 0;
    	$artifact_weight = 0;

    	foreach ($valueartifact as $j=>$valuecriterion) {
    		$criterion_sum = 0;
    		$criterion_weight = 0;
    		
    		//echo "...".$j."<br>";
    		foreach ($valuecriterion as $k=>$valuesubcriterion) {
    			$Sql = mysql_query("SELECT * FROM `tbSubCriterion` WHERE idtbSubCriterion = ".$k);
            	while ($rr = mysql_fetch_array($Sql)) {
            		//echo $rr['tbSubCriterionDesc']." "; 
            		$subcriterion = $rr['tbSubCriterionDesc'];
				}  
    		
    			if ($sum_weight[$i][$j][$k] != 0) {
	    			$result = $valuesubcriterion/$sum_weight[$i][$j][$k]*20;
	    			$criterion_sum = $criterion_sum + $valuesubcriterion;
	    			$criterion_weight = $criterion_weight + $sum_weight[$i][$j][$k];
	    			$subcriterion_value[$i][$j][$k] = $result;
	    			$subcriterion_name[$i][$j][$k] = $subcriterion;
    			}
    	   	}	
		

			
        	$Sql = mysql_query("SELECT * FROM `tbCriterion` WHERE idtbCriterion = ".$j);
            	while ($rr = mysql_fetch_array($Sql)) {
            		//echo "<strong>".$rr['tbCriterionDesc']."</strong> "; 
            		$criterion = $rr['tbCriterionDesc'];
			}
			if ($criterion_weight != 0){
				$result = $criterion_sum/$criterion_weight*20;
	
			    $criterion_value[$i][$j] = $result;
				$criterion_name[$i][$j] = $criterion;
			}
						
	    	$artifact_sum = $artifact_sum + $criterion_sum;
	    	$artifact_weight = $artifact_weight + $criterion_weight;
			
		}
		
		
    	$Sql = mysql_query("SELECT * FROM `tbArtifact` WHERE idtbArtifact = ".$i);
        	while ($rr = mysql_fetch_array($Sql)) {
        		//echo "<strong>".$rr['tbArtifactDescription']."</strong> ";
        		$artifact = $rr['tbArtifactDescription'];
        } 
		if ($artifact_weight != 0) {
			$result = $artifact_sum/$artifact_weight*20;
			$artifact_name[$i] = $artifact;
			$artifact_value[$i] = $result;
		}

	}
	
	return array($artifact_value,$artifact_name,$criterion_value,$criterion_name,$subcriterion_value,$subcriterion_name);

}


?>