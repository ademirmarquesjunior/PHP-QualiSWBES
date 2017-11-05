<?php
    session_start();

    //Lista de includes
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
        <link rel="stylesheet" href="css/sweetalert.css">
        <script src="js/jquery-3.1.1.min.js"></script>        
        <script src="js/bootstrap.min.js"></script>
        <script src="js/sweetalert.js"></script>
        <link rel="icon" type="image/png" href="favicon.png">
        <title><?php echo $lang['PAGE_TITLE']; ?></title>
    </head>
    <body>
        <div class="container-fluid">
<?php
    include 'header.php';
    include 'navbar.php';

//------------Expulsar o usuário caso ele não seja administrador ou gerente
	if ($_SESSION['user_level'] <2) {
    	echo "<script> window.location.assign('index.php')</script>";	
    }                       
            
//-----------------------------------------------------------------------------------------------------------------------------------//
    $user = $_SESSION['user_id'];


//----------Insere aplicação 
    if (isset($_POST['txt_aplic'])) {
        $applic_name = anti_injection($_POST['txt_aplic']);
        $applic_id = NULL;

        $Sql = "SELECT * FROM tbapplication WHERE tbapplicationname = '" . $applic_name . "' AND tbUser_idtbUser = '" . $user . "'";
        $rs = mysqli_query($conexao, $Sql) or die("Erro busca aplicação");

        if (mysqli_num_rows($rs)) { //Verifica se já existe uma aplicação com o mesmo nome
            $row = mysqli_fetch_assoc($rs);
            $applic_id = $row['idtbApplication'];
            //mostrar mensagem indicando que uma aplicação de mesmo nome já foi cadastrada
        } else {
            $Sql = "INSERT INTO `tbapplication` (`idtbApplication`, `tbApplicationName`, `tbApplicationDescription`, `tbUser_idtbUser`) VALUES (NULL, '" . $applic_name . "', '', '" . $user . "')";
            //echo $Sql;
            $rs = mysqli_query($conexao, $Sql) or die ("Erro na inserção");
            $applic_id = mysqli_insert_id($conexao);
        }
    }

//----------Selecionar a aplicação
    if (isset($_GET['applic_id']) OR isset($_POST['applic_id'])) {
        if (isset($_GET['applic_id'])) $applic_id = anti_injection($_GET['applic_id']);
        if (isset($_POST['applic_id'])) $applic_id = anti_injection($_POST['applic_id']);

        $Sql = "SELECT * FROM tbapplication WHERE idtbApplication = " . $applic_id . " AND tbUser_idtbUser = '" . $user . "'";
			if ($_SESSION['user_level'] >=3) {
				$Sql = "SELECT * FROM tbapplication WHERE idtbApplication = " . $applic_id;
			}                
        
        $rs = mysqli_query($conexao, $Sql);
        if (mysqli_num_rows($rs) == 0) {
            echo "<script> window.location.assign('index3.php')</script>";
        } else {
            $row = mysqli_fetch_assoc($rs);
            $applic_name = $row['tbApplicationName'];
        }
    }

//----------Enviar o usuário para a página inicial se o nome da aplicação não for recuperado
    if (!(isset($applic_name))) {
        echo "<script> window.location.assign('index3.php')</script>";
    }  
    

//----------incluir usuário na lista de formulários
	if (isset($_POST['submitAddUser'])) {
		//print_r($_POST);
		$user_type = anti_injection($_POST['sel_user_type']);
		$user_id = anti_injection($_POST['sel_user']);
		
		if ($user_id == '' OR $user_id == '') echo "<script> window.history.back()</script>";					
		//echo "inclui usuario";

		$Sql = "INSERT INTO `tbform` (`idtbForm`, `tbApplication_idtbApplication`, `tbUser_idtbUser`, `tbFormCompleted`, `tbFormStatus`, `tbUserType_idtbUserType`, `tbFormDate`) VALUES (NULL, '".$applic_id."', '".$user_id."', '0', '0', '".$user_type."', NULL)";
		//echo $Sql;
		$rs = mysqli_query($conexao, $Sql) or die ("Erro na inserção");					
	}
	
//----------Exluir usuário da lista de formulários
	if (isset($_POST['submitDelUser'])) {
        $form_id = anti_injection($_POST['form_id']);
        
        apagarQuestoesUsuario($form_id);
        apagarFormUsuario ($form_id);
	}				

