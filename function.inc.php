<?php                       

/**
 * Apaga as questões respondidas no banco de dados a partir da identificação do formulário
 * @param type $form_id 
 * @return type
 */
function apagarQuestoesUsuario ($form_id) {
    include "conecta.php";
    $Sql = 'DELETE FROM `tbform_has_tbuserquestion` WHERE `tbform_has_tbuserquestion`.`tbForm_idtbForm` = '.$form_id;
    $rs = mysqli_query($conexao, $Sql) or die ('Erro ao apagar');
    
    $Sql = "UPDATE `tbform` SET `tbFormCompleted` = '0',`tbFormStatus` = '0' WHERE `tbform`.`idtbForm` = ".$form_id;
    $rs = mysqli_query($conexao, $Sql) or die ('Erro ao atualizar');
}


/**
* Apaga um formulário do banco de dados. Requer que não haja questões respondidas no banco de dados
* @param type $form_id 
* @return type
*/
function apagarFormUsuario ($form_id) {
    include "conecta.php";
    
    $Sql = 'DELETE FROM `tbform` WHERE `tbform`.`idtbForm` = '.$form_id;
    $rs = mysqli_query($conexao, $Sql);
} 


/**
 * Extrai do banco de dados as questões juntamente com as respostas e o peso das mesmas.
 * @param type $value pode ser o número do formulário ou da aplicação a ser listada
 * @param type $set se o valor recebido for 1 as questões são listadas por aplicação. se o valor for 2 lista por formulário
 * @return retorna uma matriz com 5 matrizes. $sum e $sum_weight são duas matrizes [artefato][fator][subfator]. 
 */
function extraiResultados ($value, $set) {
	include "conecta.php";
	$total = 0; //somatório geral
	$total_weight = 0; //acumulado dos pesos
    $counter = 0; //contador 
    $sum = array(array(array())); //matriz com as somas das questões para um mesmo conjunto artefato-fator-subfator
    $sum_weight = array(array(array())); //matriz com as somas dos pesos das questões para um mesmo conjunto artefato-fator-subfator

    if ($value != '') {
		if ($set == 1) { //lista por aplicação
			$Sql = "SELECT DISTINCT idtbform_has_tbuserquestion, tbForm_has_tbUserQuestionAnswer, tbUserType_has_tbUserQuestionWeight, tbArtifact_idtbArtifact, tbFactor_idtbFactor, tbSubFactor_idtbSubFactor FROM `tbform_has_tbuserquestion` INNER JOIN tbform ON  `tbForm_idtbForm` = tbform.idtbform INNER JOIN tbquestion ON tbuserquestion_idtbuserquestion = tbquestion.idtbquestion INNER JOIN tbquestionid ON tbquestion.tbquestionid_idtbquestionid = tbquestionid.idtbquestionid INNER JOIN tbusertype_has_tbuserquestion ON tbquestionid.idtbQuestionId = tbusertype_has_tbuserquestion.tbQuestionId_idtbQuestionId WHERE tbform.tbapplication_idtbapplication = ".$value;
		} elseif($set == 2) { //lista por formulário
			$Sql = "SELECT DISTINCT tbuserquestion_idtbuserquestion, tbForm_has_tbUserQuestionAnswer, tbUserType_has_tbUserQuestionWeight, tbArtifact_idtbArtifact, tbFactor_idtbFactor, tbSubFactor_idtbSubFactor FROM `tbform_has_tbuserquestion` INNER JOIN tbquestion ON tbuserquestion_idtbuserquestion = tbquestion.idtbquestion INNER JOIN tbquestionid ON tbquestion.tbquestionid_idtbquestionid = tbquestionid.idtbquestionid INNER JOIN tbform ON tbform_has_tbuserquestion.tbform_idtbform = tbform.idtbform INNER JOIN tbusertype_has_tbuserquestion ON tbform.tbUserType_idtbUserType =  tbusertype_has_tbuserquestion.tbUserType_idtbUserType WHERE `tbForm_idtbForm` = ".$value;
		}	
        $rs = mysqli_query($conexao, $Sql) or die("Formulário não existe");

        while ($row = mysqli_fetch_assoc($rs)) {
            $total = $total + $row["tbForm_has_tbUserQuestionAnswer"]*$row["tbUserType_has_tbUserQuestionWeight"];
            $total_weight = $total_weight + $row["tbUserType_has_tbUserQuestionWeight"];
            $counter++;
            
            if (!isset($sum[$row["tbArtifact_idtbArtifact"]][$row["tbFactor_idtbFactor"]][$row["tbSubFactor_idtbSubFactor"]])){
            	$sum[$row["tbArtifact_idtbArtifact"]][$row["tbFactor_idtbFactor"]][$row["tbSubFactor_idtbSubFactor"]] = NULL;
            	$sum_weight[$row["tbArtifact_idtbArtifact"]][$row["tbFactor_idtbFactor"]][$row["tbSubFactor_idtbSubFactor"]] = NULL;
            }
            
            $sum[$row["tbArtifact_idtbArtifact"]][$row["tbFactor_idtbFactor"]][$row["tbSubFactor_idtbSubFactor"]] = $sum[$row["tbArtifact_idtbArtifact"]][$row["tbFactor_idtbFactor"]][$row["tbSubFactor_idtbSubFactor"]] + $row["tbForm_has_tbUserQuestionAnswer"]*$row["tbUserType_has_tbUserQuestionWeight"];
            $sum_weight[$row["tbArtifact_idtbArtifact"]][$row["tbFactor_idtbFactor"]][$row["tbSubFactor_idtbSubFactor"]] = $sum_weight[$row["tbArtifact_idtbArtifact"]][$row["tbFactor_idtbFactor"]][$row["tbSubFactor_idtbSubFactor"]] + $row["tbUserType_has_tbUserQuestionWeight"];
        }
     }
        
     return array($sum, $sum_weight, $total, $total_weight, $counter);
}

