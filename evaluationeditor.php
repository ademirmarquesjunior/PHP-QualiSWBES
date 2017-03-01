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
        <link rel="stylesheet" type="text/css" href="slider/rzslider.css"/>
        <link rel="stylesheet" href="css/sweetalert.css">
        <script src="js/jquery-3.1.1.min.js"></script>        
        <script src="js/bootstrap.min.js"></script>
        <script src="js/sweetalert.js"></script>
<!-- 			<script src="slider/angular.min.js"></script>
        <script src="slider/rzslider.min.js"></script> -->
        <link rel="icon" type="image/png" href="favicon.png">
        <title>Avalia SEWebS</title>
    </head>

    <body>

        <div class="container-fluid">
            <?php
            include 'header.php';
            include 'navbar.php';
            ?>


            <?php
            
				if ($_SESSION['user_level'] <2) {
            	echo "<script> window.location.assign('index.php')</script>";	
            }            
            
//----------Funções

//----------Apagar questões
				function eraseQuestions ($form_id, $applic_id) {
					include "conecta.php";
					$Sql = 'DELETE FROM `tbform_has_tbuserquestion` WHERE `tbform_has_tbuserquestion`.`tbForm_idtbForm` = '.$form_id;
					$rs = mysqli_query($conexao, $Sql) or die ('Erro ao apagar');
					
					$Sql = "UPDATE `tbform` SET `tbFormCompleted` = '0',`tbFormStatus` = '0' WHERE `tbform`.`idtbForm` = ".$form_id;
					$rs = mysqli_query($conexao, $Sql) or die ('Erro ao atualizar');
				}
				
//----------Apagar formulário				
				function eraseForm ($form_id) {
					include "conecta.php";
					
					$Sql = 'DELETE FROM `tbform` WHERE `tbform`.`idtbForm` = '.$form_id;
					$rs = mysqli_query($conexao, $Sql);
				}            
            
//-----------------------------------------------------------------------------------------------------------------------------------//
            $user = $_SESSION['user_id'];
            $level = $_SESSION['user_level'];


//----------Insere aplicação 
            if (isset($_POST['txt_aplic'])) {
                $applic_name = $_POST['txt_aplic'];
                $applic_text = $_POST['txt_aplicdesc'];
                $applic_id = NULL;

                $Sql = "SELECT * FROM tbapplication WHERE tbapplicationname = '" . $applic_name . "' AND tbUser_idtbUser = '" . $user . "'";
                $rs = mysqli_query($conexao, $Sql) or die("Erro busca aplicação");

                if (mysqli_num_rows($rs)) { //Verifica se já existe uma aplicação com o mesmo nome
                    $row = mysqli_fetch_assoc($rs);
                    $applic_id = $row['idtbApplication'];
                    //mostrar mensagem indicando que uma aplicação de mesmo nome já foi cadastrada
                } else {
                    $Sql = "INSERT INTO `tbapplication` (`idtbApplication`, `tbApplicationName`, `tbApplicationDescription`, `tbUser_idtbUser`) VALUES (NULL, '" . $applic_name . "', '" . $applic_text . "', '" . $user . "')";
                    //echo $Sql;
                    $rs = mysqli_query($conexao, $Sql) or die ("Erro na inserção");
                    $applic_id = mysqli_insert_id($conexao);
                    echo '<div class="panel panel-default">';
                    echo '<div class="panel-heading">';
                    echo '<div class="panel panel-default">
               				<div class="panel-body">';
							echo "<h1><img src='img/result.png' height='113' alt=''><strong>".$applic_name."</strong></h1>";
							echo "</div></div>";
                    echo '<p>' . $applic_text . '</p></div></div>';
                }
            }

