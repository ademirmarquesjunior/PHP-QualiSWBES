<!DOCTYPE html>
<html>

    <head>
        <meta content="text/html; charset=utf-8" http-equiv="content-type" />
        <!-- <link rel="stylesheet" href="estilo.css" type="text/css" media="screen" /> -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <script src="Chart.min.js"></script>
        <title>SEWebS</title>
    </head>
    <?php
    if (!isset($_GET['form']))
        exit;
    include "conecta.php";
    include "function.inc.php";
    ?>
    <body>
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
                    Você avaliou <strong>'<?php $Sql = mysql_query("SELECT * FROM tbform INNER JOIN tbapplication ON tbapplication.idtbApplication = tbform.tbApplication_idtbApplication WHERE tbform.idtbForm = " . $_GET['form']);
                $i = 0;
                while ($rr = mysql_fetch_array($Sql)) {
                    echo $rr['tbApplicationName'];
                    $i++;
                } if (!($i)) header('Location:index2.php'); ?></strong>'
                    como <strong>'<?php $Sql = mysql_query("SELECT * FROM `tbUserType` WHERE `idtbUserType` = " . $_SESSION['user_type']);
                while ($rr = mysql_fetch_array($Sql)) {
                    echo $rr['tbUserTypeDescripton'];
                } ?>'  
                    </strong>  
                </div>
            </div>


            <?php
            $resultados = array();
            $values = array();
            $names = array();
            $resultados_exp = array();

            $Sql = "SELECT * FROM `tbobjectives`";
            $rs = mysql_query($Sql, $conexao) or die("Erro na pesquisa");
            while ($linha = mysql_fetch_array($rs)) {
                $names[$linha["idtbObjectives"] - 1] = $linha["tbObjectivesDesc"];

                $resultados[$linha["idtbObjectives"]] = array();
                $resultados[$linha["idtbObjectives"]] = extraiResultados($_GET['form'], $linha["idtbObjectives"]);
                $value = $resultados[$linha["idtbObjectives"]][2] / $resultados[$linha["idtbObjectives"]][3] * 20;
                $values[$linha["idtbObjectives"] - 1] = $value;
                $resultados_exp[$linha["idtbObjectives"]] = detalhaResultados($resultados[$linha["idtbObjectives"]]);
            }
            ?>
            <div class="panel panel-default">
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-6">
                            <h2>Visão Geral </h2>
                            <p>A aplicação obteve a média geral de <h3><?php printf("%0.2f %% :", array_sum($values) / count(array_filter($values))); ?></h3> distribuídos entre os objetivos abaixo: </p>
                            <canvas id="GraficoBarra" style="width:100%;"></canvas>
                            <?php
                            $loadgraph = '';

                            echo '
		            <script type="text/javascript">
                var options = { responsive:true, scaleOverride:true, scaleSteps:10, scaleStartValue:0, scaleStepWidth:10, display:true };

                var data = {
                    labels: [';
                            foreach ($names as $name) {
                                echo '"' . $name . '",';
                            }
                            echo '],
                    datasets: [
                        {
                            label: "Dados primários",
                            fillColor: "rgba(220,220,220,0.5)",
                            strokeColor: "rgba(220,220,220,0.8)",
                            highlightFill: "rgba(220,220,220,0.75)",
                            highlightStroke: "rgba(220,220,220,1)",
                            data: [';
                            foreach ($values as $value) {
                                echo json_encode($value) . ',';
                            }
                            echo ']
                        }
                    ]
                }</script>;';

                            $loadgraph = $loadgraph . 'var ctx = document.getElementById("GraficoBarra").getContext("2d");
                    var BarChart = new Chart(ctx).Bar(data, options);';
                            ?>

                        </div>
					</div>
				</div>
			</div>
                       <div class="row">
                             <?php
                                for ($i = 1; $i < 5; $i++) {
                                    $artifact_value = $resultados_exp[$i][0];
                                    $artifact_name = $resultados_exp[$i][1];
                                    echo ' <div class="col-md-6"><div class="panel panel-default"><div class="panel-body">
';
                                    echo '<h2>' . $names[$i-1] ." ";
                                    printf("%0.2f%% ", $values[$i-1]);
                                    echo '</h2>';
                                    echo '<p>Explicação curta sobre o objetivo. Obs: talvez fazer uma entrada para esse texto no banco de dados. Obs2: Ler esse texto do banco de dados devido à repetição</p>';
                                    echo 'Os seguintes Artefatos foram avaliados para alcançar este objetivos. Cada artefato ainda é detalhado em fatores e subfatores avaliados:';
                                    echo '<canvas id="GraficoArtefato' . $i . '" style="width:100%;"></canvas>';
                                    echo '
		            <script type="text/javascript">
                var options' . $i . ' = { responsive:true, scaleOverride:true, scaleSteps:10, scaleStartValue:0, scaleStepWidth:10, display:true };

                var data' . $i . ' = {
                    labels: [';
                                    foreach ($artifact_name as $name) {
                                        echo '"' . $name . '",';
                                    }
                                    echo '],
                    datasets: [
                        {
                            label: "Dados primários",
                            fillColor: "rgba(220,220,220,0.5)",
                            strokeColor: "rgba(220,220,220,0.8)",
                            highlightFill: "rgba(220,220,220,0.75)",
                            highlightStroke: "rgba(220,220,220,1)",
                            data: [';
                                    foreach ($artifact_value as $value) {
                                        echo json_encode($value) . ',';
                                    }
                                    echo ']
                        }
                    ]
                };</script>';
                                    $loadgraph = $loadgraph . 'var ctx' . $i . ' = document.getElementById("GraficoArtefato' . $i . '").getContext("2d");
                 var BarChart' . $i . ' = new Chart(ctx' . $i . ').Bar(data' . $i . ', options' . $i . ');';
                 
                 			echo '</div></div></div>';
                                }
                                ?>
                        </div>
                        <div id="footer" class="well well-sm">
                            Desenvolvimento: Ademir Marques Junior - 2016 </div>
                    </div>
                    <?php
                    echo '<script>
         window.onload = function(){
          ' . $loadgraph . '     
          }           
         </script>';
                    ?>        



                    </body>

                    </html>