/**
 * Detalha a matriz de resultado agrupando por artefato, fator e subfator
 * @param type $resultados deve ser uma matriz com no mínino duas matrizes [artefato][fator][subfator] com os resultados das questões e com os pesos
 * @return retorna uma matriz com 6 matrizes $artifact_value,$artifact_name,$Factor_value,$Factor_name,$subFactor_value e $subFactor_name
 */
function detalhaResultados ($resultados) {
    include "conecta.php";
	$sum = $resultados[0];
	$sum_weight = $resultados[1];
	
	$subFactor_value = array(array(array()));
	$subFactor_name = array(array(array()));	
	$Factor_value = array(array());
	$Factor_name = array(array());
	$artifac_value = array();
	$artifact_name = array();

    foreach ($sum as $i=>$valueartifact) { //Varre os artefatos, os fatores e então os subfatores agrupando-os nas matrizes de retorno  
    	echo "<p>";
    	$artifact_sum = 0;
    	$artifact_weight = 0;

    	foreach ($valueartifact as $j=>$valueFactor) {
    		$Factor_sum = 0;
    		$Factor_weight = 0;
    		
    		foreach ($valueFactor as $k=>$valuesubFactor) {
    			$Sql = "SELECT * FROM `tbsubfactor` INNER JOIN tbsubfactortext ON tbsubfactor.idtbsubfactor = tbsubfactortext.tbsubfactor_idtbSubfactor  WHERE idtbSubFactor = ".$k." AND tblanguage_idtblanguage = ".$_SESSION['language'];   			
    			$rs = mysqli_query($conexao, $Sql);
            	while ($row = mysqli_fetch_assoc($rs)) {
            		//echo $rr['tbSubFactorDesc']." "; 
            		$subFactor = $row['tbSubFactorName'];
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
            while ($row = mysqli_fetch_assoc($rs)) {
                $Factor = $row['tbFactorName'];
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
       	while ($row = mysqli_fetch_assoc($rs)) {
        		$artifact = $row['tbArtifactName'];
        } 
		if ($artifact_weight != 0) {
			$result = $artifact_sum/$artifact_weight*20;
			$artifact_name[$i] = $artifact;
			$artifact_value[$i] = $result;
		}

	}
	
	return array($artifact_value,$artifact_name,$Factor_value,$Factor_name,$subFactor_value,$subFactor_name);

}


/**
 * Exibe um gráfico em um canvas html5 utiliza a biblioteca javascript Chart.js
 * @param type $names nomes dos dados a serem exibidos
 * @param type $values valores dos dados a serem exibidos
 * @param type $id id do elemento html. Não deve ser repetido dentro da página exibida
 * @return type nenhum
 */
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
    options: {legend: {display: false, labels: {display: false}}, scales: {xAxes: [{ticks: {beginAtZero:true, max: 100, min: 0}}]}}});
</script>";	
}

/**
 * Exibe um gráfico em um canvas html5 utiliza a biblioteca javascript Chart.js
 * @param type $names nomes dos dados a serem exibidos
 * @param type $values valores dos dados a serem exibidos
 * @param type $id id do elemento html. Não deve ser repetido dentro da página exibida
 * @return type nenhum
 */
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
    options: {legend: {display: false, labels: {display: false}}, scales: {yAxes: [{ticks: {beginAtZero:true, max: 100, min: 0}}]}}}); </script>";	
}

