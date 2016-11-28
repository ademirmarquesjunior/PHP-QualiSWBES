<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="estilo.css" type="text/css" media="screen" />
<title></title>
</head>
<body>

<div id="header">
Cabeçalho aqui
</div>

<div id="login">
<?php
include("valida.php");
?>
</div>

<div id="content">

<h1>Resultado da avaliação</h1>


<?php
include "conecta.php";


$total = 0;
$counter = 0;


$form = $_GET['form'];

if ($form != '') {

	$Sql = "SELECT * FROM `tbform_has_tbuserquestion` WHERE `tbForm_idtbForm` = ".$form;
	$rs = mysql_query($Sql, $conexao) or die ("Formulário não existe");

	while($linha = mysql_fetch_array($rs)){
		$total=$total+$linha["tbForm_has_tbUserQuestionAnswer"];
		$counter++;
	}
	if ($counter !=0) echo "Média geral: ".$total/$counter;
}


	/*
	Gerar a tela de resultados
	
	Obter das variáveis de sessão:
	usuário
	id do form
	id da aplicação avaliada
	
	
	Obter da tabela form x questions as médias das respostas por artefato criterio e subcriterio
	
	Exibir gráficos para cada categoria
	
	
	
	
	*/
	
?>


</div>

<div id="footer">
Desenvolvimento: Ademir Marques Junior - 2016
</div>



</body>

</html>