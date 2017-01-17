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
        $rs = mysqli_query($conexao, $Sql) or die("Formulário não existe");

        while ($linha = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
            $total = $total + $linha["tbForm_has_tbUserQuestionAnswer"]*$linha["tbObjectives_has_tbUserWeight"];
            $total_weight = $total_weight + $linha["tbObjectives_has_tbUserWeight"];
            $counter++;
            
            if (!isset($sum[$linha["tbArtifact_idtbArtifact"]][$linha["tbFactor_idtbFactor"]][$linha["tbSubFactor_idtbSubFactor"]])){
            	$sum[$linha["tbArtifact_idtbArtifact"]][$linha["tbFactor_idtbFactor"]][$linha["tbSubFactor_idtbSubFactor"]] = NULL;
            	$sum_weight[$linha["tbArtifact_idtbArtifact"]][$linha["tbFactor_idtbFactor"]][$linha["tbSubFactor_idtbSubFactor"]] = NULL;
            }
            
            $sum[$linha["tbArtifact_idtbArtifact"]][$linha["tbFactor_idtbFactor"]][$linha["tbSubFactor_idtbSubFactor"]] = $sum[$linha["tbArtifact_idtbArtifact"]][$linha["tbFactor_idtbFactor"]][$linha["tbSubFactor_idtbSubFactor"]] + $linha["tbForm_has_tbUserQuestionAnswer"]*$linha["tbObjectives_has_tbUserWeight"];
            $sum_weight[$linha["tbArtifact_idtbArtifact"]][$linha["tbFactor_idtbFactor"]][$linha["tbSubFactor_idtbSubFactor"]] = $sum_weight[$linha["tbArtifact_idtbArtifact"]][$linha["tbFactor_idtbFactor"]][$linha["tbSubFactor_idtbSubFactor"]] + $linha["tbObjectives_has_tbUserWeight"];
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
    			$Sql = "SELECT * FROM `tbsubFactor` INNER JOIN tbSubFactorText ON tbSubFactor.idtbSubFactor = tbSubFactorText.tbsubfactor_idtbSubfactor  WHERE idtbSubFactor = ".$k." AND tblanguage_idtblanguage = ".$_SESSION['language'];
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
		
		
        	$Sql = "SELECT * FROM `tbFactor` INNER JOIN tbFactorText ON tbFactor.idtbFactor = tbFactorText.tbfactor_idtbfactor WHERE idtbFactor = ".$j." AND tblanguage_idtblanguage = ".$_SESSION['language'];
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
		
		
    	$Sql = "SELECT * FROM `tbartifact` INNER JOIN tbartifacttext ON tbartifact.idtbArtifact = tbArtifacttext.tbArtifact_idtbArtifact WHERE idtbArtifact = ".$i." AND tblanguage_idtblanguage = ".$_SESSION['language'];
    	$rs = mysqli_query($conexao, $Sql);
       	while ($linha = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
        		//echo "<strong>".$rr['tbArtifactDescription']."</strong> ";
        		$artifact = $linha['tbArtifactDesc'];
        } 
		if ($artifact_weight != 0) {
			$result = $artifact_sum/$artifact_weight*20;
			$artifact_name[$i] = $artifact;
			$artifact_value[$i] = $result;
		}

	}
	
	return array($artifact_value,$artifact_name,$Factor_value,$Factor_name,$subFactor_value,$subFactor_name);

}


?>