//----------Excluir questões respondidas
	if (isset($_POST['submitDelForm'])) {
        $form_id = anti_injection($_POST['form_id']);
        
        apagarQuestoesUsuario($form_id, $user);
	}
	
//----------Excluir Objetos de Aprendizagem
	if (isset($_POST['submitDelLearningObject'])) {
        $applic_id = anti_injection($_POST['applic_id']);
        $learning_object = anti_injection($_POST['LearningObject_id']);
                        
        $Sql = "DELETE FROM `tblearningobjects` WHERE `tblearningobjects`.`idLearningObjects` = ".$learning_object." AND `tblearningobjects`.`tbApplication_idtbApplication` = ".$applic_id;
        $rs = mysqli_query($conexao, $Sql) or die ("Erro ao apagar Objeto de Aprendizagem");
        
        $Sql = 'DELETE FROM `tbform_has_tbuserquestion` USING tbform_has_tbuserquestion INNER JOIN `tbform` WHERE tbform.idtbForm = tbform_has_tbuserquestion.tbForm_idtbForm AND `tbform`.`tbapplication_idtbapplication` = '.$applic_id;
		$rs = mysqli_query($conexao, $Sql) or die ('Erro ao apagar');
		
		$Sql = "UPDATE `tbform` SET `tbFormCompleted` = '0',`tbFormStatus` = '0' WHERE `tbform`.`tbApplication_idtbApplication` =".$applic_id;
		$rs = mysqli_query($conexao, $Sql) or die ('Erro ao atualizar');
	}				

//----------Excluir Ontologias
	if (isset($_POST['submitDelOntology'])) {
        $applic_id = anti_injection($_POST['applic_id']);
        $ontology = anti_injection($_POST['ontology_id']);
        
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
		$learning_object = anti_injection($_POST['learningobject_text']);

		$Sql = "INSERT INTO `tblearningobjects` (`idLearningObjects`, `tbLearningObjectsName`, `tbLearningObjectsDesc`, `tbApplication_idtbApplication`) VALUES (NULL, '".$learning_object."', NULL, '".$applic_id."')";
		$rs = mysqli_query($conexao, $Sql) or die ("Erro na inserção");
	}
	
	
//----------Inserir ontologias
	if (isset($_POST['submitOntology'])) {
		if ($_POST['ontology_text'] == '') echo "<script> window.history.back()</script>"; 
		$ontology = anti_injection($_POST['ontology_text']);
		
		$Sql = "INSERT INTO `tbontologies` (`idOntologies`, `tbOntologiesName`, `tbOntologiesText`, `tbApplication_idtbApplication`) VALUES (NULL, '".$ontology."', NULL, '".$applic_id."')";
		$rs = mysqli_query($conexao, $Sql) or die ("Erro na inserção");
	}
	
//----------Notificar usuário
	if (isset($_POST['submitNotifyUser'])) {
		 $applic_id = anti_injection($_POST['applic_id']);
         $form_id = anti_injection($_POST['form_id']);

        $Sql = "SELECT * FROM `tbform` INNER JOIN tbuser ON tbform.tbUser_idtbUser = tbuser.idtbUser INNER JOIN tbusertypetext ON tbform.tbUserType_idtbUserType = tbusertypetext.tbUserType_idtbUserType WHERE tbusertypetext.tbLanguage_idtbLanguage = 1 AND tbform.tbApplication_idtbApplication =" . $applic_id;
        $rs = mysqli_query($conexao, $Sql);
        while ($row = mysqli_fetch_assoc($rs)) {
           	$email = $row['tbUserEmail'];
           	$user = $row['tbUserName'];
        }
   
        notifyUser($email,$user,NULL);
   		
        echo "<script language='javascript' type='text/javascript'>
    					swal({   title: '',   text: 'Email enviado com sucesso',    type: 'success'  });
    		</script>";   
	}
	
?>

            <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h1><img src="img/result.png" height="80" alt=""><strong><?php echo $applic_name; ?></strong></h1>
                                </div>
                                <div class="col-md-4">    
                                    <div style="text-align: right;"><h1><a class="btn btn-info btn-md " <?php echo 'href="results.php?applic='.$applic_id.'"'; ?> target="_blank" data-toggle="tooltip" data-placement="bottom" > <span class="glyphicon glyphicon-zoom-in"></span> <?php echo $lang['EVALUATIONEDITOR_RESULTS']; ?></a></h1></div>
                                </div>
                            </div>       
                        </div>
                    </div>
                </div>
            </div>


<?php

