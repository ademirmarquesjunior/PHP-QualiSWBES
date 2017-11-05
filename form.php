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
        <meta name="description" content="Abordagem para avaliação da qualidade de sistemas educacionais baseados em Web Semântica, Approach for quality evaluation of educational systems based on Semantic Web">
        <meta name="keywords" content="Web Semântica, educação, qualidade, avaliação, abordagem, Semantic Web, educational, quality, assesment, approach, education, USP, ICMC, SWBES">
        <meta name="author" content="Aparecida Maria Zem Lopes, Ademir Marques Junior, Seiji Isotani">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/sweetalert.css">
        <script src="js/jquery-3.1.1.min.js"></script>        
        <script src="js/bootstrap.min.js"></script>
        <script src="js/bootstrap-slider.min.js"></script>
        <link href="css/bootstrap-slider.min.css" rel="stylesheet">
        <script src="js/sweetalert.js"></script>
        <link rel="icon" type="image/png" href="favicon.png">
        <title><?php echo $lang['PAGE_TITLE']; ?></title>
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

    $row = mysqli_fetch_assoc($rs);
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
            $Sql = "SELECT * FROM `tbquestion` WHERE `tbQuestionId_idtbQuestionId` = '".$key."'";
            $rs = mysqli_query($conexao, $Sql) or die ("Erro listar "); 
            while ($row = mysqli_fetch_assoc($rs)) {                 	
                $Sql2 = "INSERT INTO `tbform_has_tbuserquestion` (`tbForm_idtbForm`, `tbUserQuestion_idtbUserQuestion`, `tbForm_has_tbUserQuestionAnswer`) VALUES ('" . $_SESSION['form_id'] . "', '" . $row['idtbQuestion'] . "', '" . $value . "')";
                $rs2 = mysqli_query($conexao, $Sql2) or die ("Erro insere formulário");
                $inserted = 1;
            }
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
    if ((int)$status == 1){ //Se o status for igual a 1 (Ontologias)
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

    if ((int)$status == 2) { //Se o status for igual a 2 (Objetos de aprendizagem)
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

    //----------Se o status atual é maior que  4 (número de artefatos) atualizar o formulário como completo ---------------------------------------------//
    if ($status > 4) {
        //Atualizar o formulário no banco de dados como completado	           	
        $Sql = "UPDATE `tbform` SET `tbformCompleted` = '1' WHERE `tbform`.`idtbForm` = " . $_SESSION['form_id'] . " AND `tbform`.`tbApplication_idtbApplication` = " . $_SESSION['appic_id'] . " AND `tbform`.`tbUser_idtbUser` = " . $_SESSION['user_id'] . "";
        $rs = mysqli_query($conexao, $Sql) or die("Erro completa formulário");
        echo "<script> swal({title: 'Concluído!',
                text: 'Obrigado por participar.',
                type: 'warning',
                showCancelButton: false,
                confirmButtonClass: 'btn-success',
                confirmButtonText: 'OK',
                closeOnConfirm: true}, function() { window.location.assign('index.php')});
              </script>"; //ir para resultados
        exit();
    }
?>
        <form action="form.php" class="form-group" id="form1" method="post">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2><img src="img/form.png" height="113" alt="">Questionário</h2>
                    <?php echo "<h3>Você está avaliando <strong>" . $applic_name . "</strong> como <strong>" . $type_name . "</strong><h3>"; ?> 
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">

