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

        <div class="container-fluid">
            <div class="jumbotron">
                <h2>Modelo de Avaliação de Qualidade dos Sistemas Educacionais
                    baseados em Web Semântica (SEWebS) </h2>
            </div>
            <div id="login" class="well well-sm">
                <?php
                include("valida.php");
                ?></div>
            <h1>Resultado da avaliação</h1>
            <?php
            include "conecta.php";
            ?>



            <div class="row">
                <div class="col-md-4">
                    <h2>Avaliação geral </h2>
                    <p>Pontuação total considerado todas as questões respondidas </p>
                    <h2><?php
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
                                echo ($total / $counter * 20) . "%";
                        }
                        ?><a class="btn" href="#">View details »</a> </h2>
                </div>
                <div class="col-md-4">
                    <h2>Entrega+Distribuição {Delivery + Authority} - Conteúdo </h2>
                    <p>Conteúdo distribuído e descentralizado na Web, mas ligados por ontologias, 
                        e é entregue pelo sistema de acordo com o perfil do usuário e seus tópicos 
                        de interesse. </p>
                    <p><a class="btn" href="#">View details »</a> </p>
                </div>
                <div class="col-md-4">
                    <h2>Acesso {Acess} - Conteúdo e Sistema </h2>
                    <p>Acesso não linear, e expandido – estudante descreve uma situação 
                        e realiza consultas semânticas sobre um conteúdo adequado (de acordo 
                        com contexto da aprendizagem e perfil do usuário). </p>
                    <p><a class="btn" href="#">View details »</a> </p>
                </div>
                <div class="col-md-4">
                    <h2>Capacidade de Resposta {Responsiveness} - Sistema </h2>
                    <p>Sistema responde de forma proativa com materiais de aprendizagem 
                        de acordo com o contexto do problema atual (cada usuário tem seu agente 
                        personalizado que se comunica com outros agentes). </p>
                    <p><a class="btn" href="#">View details »</a> </p>
                </div>
                <div class="col-md-4">
                    <h2>Dinâmica da Aprendizagem {Symmetry + Modality} - Aprendizagem
                    </h2>
                    <p>Todas as atividades são integradas (ambiente de negócio integrado 
                        - ex.: sistema de notas, matrícula, faltas, cursos já realizados) – 
                        aprendizagem ativa, estimulada pelo sistema por meio dos agentes inteligentes.
                    </p>
                    <p><a class="btn" href="#">View details »</a> </p>
                </div>
            </div>
            <div id="footer" class="well well-sm">
                Desenvolvimento: Ademir Marques Junior - 2016 </div>
        </div>

    </body>

</html>