/**
 * Exibe um gráfico em um canvas html5 utiliza a biblioteca javascript Chart.js
 * @param type $names nomes dos dados a serem exibidos
 * @param type $values valores dos dados a serem exibidos
 * @param type $id id do elemento html. Não deve ser repetido dentro da página exibida
 * @return type nenhum
 */
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
	       options: { defaultFontSize: 12,	responsive:true, legend: {display: false,	labels: {display: false}}, scale: {ticks: {beginAtZero: true, max: 100, display: true, fontSize: 12}, pointLabels: {fontSize: 18}}}}); </script>;';
}


/**
 * Exibe um gráfico em um canvas html5 utiliza a biblioteca javascript Chart.js
 * @param type $names nomes dos dados a serem exibidos
 * @param type $values valores dos dados a serem exibidos
 * @param type $id id do elemento html. Não deve ser repetido dentro da página exibida
 * @return type nenhum
 */
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
	       options: {defaultFontSize: 12, responsive:true, legend: {display: true, labels: {display: true}}, scale: {ticks: {beginAtZero: true, max: 100, display: true, fontSize: 12}, pointLabels: {fontSize: 18}}}}); </script>;';
}


/**
 * Remove caracteres de uma string que podem ser invasivos ao banco de dados
 * @param type $string texto a ser verificado e limpo de tentativas de sql injection
 * @return type string com os caracteres nocivos retirados
 */
function anti_injection($string) {
	// remove palavras que contenham sintaxe sql
	$string = preg_replace("/(from|FROM|select|SELECT|insert|INSERT|delete|DELETE|where|WHERE|drop table|DROP TABLE|show tables|SHOW TABLES|#|\*|--|\\\\)/","",$string);
	$string = trim($string);//limpa espaços vazio
	$string = strip_tags($string);//tira tags html e php
	$string = addslashes($string);//Adiciona barras invertidas a uma string
	return $string;
}

/**
 * Envia email de notificação para usuário cadastrado em uma avaliação
 * @param type $email email do destinatário
 * @param type $user nome do destinatário
 * @param type|null $from remetente
 * @return type nenhum
*/    
function notifyUser ($email, $user, $from = NULL) {
    include "language.php";
	$subject = $lang['EMAIL_SUBJECT_PENDING_USER'];
	$txt =' <html><head><title>HTML email</title></head>
        		<body>
            		<h3>'.$lang['EMAIL_GREETING'].' '.$user.'</h3>
            		'.$lang['EMAIL_SUBJECT_PENDING_USER_TEXT'].'
        		</body>
        	</html>';
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= 'From: <no-reply@caed-lab.com>' . "\r\n";
	return mail($email,$subject,$txt,$headers);
}

/**
 * Envia email de notificação para usuário cadastrado em uma avaliação. $valid é o código de validação criado como variável global no momento de execução do script. Nota: Incorporar como argumento
 * @param type $email email do destinatário
 * @param type $user nome do destinatário
 * @param type $user_level
 * @return type nenhum
*/
function notifyNewUser ($email, $user, $user_level = 1) {
    include "language.php";
	$subject = $lang['EMAIL_SUBJECT_NEW_USER'];
	$txt = '<html><head><title>HTML email</title></head>
		<body>
		<h3>'.$lang['EMAIL_GREETING'].' '.$user.'</h3>
        '.$lang['EMAIL_SUBJECT_NEW_USER_TEXT'].'
		<a href="http://avaliasewebs.caed-lab.com/cadastrar.php?valid='.$valid.'" target="_blank">http://avaliasewebs.caed-lab.com/cadastrar.php?valid='.$valid.'</a>';
        if ($user_level == 1) { $txt .= $lang['EMAIL_SUBJECT_NEW_USER_TEXT_1'];}
        if ($user_level == 2) { $txt .= $lang['EMAIL_SUBJECT_NEW_USER_TEXT_2'];}
        if ($user_level == 3) { $txt .= $lang['EMAIL_SUBJECT_NEW_USER_TEXT_3'];}
		echo "</body>
		</html>";
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= 'From: <no-reply@caed-lab.com>' . "\r\n";
	
	return mail($email,$subject,$txt,$headers);
}

