<?php
    session_start();
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
        <script src="js/Chart.bundle.min.js"></script>
        <script src="js/sweetalert.js"></script>
        <link rel="icon" type="image/png" href="favicon.png">
        <title><?php echo $lang['PAGE_TITLE']; ?></title>
    </head>
    <body>
        <div class="container-fluid">
<?php
    include 'header.php';
    include 'navbar.php';

    if (isset($_GET['form']) AND is_numeric($_GET['form']) AND ! isset($_GET['applic'])) { //Verificar se a entrada é para exibir por formulário
        //Verificar se o formulário existe
        $Sql = "SELECT * FROM tbform INNER JOIN tbapplication ON tbapplication.idtbApplication = tbform.tbApplication_idtbApplication INNER JOIN tbusertypetext ON tbform.tbUserType_idtbUserType = tbusertypetext.tbUserType_idtbUserType INNER JOIN tbuser ON tbform.tbUser_idtbUser = tbuser.idtbUser WHERE tbform.idtbForm = " . $_GET['form'] . "  AND tblanguage_idtblanguage =  " . $_SESSION['language'];
        $rs = mysqli_query($conexao, $Sql) or die('Erro amarelo');
        $i = 0;
        $completed = 0;
        $applic_id = 0;

        while ($row = mysqli_fetch_assoc($rs)) {
            $user_name = $row['tbUserName'];
            $applic_name = $row['tbApplicationName'];
            $type_name = $row['tbUserTypeDesc'];

            $completed = $row['tbFormCompleted'];
            $applic_id = $row['idtbApplication'];
            $i++;
        }

        //Verificar se o formulário foi finalizado
        if ((!($completed)) AND ( $i)) { //Se o formulário não foi finalizado
            //Iniciar as variáveis de ambiente para preenchimento do formulário
            $_SESSION['form_id'] = $_GET['form'];
            $_SESSION['appic_id'] = $applic_id;
            echo "<script> window.location.assign('form.php')</script>"; //Carregar a página de formulário

        }
        if (!($i)) { //Se não há nenhum resultado carregar a página inicial
            echo "<script> window.location.assign('index.php')</script>";
        }
    } elseif (isset($_GET['applic']) AND is_numeric($_GET['applic']) AND ! isset($_GET['form'])) { //Verificar se a entrada é para exibir por aplicação
        //Verificar se aplicação existe e pelo menos um usuário já finalizou
        $Sql = "SELECT * FROM tbapplication INNER JOIN tbform ON tbapplication.idtbApplication = tbform.tbApplication_idtbApplication WHERE tbform.tbFormCompleted = 1 AND tbapplication.idtbApplication = " . $_GET['applic'];
        $rs = mysqli_query($conexao, $Sql);

        if (mysqli_num_rows($rs)) { 
            while ($row = mysqli_fetch_assoc($rs)) {
                $applic_name = $row['tbApplicationName'];
                $applic_id = $row['idtbApplication'];
            }
        } else { //Se não há nenhum resultado carregar a página inicial
            echo "<script> window.location.assign('index.php')</script>";
        }
    } else { //Se os valores de entrada não são reconhecidos carregar a página inicial
        echo "<script> window.location.assign('index3.php')</script>";
    }

    ?>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php
                        if (isset($_GET['form'])) {
                            echo '<div class="panel panel-default">
                   				<div class="panel-body">';
                            echo "<h3><img src='img/result.png' height='113' alt=''><strong>" . $user_name . "</strong> avaliou <strong>" . $applic_name . "</strong> como <strong>" . $type_name . "</strong></h3>";
                            echo "</div></div>";
                        }

                        if (isset($_GET['applic'])) {
                            echo '<div class="panel panel-default">
                   				<div class="panel-body">';
                            echo "<h2><img src='img/result.png' height='80' alt=''><strong>" . $applic_name . "</strong></h2>";
                            echo "</div></div>";
                            echo '<p><strong>Sistema avaliado por:</strong><br>';

                            $Sql = "SELECT * FROM `tbform` INNER JOIN tbuser ON tbform.tbUser_idtbUser = tbuser.idtbUser INNER JOIN tbusertypetext ON tbform.tbUserType_idtbUserType = tbusertypetext.tbUserType_idtbUserType WHERE tbform.tbFormCompleted = 1 AND tbusertypetext.tbLanguage_idtbLanguage = 1 AND tbform.tbApplication_idtbApplication =" . $applic_id;
                            $rs = mysqli_query($conexao, $Sql);
                            while ($row = mysqli_fetch_assoc($rs)) {
                                echo '<span class="glyphicon glyphicon-user"></span> <strong>' . $row['tbUserName'] . '</strong> como <strong>' . $row['tbUserTypeDesc'] . '</strong><br>';
                            }
                            echo '</p>';
                        }
                        ?>
                    </div>
                </div>