//----------Selecionar a aplicação
            if (isset($_GET['applic_id']) OR isset($_POST['applic_id'])) {
                if (isset($_GET['applic_id'])) $applic_id = $_GET['applic_id'];
                if (isset($_POST['applic_id'])) $applic_id = $_POST['applic_id'];

                $Sql = "SELECT * FROM tbapplication WHERE idtbApplication = " . $applic_id . " AND tbUser_idtbUser = '" . $user . "'";
                $rs = mysqli_query($conexao, $Sql);
                if (mysqli_num_rows($rs) == 0) {
                    echo "<script> window.location.assign('index3.php')</script>";
                } else {
                    $row = mysqli_fetch_assoc($rs);
                    $applic_name = $row['tbApplicationName'];
                    $applic_text = $row['tbApplicationDescription'];
                    echo '<div class="panel panel-default">';
                    echo '<div class="panel-heading">';
                    echo '<div class="panel panel-default">
               				<div class="panel-body">';
							echo "<h1><img src='img/result.png' height='113' alt=''><strong>".$applic_name."</strong></h1>";
							echo "</div></div>";
                    echo '<p>' . $applic_text . '</p></div></div>';
                }
            } else {
					echo "<script> window.location.assign('index3.php')</script>";            
            }
            
	
//----------incluir usuário na lista de formulários
				if (isset($_POST['submitAddUser'])) {
					//print_r($_POST);
					$user_type = $_POST['sel_user_type'];
					$user_id = $_POST['sel_user'];
					
					if ($user_id == '' OR $user_id == '') echo "<script> window.history.back()</script>";					
					//echo "inclui usuario";
					
					//$Sql = "INSERT INTO `tbapplication_has_tbuser` (`tbApplication_idtbApplication`, `tbUser_idtbUser`, `tbUserType_idtbUserType`) VALUES ('".$applic_id."', '".$user_id."', '".$user_type."')";
					$Sql = "INSERT INTO `tbform` (`idtbForm`, `tbApplication_idtbApplication`, `tbUser_idtbUser`, `tbFormCompleted`, `tbFormStatus`, `tbUserType_idtbUserType`, `tbFormDate`) VALUES (NULL, '".$applic_id."', '".$user_id."', '0', '0', '".$user_type."', NULL)";
					//echo $Sql;
					$rs = mysqli_query($conexao, $Sql) or die ("Erro na inserção");					
					
				}
				
//----------Exluir usuário da lista de formulários
				if (isset($_POST['submitDelUser'])) {
                $applic_id = $_POST['applic_id'];
                $form_id = $_POST['form_id'];
                
                eraseQuestions($form_id, $user);
                eraseForm ($form_id, $user, $applic_id);
				}				

//----------Excluir questões respondidas
				if (isset($_POST['submitDelForm'])) {
                $applic_id = $_POST['applic_id'];
                $form_id = $_POST['form_id'];
                
                eraseQuestions($form_id, $user);
                
				}
				
//----------Excluir Objetos de Aprendizagem
				if (isset($_POST['submitDelLearningObject'])) {
                $applic_id = $_POST['applic_id'];
                $learning_object = $_POST['LearningObject_id'];
                                
                $Sql = "DELETE FROM `tblearningobjects` WHERE `tblearningobjects`.`idLearningObjects` = ".$learning_object." AND `tblearningobjects`.`tbApplication_idtbApplication` = ".$applic_id;
                $rs = mysqli_query($conexao, $Sql) or die ("Erro ao apagar Objeto de Aprendizagem");
                
                $Sql = 'DELETE FROM `tbform_has_tbuserquestion` USING tbform_has_tbuserquestion INNER JOIN `tbform` WHERE tbform.idtbForm = tbform_has_tbuserquestion.tbForm_idtbForm AND `tbform`.`tbapplication_idtbapplication` = '.$applic_id;
					$rs = mysqli_query($conexao, $Sql) or die ('Erro ao apagar');
					
					$Sql = "UPDATE `tbform` SET `tbFormCompleted` = '0',`tbFormStatus` = '0' WHERE `tbform`.`tbApplication_idtbApplication` =".$applic_id;
					$rs = mysqli_query($conexao, $Sql) or die ('Erro ao atualizar');
                
				}				
	
