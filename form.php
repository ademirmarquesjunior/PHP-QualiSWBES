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
		<!-- <link rel="stylesheet" type="text/css" href="slider/rzslider.css"/> -->
		<script src="js/jquery-3.1.1.min.js"></script>        
		<script src="js/bootstrap.min.js"></script>
		<script src="js/bootstrap-slider.min.js"></script>
		<link href="css/bootstrap-slider.min.css" rel="stylesheet">
		<script src="js/sweetalert.js"></script>
		<!-- <script src="slider/angular.min.js"></script>
		<script src="slider/rzslider.min.js"></script>  -->       
		<link rel="icon" type="image/png" href="favicon.png">
		<title>Avalia SEWebS</title>
    </head>

    <body>
        <div class="container-fluid">
            <?php
            include 'header.php';
            include 'navbar.php';


//----------Obter as informações formulário das variáveis de sessão--------------------------------------------------------//
            $Sql = "SELECT * FROM `tbform` INNER JOIN tbusertypetext ON tbform.tbUserType_idtbUserType = tbusertypetext.tbUserType_idtbUserType INNER JOIN `tbapplication` ON tbapplication.idtbapplication = tbform.tbapplication_idtbapplication WHERE tbform.idtbForm = " . $_SESSION['form_id'] . " AND tbLanguage_idtbLanguage = " . $_SESSION['language'];
            $rs = mysqli_query($conexao, $Sql) or die ("Formulário não encontrado");
            if (mysqli_num_rows($rs) == 0) {
            	echo "<script> window.location.assign('index3.php'); </script>";
            }
            $row = mysqli_fetch_array($rs, MYSQLI_ASSOC);
            $applic_id = $row['idtbApplication'];
            $applic_name = $row['tbApplicationName'];
            $type_name = $row['tbUserTypeDesc'];
            $status = $row['tbFormStatus'];
            $user_type = $row['tbUserType_idtbUserType'];
            
//----------Obter a quantidade de objetos de aprendizagem para o formulário-------------------------------------------------//           
            $Sql = "SELECT * FROM `tblearningobjects` WHERE tbapplication_idtbapplication = ".$applic_id;
            $rs = mysqli_query($conexao, $Sql) or die ("Formulário não encontrado");
            $num_learningobjects = mysqli_num_rows($rs);
           
//----------Obter a quantidade de ontologias para o formulário-------------------------------------------------------------//
				$Sql = "SELECT * FROM `tbontologies` WHERE tbapplication_idtbapplication = ".$applic_id;
				$rs = mysqli_query($conexao, $Sql) or die ("Formulário não encontrado");
				$num_onlogies =  mysqli_num_rows($rs);
				
            $inserted = 0;

//----------Insere as questões respondidas---------------------------------------------------------------------------------------------//
            foreach ($_POST as $key => $value) {
                if (is_numeric($key) AND is_numeric($value)) {
                    $Sql = "INSERT INTO `tbform_has_tbuserquestion` (`tbForm_idtbForm`, `tbUserQuestion_idtbUserQuestion`, `tbForm_has_tbUserQuestionAnswer`) VALUES ('" . $_SESSION['form_id'] . "', '" . $key . "', '" . $value . "')";
                    $rs = mysqli_query($conexao, $Sql) or die("Erro insere formulário");
                    $inserted = 1;
                }
            }

//----------Se questões foram inseridas na tabela de respostas incrementar o status----------------------------------------------------//
            if ($inserted == 1) {	
            	
            	//O valor decimal acrescentado representa a próxima ontologia ou objeto de aprendizagem a ser carregado
             	if(($status < (1+($num_onlogies-1)/10)) AND ((int)$status == 1)) { //Verificar todas as ontologias já foram respondidas 
                	$status = $status + 0.1;
                } elseif (($status < (2+($num_learningobjects-1)/10)) AND ((int)$status == 2)) { //Verificar todas os objetos de aprendizagem já foram respondidos
                	$status = $status + 0.1;
                } else {
             	$status= (int)$status + 1;
               }
               
					//Atualizar o novo valor de status no banco de dados
					$Sql2 = "UPDATE `tbform` SET `tbFormStatus` = '" . $status . "' WHERE `tbform`.`idtbForm` = " . $_SESSION['form_id'] . " AND `tbform`.`tbApplication_idtbApplication` = " . $_SESSION['appic_id'] . " AND `tbform`.`tbUser_idtbUser` = " . $_SESSION['user_id'];
					$rs2 = mysqli_query($conexao, $Sql2) or die("Erro na atualização de estado");
					if ($rs2) {
					  echo "<script> window.location.assign('form.php'); </script>";
					}
            }

