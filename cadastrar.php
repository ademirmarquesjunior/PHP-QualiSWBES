<!DOCTYPE html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="" type="text/css" media="screen" />
<title></title>
</head>
<body>

<div id="login">
<?php
//include("valida.php");
echo 'Bem vindo visitante';
?>
</div>


<div id="conteudo">

<h3>Cadastre-se:</h3>
<?php 
	session_start();
// isset verifica se a sessão já existe
if(isset($_SESSION['user_login'])) 
		header('Location: negado.php');


if (isset($_POST['cadastrar'])) {

//salvar usuario
	include "conecta.php";

	$usuario=$_POST['txt_nome'];
	$matricula=$_POST['txt_matricula'];
	$email=$_POST['txt_email'];
	$senha1=md5($_POST['txt_senha1']);
	$senha2=md5($_POST['txt_senha2']);
	
	if ($usuario='') {
		echo 'UM nome deve ser digitado';
		
	}
	
	$codificada1 = md5($senha1);
	$codificada2 = md5($senha2);
	
	if (!($codificada1 == $codificada2)) {
		echo 'As senhas digitadas não conferem';
	}
	
			// 54cf74d1acdb4037ab956c269b63c8ac
} else {
	
	echo '<form action="cadastrar.php" method="post" name="cadastrar">  <table border="0" width="658">    <tbody><tr>';
    echo '<th scope="row" width="329">Nome Completo: </th><td width="319"><input maxlength="60" name="txt_nome" id="entravalor2" size="50" /></td></tr>';
    echo '<tr><th scope="row">Matrícula:</th><td><input maxlength="60" name="txt_matricula" id="entravalor3" size="50" /></td></tr>';
    echo '<tr><th scope="row">Email para a validação: </th><td><input name="txt_email" id="entravalor3" size="40" type="text" /></td></tr>';
    echo '<tr><th scope="row">Crie uma senha:</th><td><input name="txt_senha1" id="entravalor4" size="12" type="password" /></td></tr>';
    echo '<tr><th scope="row">Digite a senha novamente:</th><td><input name="txt_senha2" id="entravalor" size="12" maxlength="13" type="password" /></td></tr>';
    echo '<tr><th scope="row"><input value="Limpar" type="reset" /></th><td><input value="Enviar" type="submit" /></td></tr></tbody></table>';
	echo '</form>';
}
?>

</body>
</html>