//----------Excluir Ontologias
				if (isset($_POST['submitDelOntology'])) {
                $applic_id = $_POST['applic_id'];
                $ontology = $_POST['ontology_id'];
                
                $Sql = "DELETE FROM `tbontologies` WHERE `tbontologies`.`idOntologies` = ".$ontology." AND `tbontologies`.`tbApplication_idtbApplication` = ".$applic_id;
                $rs = mysqli_query($conexao, $Sql) or die ("Erro ao apagar ontologia");
                
                $Sql = 'DELETE FROM `tbform_has_tbuserquestion` USING tbform_has_tbuserquestion INNER JOIN `tbform` WHERE tbform.idtbForm = tbform_has_tbuserquestion.tbForm_idtbForm AND `tbform`.`tbapplication_idtbapplication` = '.$applic_id;
					$rs = mysqli_query($conexao, $Sql) or die ('Erro ao apagar');
					
					$Sql = "UPDATE `tbform` SET `tbFormCompleted` = '0',`tbFormStatus` = '0' WHERE `tbform`.`tbApplication_idtbApplication` =".$applic_id;
					$rs = mysqli_query($conexao, $Sql) or die ('Erro ao atualizar');
				}	
	
//----------Inserir objeto de aprendizagem
				if (isset($_POST['submitLearningObject'])) {
					if ($_POST['learningobject_text'] == '') echo "<script> window.history.back()</script>"; 
					$learning_object = $_POST['learningobject_text'];
					
				
					$Sql = "INSERT INTO `tblearningobjects` (`idLearningObjects`, `tbLearningObjectsName`, `tbLearningObjectsDesc`, `tbApplication_idtbApplication`) VALUES (NULL, '".$learning_object."', NULL, '".$applic_id."')";
					$rs = mysqli_query($conexao, $Sql) or die ("Erro na inserção");
				}
				
				
//----------Inserir ontologias
				if (isset($_POST['submitOntology'])) {
					if ($_POST['ontology_text'] == '') echo "<script> window.history.back()</script>"; 
					$ontology = $_POST['ontology_text'];
					
					$Sql = "INSERT INTO `tbontologies` (`idOntologies`, `tbOntologiesName`, `tbOntologiesText`, `tbApplication_idtbApplication`) VALUES (NULL, '".$ontology."', NULL, '".$applic_id."')";
					$rs = mysqli_query($conexao, $Sql) or die ("Erro na inserção");
				}
				
//----------Notificar usuário
				if (isset($_POST['submitNotifyUser'])) {
					 $applic_id = $_POST['applic_id'];
                $form_id = $_POST['form_id'];
                
                $Sql = "SELECT * FROM `tbform` INNER JOIN tbuser ON tbform.tbUser_idtbUser = tbuser.idtbUser INNER JOIN tbusertypetext ON tbform.tbUserType_idtbUserType = tbusertypetext.tbUserType_idtbUserType WHERE tbusertypetext.tbLanguage_idtbLanguage = 1 AND tbform.tbApplication_idtbApplication =" . $applic_id;
	            $rs = mysqli_query($conexao, $Sql);
               while ($row = mysqli_fetch_assoc($rs)) {
               	$email = $row['tbUserEmail'];
               	$user = $row['tbUserName'];
               	
               }
               $to = $email;
					$subject = "[AvaliaQASWebE] Usuário cadastrado";
					$txt = "<html><head><title>HTML email</title></head>
						<body>
						<h3>Olá ".$user."</h3>
						<p>Você foi indicado para avaliar um sistema educacional utilizando o sistema de avaliação de sistemas educacionais baseados em Web Semântica AvaliaQASWebE disponível  <a href='http://avaliasewebs.caed-lab.com/index.php' target='_blank'>aqui</a>.</p>
						<p>Acesse a área principal para acessar o formulário de avaliação.</p>
						</body>
						</html>";
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
					$headers .= 'From: <no-reply@caed-lab.com>' . "\r\n";
					
					mail($to,$subject,$txt,$headers);
                echo "<script language='javascript' type='text/javascript'>
								swal({   title: '',   text: 'Email enviado com sucesso',    type: 'success'  });
							</script>";
                
				}
				           
//----------Hub de opções para exibição do resumo de avaliações
            echo "<div class='panel panel-default'>
							<div class='panel-heading'><h1>Visualize resumo de avaliação</h1></div>
							<div class='panel-body'>";
				echo '<a class="btn btn-info btn-md " href="results.php?applic='.$applic_id.'" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Visialize o relátório em uma nova página."> <span class="glyphicon glyphicon-zoom-in"></span> Abrir </a>';
				
											
							            
            echo "</div></div>";
            
            
