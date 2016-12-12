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
            $question = trim($_POST['txt_question']);
            $howto = trim($_POST['howto']);

            $Sql = "UPDATE `tbuserquestion` SET `tbUserQuestionText` = '" . $question . "', `tbUserQuestionHowTo` = '" . $howto . "' WHERE `tbuserquestion`.`idtbUserQuestion` = " . $id;
			$rs = mysqli_query($conexao, $Sql) or die ("Erowo na pesquisa");
			
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
            $Sql = "SELECT * FROM tbartifact WHERE idtbartifact = " . $_GET['artifact'];
			$rs = mysqli_query($conexao, $Sql);
            while ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
                echo "<option value=" . $row['idtbArtifact'] . ">" . $row['tbArtifactDescription'] . "</option>";
            }
            echo '</select>';

            echo '<select name="sel_criterion" id="criterion" class="form-control">';
            $Sql = "SELECT * FROM `tbcriterion` WHERE idtbcriterion = " . $_GET['artifact'];
			$rs = mysqli_query($conexao, $Sql);
            while ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
                echo "<option value=" . $row['idtbCriterion'] . ">" . $row['tbCriterionDesc'] . "</option>";
            }
            echo '</select>';
            
            echo '<select name="sel_subcriterion" id="subcriterion" class="form-control">';
            $Sql = "SELECT * FROM `tbsubcriterion` WHERE `idtbsubcriterion` = " . $_GET['subcriterion'];
			$rs = mysqli_query($conexao, $Sql);
            while ($row = mysqli_fetch_array($rs, MYSQL_ASSOC)) {
                echo "<option value=" . $row['idtbSubcriterion'] . ">" . $row['tbSubCriterionDesc'] . "</option>";
            }
            echo '</select>';

            

            echo '<br><br>';
            echo '<label>Questão</label><br>';
            echo '<input name="txt_question" value="' . $_GET['question'] . '" type="text" size="100" class="form-control">';
            echo '<br><label>Como responder</label><br>';
            echo '<textarea cols="50" rows="10" name="howto" class="form-control">' . $_GET['howto'] . '</textarea><br>';
            echo '<input type="hidden" size="100" value="' . $_GET['id'] . '" name="sel_id"  style="display:none">';

            echo '<input type="submit" name="Submit" value="Atualizar" class="btn btn-default" /></form>';
			
			echo "<p><a href='listaquestao.php'><img src='img/voltar.png' alt='Voltar para lista' height='30'/></a></p>";
        } else {
            echo $_SERVER['QUERY_STRING'];
        }
        ?>
    </body>



</html>