//----------Obter os nomes das ontlogias e objetos de aprendizagem----------------------------------------------------------------//
				$item_name = '';
				if ((int)$status == 1){
					$Sql = 'SELECT * FROM `tbontologies` WHERE tbApplication_idtbApplication = '.$applic_id;
					$rs = mysqli_query($conexao, $Sql) or die("Erro insere formulário");
					if (mysqli_num_rows($rs) != 0) {
						$item = (int)(($status -1)*10)+1;
						$i = 0;
						while($row = mysqli_fetch_assoc($rs)) {
							$i=$i + 1;
							if ($i == $item) $item_name = ' - '.$row['tbOntologiesName'];	
						}
					} else {
						$status++;
                $Sql2 = "UPDATE `tbform` SET `tbFormStatus` = '" . $status . "' WHERE `tbform`.`idtbForm` = " . $_SESSION['form_id'] . " AND `tbform`.`tbApplication_idtbApplication` = " . $_SESSION['appic_id'] . " AND `tbform`.`tbUser_idtbUser` = " . $_SESSION['user_id'];
                $rs2 = mysqli_query($conexao, $Sql2) or die("Erro na atualização de estado");
                echo "<script> location.reload(); </script>";
					}
				} 
				
				if ((int)$status == 2) {
					$Sql = 'SELECT * FROM `tblearningobjects` WHERE tbApplication_idtbApplication = '.$applic_id;
					$rs = mysqli_query($conexao, $Sql) or die ("Erro");
					if (mysqli_num_rows($rs) != 0) {
						$item = (int)(($status -2)*10)+1;
						$i = 0;
						while($row = mysqli_fetch_assoc($rs)) {
							$i=$i + 1;
							//echo $i.' '.$learningobject.' '.$status.' '.$row['tbLearningObjectsName'].'<br>';
							if ($i == $item) $item_name = ' - '.$row['tbLearningObjectsName']; 
						}
					} else {
						$status++;
                $Sql2 = "UPDATE `tbform` SET `tbFormStatus` = '" . $status . "' WHERE `tbform`.`idtbForm` = " . $_SESSION['form_id'] . " AND `tbform`.`tbApplication_idtbApplication` = " . $_SESSION['appic_id'] . " AND `tbform`.`tbUser_idtbUser` = " . $_SESSION['user_id'];
                $rs2 = mysqli_query($conexao, $Sql2) or die("Erro na atualização de estado");
                echo "<script> location.reload(); </script>";
					}
				}
             //echo $status;

//----------Se o status atual é maior que  4 (número de artefatos)-------------------------------------------------------------------//
            if ($status > 4) {
					//Atualizar o formulário no banco de dados como completado	           	
					$Sql = "UPDATE `tbform` SET `tbformCompleted` = '1' WHERE `tbform`.`idtbForm` = " . $_SESSION['form_id'] . " AND `tbform`.`tbApplication_idtbApplication` = " . $_SESSION['appic_id'] . " AND `tbform`.`tbUser_idtbUser` = " . $_SESSION['user_id'] . "";
                $rs = mysqli_query($conexao, $Sql) or die("Erro completa formulário");
                echo "<script> window.location.assign('results.php?form=" . $_SESSION['form_id'] . "')</script>"; //ir para resultados
            }
            ?>


            <form action="form.php" class="form-group" method="post">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2><img src="img/form.png" height="113" alt="">Questionário</h2>
                        <?php
                        echo "<h3>Você está avaliando <strong>" . $applic_name . "</strong> como <strong>" . $type_name . "</strong><h3>";
                        ?> 
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">

            <?php