<?php
    //----------Listar as questões do formulário--------------------------------------------------------//                        

    $artifact_label = 1;
    $i = 0;

    $Sql = "SELECT DISTINCT idtbQuestionId, tbQuestionText, tbQuestionTextHowTo, tbquestiontext.tbLanguage_idtbLanguage, tbartifacttext.tbArtifact_idtbArtifact, tbartifacttext.tbArtifactName, tbartifacttext.tbArtifactDesc FROM tbquestionid INNER JOIN tbquestiontext ON tbquestionid.idtbQuestionId = tbquestiontext.tbQuestionId_idtbQuestionId INNER JOIN tbusertype_has_tbuserquestion ON tbquestionid.idtbQuestionId = tbusertype_has_tbuserquestion.tbQuestionId_idtbQuestionId INNER JOIN tbquestion ON tbquestionid.idtbQuestionId = tbquestion.tbQuestionId_idtbQuestionId INNER JOIN tbartifacttext ON tbquestion.tbArtifact_idtbArtifact = tbartifacttext.tbArtifact_idtbArtifact WHERE tbartifacttext.tbLanguage_idtbLanguage = " . $_SESSION['language'] . " AND tbquestiontext.tbLanguage_idtbLanguage = " . $_SESSION['language'] . " AND tbquestion.tbArtifact_idtbArtifact = " . (int)$status . " AND tbusertype_has_tbuserquestion.tbUserType_idtbUserType = ".$user_type;


    $rs = mysqli_query($conexao, $Sql) or die ("Erro na listagem de questões");
    if (mysqli_num_rows($rs) == 0) { //Se não há questões para o status atual pular para o próximo
        $status++;
        $Sql2 = "UPDATE `tbform` SET `tbFormStatus` = '" . $status . "' WHERE `tbform`.`idtbForm` = " . $_SESSION['form_id'] . " AND `tbform`.`tbApplication_idtbApplication` = " . $_SESSION['appic_id'] . " AND `tbform`.`tbUser_idtbUser` = " . $_SESSION['user_id'];
        $rs2 = mysqli_query($conexao, $Sql2) or die("Erro na atualização de estado");
        echo "<script> location.reload(); </script>";
    }

    while ($row = mysqli_fetch_assoc($rs)) {
        $id = $row["idtbQuestionId"];
        $artifact = $row["tbArtifact_idtbArtifact"];
        $artifact_name = $row["tbArtifactName"];
        $artifact_text = $row["tbArtifactDesc"];
        $question = $row["tbQuestionText"];
        $howto = $row["tbQuestionTextHowTo"];
        $i++;

        if ($artifact_label) {
            echo '<div class="panel panel-primary" data-toggle="tooltip" data-placement="top" title="'.$artifact_text.'">
                    <div class="panel-body"><h2>'.$artifact_name.$item_name.'</h2></div>
                  </div>';
            $artifact_label = 0;
        }

        echo '<div class="panel panel-body">
                <div class="panel-heading" >
                    <h4 id="question"> '.$i.' - '. $question .'</h4>
                    <a href="#" data-toggle="tooltip" data-placement="right" title="'.$howto.'">Como responder?</a> 
                    <input type="checkbox" id="check'.$i.'" name="check'.$i.'" value="Car" onclick="return disable('.$i.')"> Não se aplica		
                </div>
                <div class="panel-body" id="div'.$i.'">
                    <div class="row">
                        <div class="col-md-4" align="left" >
                            não atende   <input id="slider'.$i.'" name="'.$id.'" type="text" data-slider-ticks="[0, 1, 2, 3, 4, 5]" data-slider-ticks-snap-bounds="2" data-slider-ticks-labels="["0", "1", "2", "3", "4", "5"]"/>    atende
                            <script>$("#slider'.$i.'").slider({
                                ticks: [0, 1, 2, 3, 4, 5],
                                ticks_labels: ["0","1", "2", "3", "4", "5"],
                                value: 0,
                                range: false,
                                step: 1,
                                ticks_snap_bounds: 2});
                            </script>
                        </div>
                    </div>
                </div>
              </div>';                                  

    }
    echo '<input class="btn btn-primary btn-block btn-lg" type="submit" value="'.$lang['FORM_BUTTON'].'" />';
?>

                </div>
            </div>
            </form>
<?php
include 'footer.php';
?>
        </div>

<?php 
    if ((int)$status == 1){ //Exibir painel com informações ao carregar a página
    	echo '<script>
        window.onload = function () {
            swal("'.$artifact_name.'", "'.$lang['FORM_ONTOLOGY_TEXT'].$item_name.'");
        }</script>';
    }
    if ((int)$status == 2){ //Exibir painel com informações ao carregar a página
    	echo '<script>
        window.onload = function () {
            swal("'.$artifact_name.'", "'.$lang['FORM_LEARNINGOBJECT_TEXT'].$item_name.'`");
        }</script>';
    }
    if ((int)$status == 3){ //Exibir painel com informações ao carregar a página
    	echo '<script>
        window.onload = function () {
            swal("'.$artifact_name.'", "'.$lang['FORM_INTERFACE_TEXT'].'");
        }</script>';
    }
    if ((int)$status == 4){ //Exibir painel com informações ao carregar a página
    	echo '<script>
        window.onload = function () {
            swal("'.$artifact_name.'", "'.$lang['FORM_SOFTWARE_TEXT'].'");
        }</script>';
    }
?>			
        <script>
            $(function() { //Carregar as mensagens de ajuda 
                $('[data-toggle=tooltip]').tooltip();
            });

            function disable(id) { //Disabilitar a questão no formulário
                $("#div"+id).empty();
                $("#check"+id).prop("disabled", true);
            } 				
        </script>
    </body>
</html>