/**
 * Envia notificação de email para usuário que deseja cadastrar uma nova senha. $valid é o código de validação criado como variável global no momento de execução do script. Nota: Incorporar como argumento
 * @param type $email email do destinatário
 * @param type $user nome do destinatário
 * @param type|null $from remetente
 * @return type nenhum
*/ 
function notifyNewPassword ($email, $user, $from = NULL) {
    include "language.php";
	$subject = $lang['EMAIL_SUBJECT_PASSWORD'];
	$txt = '<html><head><title>HTML email</title></head>
		<body>
		<h3>'.$lang['EMAIL_GREETING'].' '.$user.'</h3>
		'.$lang['EMAIL_SUBJECT_PASSWORD_TEXT'].'
		<a href="http://avaliasewebs.caed-lab.com/cadastrar.php?valid='.$valid.'" target="_blank">http://avaliasewebs.caed-lab.com/cadastrar.php?valid='.$valid.'</a>
		</body>
		</html>';
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= 'From: <no-reply@caed-lab.com>' . "\r\n";		
	mail($email,$subject,$txt,$headers);
}


/**
 * Envia notificação de alteração de usuário
 * @param type $email email do destinatário
 * @param type $user nome do destinatário
 * @param type $user_level novo nível do usuário cadastrado
 * @return type nenhum
 */
function notifyAlteredUser ($email, $user, $user_level) {
    include "language.php";
    $subject = $lang['EMAIL_SUBJECT_ALTERED'];
    $txt = '<html><head><title>HTML email</title></head>
    <body>
        <h3>'.$lang['EMAIL_GREETING'].' '.$user.'</h3>
        '.$lang['EMAIL_SUBJECT_ALTERED_TEXT'];
                            
        if ($user_level == 1) { $txt .= $lang['EMAIL_SUBJECT_NEW_USER_TEXT_1'];}
        if ($user_level == 2) { $txt .= $lang['EMAIL_SUBJECT_NEW_USER_TEXT_2'];}
        if ($user_level == 3) { $txt .= $lang['EMAIL_SUBJECT_NEW_USER_TEXT_3'];}
    $txt .= "</body></html>";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <no-reply@caed-lab.com>' . "\r\n";
                        
    mail($to,$subject,$txt,$headers);
}

/**
 * Exibe o id e o texto de uma questão nos idiomas em que a questão estiver cadastrada
 * @param type $id o id da questão no banco de dados
 * @return nenhum
 */
function mostrar_questao ($id) {
  //Mostrar a questão a ser manipulada
    include "conecta.php";
    $Sql = "SELECT * FROM tbquestionid INNER JOIN tbquestiontext ON tbquestionid.idtbQuestionId = tbquestiontext.tbQuestionId_idtbQuestionId WHERE idtbquestionid = ".$id;
    $rs = mysqli_query($conexao, $Sql);
    while($row = mysqli_fetch_assoc($rs)) {
    echo "<strong>".$row['idtbQuestionId']."</strong> - ".$row['tbQuestionText']."<br>";
    }
}

/**
 * Retorna uma matriz com o texto e o texto de ajuda de uma questão de acordo com o idioma solicitado
 * @param type $id o id da questão no banco de dados
 * @param type $lang deve ser o valor inteiro de um dos idiomas cadastrados
 * @return type
 */
function mostrar_questao2 ($id, $lang) {
    include "conecta.php";
    $Sql = "SELECT * FROM tbquestionid INNER JOIN tbquestiontext ON tbquestionid.idtbQuestionId = tbquestiontext.tbQuestionId_idtbQuestionId WHERE idtbquestionid = ".$id;
    if ($lang == 1) $Sql .= " AND tbLanguage_idtbLanguage = 1";
    if ($lang == 2) $Sql .= " AND tbLanguage_idtbLanguage = 2";
    $rs = mysqli_query($conexao, $Sql);
                    
    if (mysqli_num_rows($rs) == 0) return false;
                    
    $value[] = NULL;
    $row = mysqli_fetch_assoc($rs);
        $value[1] = $row['tbQuestionText'];
        $value[2] = $row['tbQuestionTextHowTo'];
              
    return $value;
}