//----------Formulário para inclusão de usuários
            echo "<div class='panel panel-default'>
							<div class='panel-heading'><h1>Gerencie avaliadores</h1></div>
							<div class='panel-body'>";
            //echo "<p>Excluir usuários também exclui avaliações já terminadas por esse usuário!</p>";

            $Sql = "SELECT * FROM `tbform` INNER JOIN tbuser ON tbform.tbUser_idtbUser = tbuser.idtbUser INNER JOIN tbusertypetext ON tbform.tbUserType_idtbUserType = tbusertypetext.tbUserType_idtbUserType WHERE tbusertypetext.tbLanguage_idtbLanguage = 1 AND tbform.tbApplication_idtbApplication =" . $applic_id;
            $rs = mysqli_query($conexao, $Sql);
            if (mysqli_num_rows($rs) == 0) {
                echo "<p>Não há usuários cadastrados</p>";
            } else {
                while ($row = mysqli_fetch_assoc($rs)) {
                    echo '<form method="post" action="evaluationeditor.php">
							<strong>'.$row['tbUserName'].'</strong> como <strong>'.$row['tbUserTypeDesc'].'</strong>';
							if ($row['tbFormCompleted'] == '1') { echo ' - Terminado'; }
							echo '<br>
							<input name="applic_id" value="'.$applic_id.'" size="12" type="text" hidden/>
							<input name="form_id" value="'.$row['idtbForm'].'" size="12" type="text" hidden/>
							<button type="submit" class="btn btn-danger btn-sm" name="submitDelUser" data-toggle="tooltip" data-placement="bottom" title="Excluir avaliador para a avaliação. Exclui avaliação já terminada!"> <span class="glyphicon glyphicon-remove"></span> </button>
							<button type="submit" class="btn btn-default btn-sm ';
							if ($row['tbFormCompleted'] == '1') { echo ' disabled'; }
							echo '" name="submitNotifyUser" data-toggle="tooltip" data-placement="bottom" title="Notifique o usuário de avaliações pendentes!"> <span class="glyphicon glyphicon-info-sign"></span></button>
							<button type="submit" class="btn btn-warning btn-sm" name="submitDelForm" data-toggle="tooltip" data-placement="bottom" title="Reinicie todas as questões respondidas pelo usuário!"> <span class="glyphicon glyphicon-refresh"></span></button> 
							<a class="btn btn-info btn-sm';
							if ($row['tbFormCompleted'] == '0') { echo ' disabled'; }
							echo '" href="results.php?form='.$row['idtbForm'].'" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Visialize o resumo do formulário já terminado em uma nova página."> <span class="glyphicon glyphicon-zoom-in"></span> </a>';							
							echo '</form><hr>';
                }
            }

				//echo '<br><h3>Inclua usuários:</h3>';
            echo '<form method="post" action="evaluationeditor.php" class="form form-inline">';
            echo '<input name="applic_id" value="'.$applic_id.'" size="12" type="text" hidden/>';
            
            echo '<select name="sel_user" id="user" class="form-control">';
            echo '<option value="" disabled selected hidden>Usuário</option>';
            $Sql = "SELECT * FROM `tbuser`";
            $rs = mysqli_query($conexao, $Sql) or die("Erro na pesquisa");
            while ($row = mysqli_fetch_assoc($rs)) {
                echo "<option value=" . $row['idtbUser'] . ">" . $row['tbUserName'] . "</option>";
            }
            echo '</select>';

            echo '<select name="sel_user_type" id="user_type" class="form-control">';
            echo '<option value="" disabled selected hidden>Tipo de avaliador</option>';
            $Sql = "SELECT * FROM `tbusertype` INNER JOIN tbusertypetext ON tbusertype.idtbUserType = tbusertypetext.tbUserType_idtbUserType WHERE tblanguage_idtbLanguage = 1";
            $rs = mysqli_query($conexao, $Sql) or die("Erro na pesquisa");
            while ($row = mysqli_fetch_assoc($rs)) {
                echo "<option value=" . $row['idtbUserType'] . ">" . $row['tbUserTypeDesc'] . "</option>";
            }
            echo '</select>';


            //echo '<button type="submit" name="submitDelUser" ><span class="glyphicon glyphicon-minus-sign"></span> Excluir</button>';
            echo '<button type="submit" name="submitAddUser" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> Adicionar</button></form>';
            echo "O usuário que você procura ainda não foi cadastrado? <a href=''>Cadastre um novo usuário</a>";
            
            echo "</div></div>";
            
