<!DOCTYPE html>
<html><head>

        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <link rel="stylesheet" href="estilo.css" type="text/css" media="screen"><title>Novo</title>

    </head>

    <body>

        <h1>Editar questão</h1>
        <?php
        include "conecta.php";
		
        if (isset($_POST['sel_id'])) {


            $id = trim($_POST['sel_id']);
            $question = trim($_POST['txt_question']);
            $howto = trim($_POST['howto']);

            $Sql = "UPDATE `tbuserquestion` SET `tbUserQuestionText` = '" . $question . "', `tbUserQuestionHowTo` = '" . $howto . "' WHERE `tbuserquestion`.`idtbUserQuestion` = " . $id;
			$rs = mysql_query($Sql, $conexao) or die ("Erro na pesquisa");
			
            if ($rs) {
                echo "<p>Alterado com sucesso</p>";
            } else {
				echo "erro";
			}

            echo "<p><a href='listaquestao.php'><img src='img/voltar.png' alt='Voltar para lista' height='30'/></a></p>";
            
        } elseif (isset($_GET['id'])) {

            echo '<form id="form1" name="form1" method="POST" action="editaquestao.php">';

            echo 'Questão id: ';
            echo $_GET['id'];
            echo '<br>';

            echo '<select name="sel_usuario" id="usuario">';
            $Sql = mysql_query("SELECT * FROM `tbusertype` WHERE `idtbUserType` = " . $_GET['usertype']);
            while ($rr = mysql_fetch_array($Sql)) {
                echo "<option value=" . $rr['idtbUserType'] . ">" . $rr['tbUserTypeDescripton'] . "</option>";
            }
            echo '</select>';

            echo '<select name="sel_artifact" id="artifact">';
            $Sql = mysql_query("SELECT * FROM `tbArtifact` WHERE idtbArtifact = " . $_GET['artifact']);
            while ($rr = mysql_fetch_array($Sql)) {
                echo "<option value=" . $rr['idtbArtifact'] . ">" . $rr['tbArtifactDescription'] . "</option>";
            }
            echo '</select>';

            echo '<select name="sel_criterion" id="criterion">';
            $Sql = mysql_query("SELECT * FROM `tbCriterion` WHERE idtbCriterion = " . $_GET['artifact']);
            while ($rr = mysql_fetch_array($Sql)) {
                echo "<option value=" . $rr['idtbCriterion'] . ">" . $rr['tbCriterionDesc'] . "</option>";
            }
            echo '</select>';

            echo '<br><br>';
            echo '<label>Questão</label><br>';
            echo '<input name="txt_question" value="' . $_GET['question'] . '" type="text" size="100">';
            echo '<br><label>Como responder</label><br>';
            echo '<textarea cols="50" rows="10" name="howto">' . $_GET['howto'] . '</textarea><br>';
            echo '<input type="hidden" size="100" value="' . $_GET['id'] . '" name="sel_id"  style="display:none">';

            echo '<input type="submit" name="Submit" value="Atualizar" /></form>';
			
			echo "<p><a href='listaquestao.php'><img src='img/voltar.png' alt='Voltar para lista' height='30'/></a></p>";
        } else {
            echo $_SERVER['QUERY_STRING'];
        }
        ?>
    </body>



</html>