//----------Hub de opções para exibição do resumo de avaliações
    echo '<div class="panel panel-default">
					<div class="panel-body">
                    <h2>'.$lang['EVALUATIONEDITOR_USERS'].'</h2>';

    $Sql = "SELECT * FROM `tbform` INNER JOIN tbuser ON tbform.tbUser_idtbUser = tbuser.idtbUser INNER JOIN tbusertypetext ON tbform.tbUserType_idtbUserType = tbusertypetext.tbUserType_idtbUserType WHERE tbusertypetext.tbLanguage_idtbLanguage = ".$_SESSION['language']." AND tbform.tbApplication_idtbApplication = " . $applic_id;
    $rs = mysqli_query($conexao, $Sql);
    if (mysqli_num_rows($rs) == 0) {
        echo '<p>'.$lang['EVALUATIONEDITOR_NOUSERS'].'</p>';
    } else {
    	echo '<table class="table table-bordered table-condensed table-hover">';
        while ($row = mysqli_fetch_assoc($rs)) {
            echo '<form method="post" action="evaluationeditor.php">
            <tr><td>
				<strong>'.$row['tbUserName'].'</strong> - [<strong>'.$row['tbUserTypeDesc'].'</strong>]';
				if ($row['tbFormCompleted'] == '1') { echo ' - '.$lang['EVALUATIONEDITOR_FINISHED']; }
				echo '</td><td>
				<input name="applic_id" value="'.$applic_id.'" size="12" type="text" hidden/>
				<input name="form_id" value="'.$row['idtbForm'].'" size="12" type="text" hidden/>
				<div style="text-align: right;"><button type="submit" class="btn btn-danger btn-sm" name="submitDelUser" data-toggle="tooltip" data-placement="bottom" title="'.$lang['EVALUATIONEDITOR_BTN_DEL_MESSAGE'].'"> <span class="glyphicon glyphicon-remove"></span> '.$lang['EVALUATIONEDITOR_BTN_DEL'].'</button>
				<button type="submit" class="btn btn-default btn-sm ';
				if ($row['tbFormCompleted'] == '1') { echo ' disabled'; }
				echo '" name="submitNotifyUser" data-toggle="tooltip" data-placement="bottom" title="'.$lang['EVALUATIONEDITOR_BTN_NOTIFY_MESSAGE'].'"> <span class="glyphicon glyphicon-info-sign"></span> '.$lang['EVALUATIONEDITOR_BTN_NOTIFY'].'</button>
				<button type="submit" class="btn btn-warning btn-sm" name="submitDelForm" data-toggle="tooltip" data-placement="bottom" title="'.$lang['EVALUATIONEDITOR_BTN_RESTART_MESSAGE'].'"> <span class="glyphicon glyphicon-refresh"></span> '.$lang['EVALUATIONEDITOR_BTN_RESTART'].'</button> 
				<a class="btn btn-info btn-sm';
				if ($row['tbFormCompleted'] == '0') { echo ' disabled'; }
				echo '" href="results.php?form='.$row['idtbForm'].'" target="_blank" data-toggle="tooltip" data-placement="bottom" title="'.$lang['EVALUATIONEDITOR_BTN_OPEN_MESSAGE'].'"> <span class="glyphicon glyphicon-zoom-in"></span> '.$lang['EVALUATIONEDITOR_BTN_OPEN'].' </a>
				</div></td></tr></form>';
        }
        echo '</table>';
    }

		//echo '<br><h3>Inclua usuários:</h3>';
    echo '<form method="post" action="evaluationeditor.php" class="form form-inline">
            <input name="applic_id" value="'.$applic_id.'" size="12" type="text" hidden/>
            <select name="sel_user" id="user" class="form-control">
                <option value="" disabled selected hidden>'.$lang['EVALUATIONEDITOR_USERS'].'</option>';
            $Sql = "SELECT * FROM `tbuser`";
            $rs = mysqli_query($conexao, $Sql) or die("Erro na pesquisa");
            while ($row = mysqli_fetch_assoc($rs)) {
                echo "<option value=" . $row['idtbUser'] . ">" . $row['tbUserName'] . "</option>";
            }
            echo '</select>';

    echo '  <select name="sel_user_type" id="user_type" class="form-control">
                <option value="" disabled selected hidden>'.$lang['EVALUATIONEDITOR_USERS_TYPE'].'</option>';
                $Sql = "SELECT * FROM `tbusertype` INNER JOIN tbusertypetext ON tbusertype.idtbUserType = tbusertypetext.tbUserType_idtbUserType WHERE tblanguage_idtbLanguage = ".$_SESSION['language'];
                $rs = mysqli_query($conexao, $Sql) or die("Erro na pesquisa");
                while ($row = mysqli_fetch_assoc($rs)) {
                    echo "<option value=" . $row['idtbUserType'] . ">" . $row['tbUserTypeDesc'] . "</option>";
                }
            echo '</select>';

    echo '  <button type="submit" name="submitAddUser" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> Adicionar</button>
         </form>
        '.$lang['EVALUATIONEDITOR_NEW_USERS'].'

        <hr><h2>'.$lang['EVALUATIONEDITOR_LO'].'</h2>';			
    
    $Sql = "SELECT * FROM `tblearningobjects` WHERE tbApplication_idtbApplication = " . $applic_id;
    $rs = mysqli_query($conexao, $Sql);
    if (mysqli_num_rows($rs) == 0) {
        echo '<p>'.$lang['EVALUATIONEDITOR_LO_NONE'].'</p>';
    } else {
    	echo '<table class="table table-bordered table-condensed table-hover">';
        while ($row = mysqli_fetch_assoc($rs)) {
            echo '<form method="post" action="evaluationeditor.php">
            <input name="applic_id" value="'.$applic_id.'" size="12" type="text" hidden/>
            <input name="LearningObject_id" value="'.$row['idLearningObjects'].'" size="12" type="text" hidden/>
            <tr><td>
            <strong>'.$row['tbLearningObjectsName'].'</strong></td>
            <td><div style="text-align: right;"><button type="submit" class="btn btn-danger btn-sm" name="submitDelLearningObject" data-toggle="tooltip" data-placement="bottom" title="'.$lang['EVALUATIONEDITOR_LO_DEL_MESSAGE'].'"> <span class="glyphicon glyphicon-remove"></span> '.$lang['EVALUATIONEDITOR_DEL'].' </button></div></td></tr>
            </form>';
        }
        echo '</table>';
    }

    echo '<form method="post" action="evaluationeditor.php" name="formLearningObject" class="form form-inline">
            <input name="applic_id" value="'.$applic_id.'" size="12" type="text" hidden/>
            <input name="learningobject_text" value="" size="35" type="text" placeholder="'.$lang['EVALUATIONEDITOR_LO_ADD'].'" class="form-control" required/>
            <button type="submit" name="submitLearningObject" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> '.$lang['EVALUATIONEDITOR_ADD'].'</button>
        </form>
        <hr><h2>'.$lang['EVALUATIONEDITOR_ONTOLOGY'].'</h2>';
    
    $Sql = "SELECT * FROM `tbontologies` WHERE tbApplication_idtbApplication = " . $applic_id;
    $rs = mysqli_query($conexao, $Sql);
    if (mysqli_num_rows($rs) == 0) {
        echo '<p>'.$lang['EVALUATIONEDITOR_ONTOLOGY_NONE'].'</p>';
    } else {
    	echo '<table class="table table-bordered table-condensed table-hover">';
        while ($row = mysqli_fetch_assoc($rs)) {
            echo '<form method="post" action="evaluationeditor.php">
                    <input name="applic_id" value="'.$applic_id.'" size="12" type="text" hidden/>
                    <input name="ontology_id" value="'.$row['idOntologies'].'" size="12" type="text" hidden/>
                    <tr><td>
    		        <strong>'.$row['tbOntologiesName'].'</strong></td>
                    <td><div style="text-align: right;"><button type="submit" class="btn btn-danger btn-sm" name="submitDelOntology" data-toggle="tooltip" data-placement="bottom" title="'.$lang['EVALUATIONEDITOR_ONTOLOGY_DEL_MESSAGE'].'"> <span class="glyphicon glyphicon-remove"></span> '.$lang['EVALUATIONEDITOR_DEL'].'</button>
                </form>
            </div></td></tr>';
        }
        echo '</table>';
    }

    echo '<form method="post" action="evaluationeditor.php" name="formOntology" class="form form-inline">
            <input name="applic_id" value="'.$applic_id.'" size="12" type="text" hidden/>
            <input name="ontology_text" value="" size="35" type="text" placeholder="'.$lang['EVALUATIONEDITOR_ONTOLOGY_ADD'].'" class="form-control" required/>
            <button type="submit" name="submitOntology" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> '.$lang['EVALUATIONEDITOR_ADD'].'</button>
        </form>
        </div>
    </div><hr>';
    
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