<?php
    $resultados = array(); //matriz com as respostas obtidas do banco de dados em [artedato][fator][subfator]
    $values = 0; //média total obtida
    $counter = 0; //quantidade de questões respondidas
    $resultados_exp = array(); //matriz detalhada em submatrizes [nome do item][média obtida] para artefatos, fatores e subfatores
    $graph_id = 0; //identificação dos gráficos deve ser incrementado antes de ser usado na geração de cada gráfico

    if (isset($_GET['applic'])) { //
        $resultados = extraiResultados($_GET['applic'], 1);
    }

    if (isset($_GET['form'])) {
        $resultados = extraiResultados($_GET['form'], 2);
    }

    $values = $resultados[2] / $resultados[3] * 20;
    $counter = $resultados[4];
    $resultados_exp = detalhaResultados($resultados);

    /**
     * $resultados_exp[0] = Valores para os artefatos
     * $resultados_exp[1] = Nomes para os artefatos
     * $resultados_exp[2] = Valores para os fatores
     * $resultados_exp[3] = Nomes para os fatores
     * $resultados_exp[4] = Valores para os subfatores
     * $resultados_exp[5] = Nomes para os subfatores
     */ 
    if ($values != 0) { 


        //Gerar dados para o gráfico de fatores global
        $sum_factor = array();
        $count_factor = array();
        $names_factor = array();

        for ($k = 0; $k < 6; $k++) {
            if (array_key_exists($k, $resultados_exp[2])) {
                foreach ($resultados_exp[3][$k] as $key => $value) {
                    if (!isset($sum_factor[$key])) $sum_factor[$key] = null;
                    if (!isset($count_factor[$key])) $count_factor[$key] = null;

                    $sum_factor[$key] = $sum_factor[$key] + $resultados_exp[2][$k][$key];
                    $count_factor[$key] ++;
                }
                $names_factor = $names_factor + $resultados_exp[3][$k];
            }
        }
        foreach ($sum_factor as $key => $value) {
            $sum_factor[$key] = $sum_factor[$key] / $count_factor[$key];
        }
        $mean_factor = array_sum($sum_factor) / count($sum_factor);

        echo '  <div class="panel panel-default">
                    <div style="text-align: center;"><h2>Avaliação geral</h2>
                        Questões respondidas <strong>' . $counter . '</strong>';
               
        if (!isset($_GET['form'])) { //mostrar resumo com a avaliação somente se estiver mostrando resultados para a aplicação como um todo      
            echo '      <div class="panel-body">
                            <div class="row">
                                <div class="col-md-5" style="text-align: center;">
                                    <div class="panel panel-default"><div class="panel-body">
                                        <h1>'.$lang['RESULTS_FACTORS'].':</h1>';
                    				    $graph_id = $graph_id + 1;
                                        graficoRadar($names_factor, $sum_factor, $graph_id);
                                        echo '<p>'.$lang['Factors'].'</p>
                                    </div>
                                </div>
                            </div>
                        <div class="col-md-5" style="text-align: center;">
                            <div class="panel panel-default"><div class="panel-body">
                                <h1>'.$lang['RESULTS_MEAN_FACTORS'].':<br> <strong>';       
                                printf("%0.0f%%", $mean_factor);
                                echo '</strong><br>';

            if ($mean_factor < 50) {
            		echo '<img src="img/negativo.png" width="80" height="100" alt=""></h1>';
                echo $lang['RESULTS_INADEQUATE'];
            } elseif ($mean_factor < 80) {
            		echo '<img src="img/meio.png" width="80" height="100" alt=""></h1>';
                echo $lang['RESULTS_RESTRICT_ADEQUATE'];
            } else {
            		echo '<img src="img/positivo.png" width="80" height="100" alt=""></h1>';
                echo $lang['RESULTS_ADEQUATE'];
	        }
            echo '          </div>
                        </div>
                            <div class="panel panel-default"><div class="panel-body">
                                <h1>'.$lang['RESULTS_ARTEFACTS'].':</h1>';
                                $graph_id = $graph_id + 1;
                                graficoBarra2($resultados_exp[1], $resultados_exp[0], $graph_id);
            echo '          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>';
     	}

        //Para cada artefato mostrar os gráficos de fatores
        foreach (array_keys($resultados_exp[1]) as $key) {
            echo '<div class="panel-group">
    					<div class="panel panel-default">
    					    <div class="panel-heading">
    					     	<h3>' . $resultados_exp[1][$key] . '</h3>
                            </div>
                            <div class="panel-body">
                                <div class="row"><div class="col-md-6">';
                               $graph_id = $graph_id + 1;
                               graficoBarraHorizontal($resultados_exp[3][$key], $resultados_exp[2][$key], $graph_id);
            echo '          </div>
                        </div>
                        <hr>
                    <div class="row">';

            //Para cada fator mostrar os gráficos de subfatores
            foreach (array_keys($resultados_exp[3][$key]) as $key2) {
                echo '  <div class="col-md-4"><div class="panel panel-default"><div class="panel-body">
                            <h4>' . $resultados_exp[3][$key][$key2] . '</h4>';
                            $graph_id = $graph_id + 1;
                            graficoPolar($resultados_exp[5][$key][$key2], $resultados_exp[4][$key][$key2], $graph_id);
                echo '  </div>
                    </div>
                </div>';
            }
            echo '</div>
               </div>
            </div>';
        }     
        echo '</div></div>';
    }

?>
            </div>
        </div>
<?php
    include 'footer.php';            
?>        
    </body>
</html>