//----------Listar as questões do formulário--------------------------------------------------------//                        
            $order = "ORDER BY tbuserquestion.tbArtifact_idtbArtifact";
            $artifact_change = 1;
            //$factor_change = '';
            $i = 0;

            $Sql = "SELECT DISTINCT idtbUserQuestion, tbuserquestion.tbArtifact_idtbArtifact, tbArtifactName, tbFactor_idtbFactor, tbQuestionText, tbQuestionTextHowTo FROM tbuserquestion INNER JOIN tbquestiontext ON tbuserquestion.idtbUserQuestion = tbquestiontext.tbUserQuestion_idtbUserQuestion INNER JOIN tbobjectives_has_tbuserquestion ON tbuserquestion.idtbUserQuestion = tbobjectives_has_tbuserquestion.tbUserQuestion_idtbUserQuestion INNER JOIN tbartifacttext ON tbuserquestion.tbartifact_idtbArtifact = tbartifacttext.tbArtifact_idtbArtifact WHERE tbuserquestion.tbartifact_idtbArtifact = " . (int)$status . " AND tbartifacttext.tbLanguage_idtbLanguage = 1 AND tbquestiontext.tbLanguage_idtbLanguage = " . $_SESSION['language'] . " AND tbobjectives_has_tbuserquestion.tbUserType_idtbUserType = " . $user_type . " " . $order;
            $rs = mysqli_query($conexao, $Sql) or die ("Erro na listagem de questões");
            if (mysqli_num_rows($rs) == 0) { //Se não há questões para o status atual pular para o próximo
            	echo $Sql;
                $status++;
                $Sql2 = "UPDATE `tbform` SET `tbFormStatus` = '" . $status . "' WHERE `tbform`.`idtbForm` = " . $_SESSION['form_id'] . " AND `tbform`.`tbApplication_idtbApplication` = " . $_SESSION['appic_id'] . " AND `tbform`.`tbUser_idtbUser` = " . $_SESSION['user_id'];
                $rs2 = mysqli_query($conexao, $Sql2) or die("Erro na atualização de estado");
                echo "<script> location.reload(); </script>";
            }

            while ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
                $id = $row["idtbUserQuestion"];
                $artifact = $row["tbArtifact_idtbArtifact"];
                $artifact_name = $row["tbArtifactName"];
                $Factor = $row["tbFactor_idtbFactor"];
                $question = $row["tbQuestionText"];
                $howto = $row["tbQuestionTextHowTo"];
                $i++;
                
                
                if ($artifact_change) {
                   echo '<div class="panel panel-primary"><div class="panel-body">
                   <h2>'.$artifact_name.$item_name.'</h2></div></div>';
                   $artifact_change = 0;
                }
                

              /*if ($factor_change != $Factor) {
                  $Sql2 = "SELECT * FROM `tbfactor` INNER JOIN tbfactortext ON tbfactor.idtbFactor = tbfactortext.tbFactor_idtbFactor WHERE tbfactor.idtbFactor = '" . $Factor . "' AND tbLanguage_idtbLanguage = " . $_SESSION['language'];
                  $rs2 = mysqli_query($conexao, $Sql2) or die("Erro na pesquisa");
                  while ($row2 = mysqli_fetch_array($rs2, MYSQLI_ASSOC)) {
                      echo "<hr><h6>" . $row2['tbFactorName'] . "</h6>";
                      $factor_change = $Factor;
                  }
              }*/
                    
					echo '<div class="panel panel-body">
								<div class="panel-heading" >
									<h4 id="question"> '.$i.' - '. $question .'</h4><a href="#" data-toggle="tooltip" data-placement="right" title="'.$howto.'">Como responder?</a>
								</div>
							<div class="panel-body">';
               echo '<div class="row">';
					
					//echo '<rzslider rz-slider-model="slider_ticks.value" rz-slider-options="slider_ticks.options"></rzslider>';
					echo '<div class="col-md-4" align="right">';
					//echo '<input type="range" name="' . $id . '" min="0" max="5">';
					echo '<input id="slider'.$i.'" name="'.$id.'" type="text" data-slider-ticks="[0, 1, 2, 3, 4, 5]" data-slider-ticks-snap-bounds="2" data-slider-ticks-labels="["0", "1", "2", "3", "4", "5"]"/>
                    <script>$("#slider'.$i.'").slider({
										    ticks: [0, 1, 2, 3, 4, 5],
										    ticks_labels: ["0", "1", "2", "3", "4", "5"],
										    value: 0,
										    step: 1,
										    ticks_snap_bounds: 2
										});</script>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
					/*echo "<div class='radio'>
					<div class='radio-inline'><label><input type='radio' name=" . $id . " value='0' class='optradio' required/>0</label></div>
					<div class='radio-inline'><label><input type='radio' name=" . $id . " value='1' class='optradio' />1</label></div>
					<div class='radio-inline'><label><input type='radio' name=" . $id . " value='2' class='optradio'  />2</label></div>
					<div class='radio-inline'><label><input type='radio' name=" . $id . " value='3' class='optradio' />3</label></div>
					<div class='radio-inline'><label><input type='radio' name=" . $id . " value='4' class='optradio' />4</label></div>
					<div class='radio-inline'><label><input type='radio' name=" . $id . " value='5' class='optradio' />5</label></div>
					</div>";*/
					echo '</div>';                                  
                
            }
            ?>
                        <input class="btn btn-primary btn-block btn-lg" type="submit" value="Seguir" />
                    </div>
                </div>
            </form>
            <?php
            include 'footer.php';
            ?>
        </div>

<?php 
if ((int)$status == 1){
	echo '<script>
            window.onload = function () {
                swal("Ontologias", "Você está avaliando o elemento `'.$item_name.'`");
            }</script>';
}

if ((int)$status == 2){
	echo '<script>
            window.onload = function () {
                swal("Objetos de Aprendizagem", "Você está avaliando o elemento `'.$item_name.'`");
            }</script>';
}

if ((int)$status == 3){
	echo '<script>
            window.onload = function () {
                swal("Interface", "Neste aviso estará um pequeno texto explicando sobre o que será avaliado em geral, destacando que os objetos avaliados dependem do tipo de avaliador que está usando o sistema de avaliação.");
            }</script>';
}

if ((int)$status == 4){
	echo '<script>
            window.onload = function () {
                swal("Software", "Neste aviso estará um pequeno texto explicando sobre o que será avaliado em geral, destacando que os objetos avaliados dependem do tipo de avaliador que está usando o sistema de avaliação.");
            }</script>';
}




?>			
			
			
        <script>
        
        

            $(function(){
    				$('[data-toggle=tooltip]').tooltip();
 				});
        </script>
      
        
    </body>

</html>
