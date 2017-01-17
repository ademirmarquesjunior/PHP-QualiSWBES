<!DOCTYPE html>
<html><head>

        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <!-- <link rel="stylesheet" href="estilo.css" type="text/css" media="screen" /> -->
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">
        <title>SEWebS</title>


    </head>

    <body>

        <h1>Editar questão</h1>
        <p><a href='listaquestao.php'><img src='img/voltar.png' alt='Voltar para lista' height='30'/></a></p>
        <?php
        include "conecta.php";
		
        if (isset($_POST['sel_id'])) {


            $id = trim($_POST['sel_id']);
            $questionPort = trim($_POST['txt_questionPort']);
            $howtoPort = trim($_POST['howtoPort']);
            
            $questionEng = trim($_POST['txt_questionEng']);
            $howtoEng = trim($_POST['howtoEng']);
            
            $english = trim($_POST['english']);

				$Sql = "UPDATE `tbquestiontext` SET `tbQuestionText` = '".$questionPort."', `tbQuestionTextHowTo` = '".$howtoPort."' WHERE `tbquestiontext`.`tbLanguage_idtbLanguage` = 1 AND `tbquestiontext`.`tbUserQuestion_idtbUserQuestion` = ".$id;
				$rs = mysqli_query($conexao, $Sql) or die ("Erro na alteração: português");
				
				if ($english == 1) {
					$Sql = "UPDATE `tbquestiontext` SET `tbQuestionText` = '".$questionEng."', `tbQuestionTextHowTo` = '".$howtoEng."' WHERE `tbquestiontext`.`tbLanguage_idtbLanguage` = 2 AND `tbquestiontext`.`tbUserQuestion_idtbUserQuestion` = ".$id;
					$rs = mysqli_query($conexao, $Sql) or die ("Erro na alteração: inglês");
				} else {
					$Sql = "INSERT INTO `tbquestiontext` (`tbQuestionText`, `tbQuestionTextHowTo`, `tbLanguage_idtbLanguage`, `tbUserQuestion_idtbUserQuestion`) VALUES ('".$questionEng."', '".$howtoEng."', '2', '".$id."')";
					$rs = mysqli_query($conexao, $Sql) or die ("Erro na inserção: inglês");
				}
			
			
			
            if ($rs) {
                echo "<p>Alterado com sucesso</p>";
            } else {
				echo "erowo";
			}

            echo "<p><a href='listaquestao.php'><img src='img/voltar.png' alt='Voltar para lista' height='30'/></a></p>";
            
        } elseif (isset($_GET['id'])) {

            echo '<form id="form1" name="form1" method="POST"  class="form-inline" action="editaquestao.php">';

            echo 'Questão id: ';
            echo $_GET['id'];
            echo '<br>';

            echo '<select name="sel_artifact" id="artifact" class="form-control">';
            $Sql = "SELECT * FROM tbartifact INNER JOIN tbartifacttext ON tbartifact.idtbartifact = tbartifacttext.tbartifact_idtbartifact WHERE tbartifacttext.tblanguage_idtblanguage = 1 AND  idtbartifact = ". $_GET['artifact'];
				$rs = mysqli_query($conexao, $Sql);
            while ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
                echo "<option value=" . $row['idtbArtifact'] . ">" . $row['tbArtifactName'] . "</option>";
            }
            echo '</select>';

            echo '<select name="sel_criterion" id="criterion" class="form-control">';
            $Sql = "SELECT * FROM tbFactor INNER JOIN tbfactortext ON tbfactor.idtbfactor = tbfactortext.tbfactor_idtbfactor WHERE tbfactortext.tblanguage_idtblanguage = 1 AND idtbFactor = " . $_GET['Factor'];
				$rs = mysqli_query($conexao, $Sql);
            while ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
                echo "<option value=" . $row['idtbFactor'] . ">" . $row['tbFactorName'] . "</option>";
            }
            echo '</select>';
            
            echo '<select name="sel_subcriterion" id="subcriterion" class="form-control">';
            $Sql = "SELECT * FROM tbsubFactor INNER JOIN tbsubfactortext ON tbsubfactor.idtbsubfactor = tbsubfactortext.tbsubfactor_idtbsubfactor  WHERE tbsubfactortext.tblanguage_idtblanguage = 1 AND idtbsubFactor = " . $_GET['subFactor'];
				$rs = mysqli_query($conexao, $Sql);
            while ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
                echo "<option value=" . $row['idtbSubFactor'] . ">" . $row['tbSubFactorName'] . "</option>";
            }
            echo '</select>';

            

            echo '<br><br>';
            echo '<label>Português</label><br>';
            echo '<input name="txt_questionPort" value="';
				$Sql = "SELECT * FROM tbquestiontext WHERE tbquestiontext.tbLanguage_idtbLanguage = '1' AND tbquestiontext.tbUserQuestion_idtbUserQuestion = '".$_GET['id']."'";
				$rs = mysqli_query($conexao, $Sql);
            while ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
                echo $row['tbQuestionText'];
            }            
            
            echo '" type="text" size="100" class="form-control">';
            echo '<br><label>Como responder</label><br>';
            echo '<textarea cols="50" rows="10" name="howtoPort" class="form-control">';
            $Sql = "SELECT * FROM tbquestiontext WHERE tbquestiontext.tbLanguage_idtbLanguage = '1' AND tbquestiontext.tbUserQuestion_idtbUserQuestion = '".$_GET['id']."'";
				$rs = mysqli_query($conexao, $Sql);
            while ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
                echo $row['tbQuestionTextHowTo'];
            }  
            echo '</textarea><br>';
            
             echo '<br><br>';
            echo '<label>Inglês</label><br>';
            echo '<input name="txt_questionEng" value="';
				$Sql = "SELECT * FROM tbquestiontext WHERE tbquestiontext.tbLanguage_idtbLanguage = '2' AND tbquestiontext.tbUserQuestion_idtbUserQuestion = '".$_GET['id']."'";
				$rs = mysqli_query($conexao, $Sql);
				$i = 0;
            while ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
                echo $row['tbQuestionText'];
                $i++;
            }            
            echo '" type="text" size="100" class="form-control">';
            echo '<input type="hidden" size="100" value="' . $i . '" name="english"  style="display:none">';
            
            echo '<br><label>Como responder</label><br>';
            echo '<textarea cols="50" rows="10" name="howtoEng" class="form-control">';
            $Sql = "SELECT * FROM tbquestiontext WHERE tbquestiontext.tbLanguage_idtbLanguage = '2' AND tbquestiontext.tbUserQuestion_idtbUserQuestion = '".$_GET['id']."'";
				$rs = mysqli_query($conexao, $Sql);
            while ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
                echo $row['tbQuestionTextHowTo'];
            }  
            echo '</textarea><br>';
            
            
            
            echo '<input type="hidden" size="100" value="' . $_GET['id'] . '" name="sel_id"  style="display:none">';

            echo '<input type="submit" name="Submit" value="Atualizar" class="btn btn-default" /></form>';
			
			echo "<p><a href='listaquestao.php'><img src='img/voltar.png' alt='Voltar para lista' height='30'/></a></p>";
        } else {
            echo $_SERVER['QUERY_STRING'];
        }
        ?>
    </body>



</html>