/**
 * Exibe as Ontologias, Fatores e Subfatores inseridos para uma questão
 * @param type $id o id da questão no banco de dados
 * @return nenhum
 */
function mostrar_detalhes ($id) {
    include "conecta.php";
    $Sql = "SELECT * FROM `tbquestion` INNER JOIN tbartifacttext ON tbquestion.tbArtifact_idtbArtifact = tbartifacttext.tbArtifact_idtbArtifact INNER JOIN tbfactortext ON tbquestion.tbFactor_idtbFactor = tbfactortext.tbFactor_idtbFactor INNER JOIN tbsubfactortext ON tbquestion.tbSubFactor_idtbSubFactor = tbsubfactortext.tbSubFactor_idtbSubFactor WHERE tbartifacttext.tbLanguage_idtbLanguage = 1 AND tbfactortext.tbLanguage_idtbLanguage = 1 AND tbsubfactortext.tbLanguage_idtbLanguage = 1 AND tbquestion.tbQuestionId_idtbQuestionId = ".$id;
    $rs = mysqli_query($conexao, $Sql);
    while($row = mysqli_fetch_assoc($rs)) {
                        
        echo '<form id="form' . $row['idtbQuestion'] . '" name="form' . $row['idtbQuestion'] . '" method="post" action="questioneditor.php" class="form-inline">            
               <input type="hidden" size="100" value="' . $row['idtbQuestion'] . '" name="ArtFatSub_id"  style="display:none">
               <input type="hidden" size="100" value="' . $id . '" name="Question_id"  style="display:none">
               <input type="submit" name="SubmitExcludeArtFatSub" value="x" class="btn btn-danger btn-xs"/>
               '.$row['tbArtifactName'].' - '.$row['tbFactorName'].' - '.$row['tbSubFactorName'].'                               
          </form><br>';
      }                   
}

/**
 * Lista artefatos em um select
 * @return nenhum
 */
function select_artefatos () {
    include "conecta.php";
    echo '<select name="sel_artifact" id="artifact" class="form-control"><option value="" disabled required selected>Artefato</option>';
    $Sql = "SELECT * FROM tbartifact INNER JOIN tbartifacttext ON tbartifact.idtbArtifact = tbartifacttext.tbArtifact_idtbArtifact WHERE tbartifacttext.tbLanguage_idtbLanguage = 1";
    $rs = mysqli_query($conexao, $Sql);
    while ($row = mysqli_fetch_assoc($rs)) {
        echo "<option value=".$row['idtbArtifact'].">".$row['tbArtifactName']."</option>";
    }
    echo '</select>';                   
}               

/**
 * Lista fatores em um select
 * @return nenhum
 */              
function select_fatores () {
    include "conecta.php";
    echo '<select name="sel_factor" id="factor"class="form-control"><option value="" disabled required selected>Fator</option>';
    $Sql = "SELECT * FROM tbfactor INNER JOIN tbfactortext ON tbfactor.idtbFactor = tbfactortext.tbFactor_idtbFactor WHERE tbfactortext.tbLanguage_idtbLanguage = 1";
    $rs = mysqli_query($conexao, $Sql);
    while ($row = mysqli_fetch_assoc($rs)) {
        echo "<option value=".$row['idtbFactor'].">".$row['tbFactorName']."</option>";
    }
    echo '</select>';
}

/**
 * Lista subfatores em um select
 * @return nenhum
 */             
function select_subfatores () {
    include "conecta.php";
    echo '<select name="sel_subfactor" id="subfactor"class="form-control"><option value="" disabled required selected>Subfator</option>';
    $Sql = "SELECT * FROM tbsubfactor INNER JOIN tbsubfactortext ON tbsubfactor.idtbsubFactor = tbsubfactortext.tbsubFactor_idtbsubFactor WHERE tbsubfactortext.tbLanguage_idtbLanguage = 1 ORDER BY tbsubfactortext.tbSubFactorName";
    $rs = mysqli_query($conexao, $Sql);
    while ($row = mysqli_fetch_assoc($rs)) {
        echo "<option value=".$row['idtbSubFactor'].">".$row['tbSubFactorName']."</option>";
    }
    echo '</select><br>';
}


?>