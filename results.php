<!DOCTYPE html>
<html>

    <head>
        <meta content="text/html; charset=utf-8" http-equiv="content-type" />
        <!-- <link rel="stylesheet" href="estilo.css" type="text/css" media="screen" /> -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <title>SEWebS</title>
    </head>

    <body>
    <?php
    	include "conecta.php";
	?>

        <div class="container-fluid">
            <div class="jumbotron">
                <h2>Modelo de Avaliação de Qualidade dos Sistemas Educacionais
                    baseados em Web Semântica (SEWebS) </h2>
            </div>
            <div id="login" class="well well-sm">
                <?php
                include("valida.php");
                ?></div>
                  <div class="panel panel-default">
                    <div class="panel-body">
                        <h1>Resultado de avaliação</h1>
                        Você avaliou <strong>'<?php $Sql = mysql_query("SELECT * FROM tbform INNER JOIN tbapplication ON tbapplication.idtbApplication = tbform.tbApplication_idtbApplication WHERE tbform.idtbForm = ".$_GET['form']); while ($rr = mysql_fetch_array($Sql)) { echo $rr['tbApplicationName']; } ?></strong>'
                        como <strong>'<?php $Sql = mysql_query("SELECT * FROM `tbUserType` WHERE `idtbUserType` = ".$_SESSION['user_type']); while ($rr = mysql_fetch_array($Sql)) { echo $rr['tbUserTypeDescripton']; } ?>'  
						</strong>  
					</div>
               	 </div>

            <!-- 
            Exibir tipo de usuário e aplicação avaliada
            
            -->
            
           <div class="row">
                <div class="col-md-4">
                    <h2>Avaliação geral </h2>
                    <p>Pontuação total considerado todas as questões respondidas </p>
                    <?php
                        $total = 0;
                        $counter = 0;
                        $form = $_GET['form'];
                        if ($form != '') {
                            $Sql = "SELECT * FROM `tbform_has_tbuserquestion` WHERE `tbForm_idtbForm` = " . $form;
                            $rs = mysql_query($Sql, $conexao) or die("Formulário não existe");

                            while ($linha = mysql_fetch_array($rs)) {
                                $total = $total + $linha["tbForm_has_tbUserQuestionAnswer"];
                                $counter++;
                            }
                            if ($counter != 0)
								echo "<h2>";
								printf("%0.2f %%",($total/$counter*20));
								echo "</h2>";
                                echo "<br>Questões nessa categoria: ".$counter;
                            }
                        ?><a class="btn" href="#">Detalhes »</a>
                </div>
                <div class="col-md-4">
                    <h2>Entrega+Distribuição - Conteúdo </h2>
                    <p>Conteúdo distribuído e descentralizado na Web, mas ligados por ontologias, 
                        e é entregue pelo sistema de acordo com o perfil do usuário e seus tópicos 
                        de interesse. </p>
                        <?php
                        $total = 0;
                        $counter = 0;
                        $sum = array(array(array()));
                        $sym_weight = array(array(array()));
                        $form = $_GET['form'];
                        if ($form != '') {
                            //$Sql = "SELECT * FROM `tbform_has_tbuserquestion` WHERE `tbForm_idtbForm` = " . $form;
                            $Sql = "SELECT * FROM tbuserquestion INNER JOIN tbobjectives_has_tbuserquestion on tbuserquestion.idtbUserQuestion = tbobjectives_has_tbuserquestion.tbUserQuestion_idtbUserQuestion INNER JOIN tbform_has_tbuserquestion ON tbuserquestion.idtbUserQuestion = tbform_has_tbuserquestion.tbUserQuestion_idtbUserQuestion WHERE tbform_has_tbuserquestion.tbForm_idtbForm = ".$form." AND tbobjectives_has_tbuserquestion.tbObjectives_idtbObjectives = 2";
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
                            	echo "</p>";
                            }
                            
                            
                        ?>
                        
                        
                    <p><a class="btn" href="#">Detalhes »</a> </p>
                </div>
                <div class="col-md-4">
                    <h2>Acesso - Conteúdo e Sistema </h2>
                    <p>Acesso não linear, e expandido – estudante descreve uma situação 
                        e realiza consultas semânticas sobre um conteúdo adequado (de acordo 
                        com contexto da aprendizagem e perfil do usuário). </p>
                        <?php
                        $total = 0;
                        $counter = 0;
                        $form = $_GET['form'];
                        if ($form != '') {
                            //$Sql = "SELECT * FROM `tbform_has_tbuserquestion` WHERE `tbForm_idtbForm` = " . $form;
                            $Sql = "SELECT * FROM tbuserquestion INNER JOIN tbobjectives_has_tbuserquestion on tbuserquestion.idtbUserQuestion = tbobjectives_has_tbuserquestion.tbUserQuestion_idtbUserQuestion INNER JOIN tbform_has_tbuserquestion ON tbuserquestion.idtbUserQuestion = tbform_has_tbuserquestion.tbUserQuestion_idtbUserQuestion WHERE tbform_has_tbuserquestion.tbForm_idtbForm = ".$form." AND tbobjectives_has_tbuserquestion.tbObjectives_idtbObjectives = 4";
                            $rs = mysql_query($Sql, $conexao) or die("Formulário não existe");

                            while ($linha = mysql_fetch_array($rs)) {
                                $total = $total + $linha["tbForm_has_tbUserQuestionAnswer"]*$linha["tbObjectives_has_tbUserWeight"];
                                $counter = $counter + $linha["tbObjectives_has_tbUserWeight"];
                            }
                            if ($counter != 0)
                                //echo ($total / $counter * 20) . "%";
                                echo "<h2>";
                                printf("%0.2f %%",($total/$counter*20));
                                echo "</h2>";
                                echo "<br>Questões nessa categoria: ".$counter;
                        }
                        ?>

                    <p><a class="btn" href="#">Detalhes »</a> </p>
                </div>
                <div class="col-md-4">
                    <h2>Capacidade de Resposta - Sistema </h2>
                    <p>Sistema responde de forma proativa com materiais de aprendizagem 
                        de acordo com o contexto do problema atual (cada usuário tem seu agente 
                        personalizado que se comunica com outros agentes). </p>
                        <?php
                        $total = 0;
                        $counter = 0;
                        $form = $_GET['form'];
                        if ($form != '') {
                            //$Sql = "SELECT * FROM `tbform_has_tbuserquestion` WHERE `tbForm_idtbForm` = " . $form;
                            $Sql = "SELECT * FROM tbuserquestion INNER JOIN tbobjectives_has_tbuserquestion on tbuserquestion.idtbUserQuestion = tbobjectives_has_tbuserquestion.tbUserQuestion_idtbUserQuestion INNER JOIN tbform_has_tbuserquestion ON tbuserquestion.idtbUserQuestion = tbform_has_tbuserquestion.tbUserQuestion_idtbUserQuestion WHERE tbform_has_tbuserquestion.tbForm_idtbForm = ".$form." AND tbobjectives_has_tbuserquestion.tbObjectives_idtbObjectives = 1";
                            $rs = mysql_query($Sql, $conexao) or die("Formulário não existe");

                            while ($linha = mysql_fetch_array($rs)) {
                                $total = $total + $linha["tbForm_has_tbUserQuestionAnswer"]*$linha["tbObjectives_has_tbUserWeight"];
                                $counter = $counter + $linha["tbObjectives_has_tbUserWeight"];
                            }
                            if ($counter != 0)
                                //echo ($total / $counter * 20) . "%";
                                echo "<h2>";
                                printf("%0.2f %%",($total/$counter*20));
                                echo "</h2>";
                                echo "<br>Questões nessa categoria: ".$counter;
                        }
                        ?>

                    <p><a class="btn" href="#">Detalhes »</a> </p>
                </div>
                <div class="col-md-4">
                    <h2>Dinâmica da Aprendizagem - Aprendizagem
                    </h2>
                    <p>Todas as atividades são integradas (ambiente de negócio integrado 
                        - ex.: sistema de notas, matrícula, faltas, cursos já realizados) – 
                        aprendizagem ativa, estimulada pelo sistema por meio dos agentes inteligentes.
                    </p>
                        <?php
                        $total = 0;
                        $counter = 0;
                        $form = $_GET['form'];
                        if ($form != '') {
                            //$Sql = "SELECT * FROM `tbform_has_tbuserquestion` WHERE `tbForm_idtbForm` = " . $form;
                            $Sql = "SELECT * FROM tbuserquestion INNER JOIN tbobjectives_has_tbuserquestion on tbuserquestion.idtbUserQuestion = tbobjectives_has_tbuserquestion.tbUserQuestion_idtbUserQuestion INNER JOIN tbform_has_tbuserquestion ON tbuserquestion.idtbUserQuestion = tbform_has_tbuserquestion.tbUserQuestion_idtbUserQuestion WHERE tbform_has_tbuserquestion.tbForm_idtbForm = ".$form." AND tbobjectives_has_tbuserquestion.tbObjectives_idtbObjectives = 3";
                            $rs = mysql_query($Sql, $conexao) or die("Formulário não existe");

                            while ($linha = mysql_fetch_array($rs)) {
                                $total = $total + $linha["tbForm_has_tbUserQuestionAnswer"]*$linha["tbObjectives_has_tbUserWeight"];
                                $counter = $counter + $linha["tbObjectives_has_tbUserWeight"];
                            }
                            if ($counter != 0) {
                                //echo ($total / $counter * 20) . "%";
                                echo "<h2>";
                                printf("%0.2f %%",($total/$counter*20));
                                echo "</h2>";
                                echo "<br>Questões nessa categoria: ".$counter;
                            }
                      	}
                        ?>

                    <p><a class="btn" href="#">Detalhes »</a> </p>
                </div>
            </div>
            <div id="footer" class="well well-sm">
                Desenvolvimento: Ademir Marques Junior - 2016 </div>
        </div>

    </body>

</html>