//----------Formulário para inclusão de objetos de aprendizagem
				echo "<div class='panel panel-default'>
							<div class='panel-heading' data-toggle='tooltip' data-placement='bottom' title='O que é um Objeto de Aprendizagem?'><h1>Objetos de Aprendizagem</h1></div>
							<div class='panel-body'>";
            
            $Sql = "SELECT * FROM `tblearningobjects` WHERE tbApplication_idtbApplication = " . $applic_id;
            $rs = mysqli_query($conexao, $Sql);
            if (mysqli_num_rows($rs) == 0) {
                echo "<p>Não há Objetos de aprendizagem cadastrados</p>";
            } else {
                while ($row = mysqli_fetch_assoc($rs)) {
                    echo '<form method="post" action="evaluationeditor.php">
                    <input name="applic_id" value="'.$applic_id.'" size="12" type="text" hidden/>
                    <input name="LearningObject_id" value="'.$row['idLearningObjects'].'" size="12" type="text" hidden/>
                    <strong>'.$row['tbLearningObjectsName'].'</strong>
                    <button type="submit" class="btn btn-danger btn-sm" name="submitDelLearningObject" data-toggle="tooltip" data-placement="bottom" title="Excluir Objetos de Aprendizagem exclui avaliações já terminadas anteriormente!"> <span class="glyphicon glyphicon-remove"></span> </button>
                    </form>';
                }
            }
            
           
            echo '<br><p>Inclua um objeto de aprendizagem:<br>';
            echo '<form method="post" action="evaluationeditor.php" name="formLearningObject" class="form form-inline">';
            echo '<input name="applic_id" value="'.$applic_id.'" size="12" type="text" hidden/>';
            echo '<input name="learningobject_text" value="" size="35" type="text" class="form-control" required/>';
            
            echo '<button type="submit" name="submitLearningObject" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> Adicionar</button></form></p>';
            
            echo "</div></div>";
            
//----------Formulário para inclusão de ontologias           
            echo "<div class='panel panel-default'>
							<div class='panel-heading' data-toggle='tooltip' data-placement='bottom' title='O que é uma ontologia?'><h1>Ontologias</h1></div>
							<div class='panel-body'>";
            
            $Sql = "SELECT * FROM `tbontologies` WHERE tbApplication_idtbApplication = " . $applic_id;
            $rs = mysqli_query($conexao, $Sql);
            if (mysqli_num_rows($rs) == 0) {
                echo "<p>Não há Ontologias cadastradas</p>";
            } else {
                while ($row = mysqli_fetch_assoc($rs)) {
                    echo '<form method="post" action="evaluationeditor.php">
                    <input name="applic_id" value="'.$applic_id.'" size="12" type="text" hidden/>
                    <input name="ontology_id" value="'.$row['idOntologies'].'" size="12" type="text" hidden/>
                    <strong>'.$row['tbOntologiesName'].'</strong>
                    <button type="submit" class="btn btn-danger btn-sm" name="submitDelOntology" data-toggle="tooltip" data-placement="bottom" title="Excluir Ontologias exclui avaliações já terminadas anteriormente!"> <span class="glyphicon glyphicon-remove"></span> </button></form>';
                }
            }
            
            
            echo '<br><p>Inclua uma ontologia:<br>';
            echo '<form method="post" action="evaluationeditor.php" name="formOntology" class="form form-inline">';
            echo '<input name="applic_id" value="'.$applic_id.'" size="12" type="text" hidden/>';
            echo '<input name="ontology_text" value="" size="35" type="text" class="form-control" required/>';
            echo '<button type="submit" name="submitOntology" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> Adicionar</button></form></p>';
            
            echo "</div></div>";
            
            ?>

            <hr>

<?php
include 'footer.php';
?>
        </div>
        <script>

            $(function(){
    				$('[data-toggle=tooltip]').tooltip();
 				});
        </script>        
        
    </body>
</html>
