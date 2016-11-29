<!DOCTYPE html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type" />
<!-- <link rel="stylesheet" href="estilo.css" type="text/css" media="screen" /> -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<title>Cadastrar Usuário</title>
</head>

<body>

<div class="container-fluid">
	<div class="jumbotron">
		<h2>Modelo de Avaliação de Qualidade dos Sistemas Educacionais
baseados em Web Semântica (SEWebS) </h2>
	</div>
	<div id="login">
		<?php
//include("valida.php");
echo 'Bem vindo visitante';
?></div>
	<h3>Cadastre-se:</h3>
	<?php 
	session_start();
// isset verifica se a sessão já existe
/*
if(isset($_SESSION['user_login'])) 
		header('Location: negado.php');
*/
	include "conecta.php";

if (isset($_POST['txt_nome'])) {

//salvar usuario
	$usuario=$_POST['txt_nome'];
	$email=$_POST['txt_email'];
	$user_type=$_POST['sel_usuario'];
	$senha1=md5($_POST['txt_senha1']);
	$senha2=md5($_POST['txt_senha2']);
	
	$codificada1 = md5($senha1);
	$codificada2 = md5($senha2);
	
	echo $codificada1;
	
	$Sql = "INSERT INTO `tbuser` (`idtbUser`, `tbNome`, `tbEmail`, `tbPassword`, `tbUserType_idtbUserType`) VALUES (NULL, '".$usuario."', '".$email."', '".$senha1."', '".$user_type."')";
	
	$rs = mysql_query($Sql, $conexao) or die ("Erro na pesquisa");
			
    if ($rs) {
        echo "<script language='javascript' type='text/javascript'>
		alert('Usuário cadastrado com sucesso. Faça login para ter acesso ao sistema');
			window.location.href='login.php';
		</script>";
	} else {
		echo "<script language='javascript' type='text/javascript'> alert('Erro!'); window.location.href='index.html';</script>";
	}

} else {
	
	echo '<form action="cadastrar.php" method="post" name="form1" class="form-group">';
    echo '<p>Nome Completo: </p> <p><input maxlength="60" name="txt_nome" id="entravalor2" size="50" class="form-control" required /></p>';
    echo '<p>Email para login:</p> <p><input name="txt_email" id="entravalor3" size="40" type="email" class="form-control" required /></p>';
	
	echo '<p>Escolha um tipo de usuário</p> <p><select name="sel_usuario" id="usuario" class="form-control">';
	//echo '<option value=""></option>';
	$Sql = "SELECT * FROM tbusertype";
	$rs = mysql_query($Sql, $conexao);
		while($linha = mysql_fetch_array($rs))
		{
		echo "<option value=".$linha['idtbUserType'].">".$linha['tbUserTypeDescripton']."</option>"; 
	}
	echo 	'</select></p>';
    echo '<p>Senha:</p> <p><input name="txt_senha1" id="entravalor4" size="12" type="password" class="form-control" required /></p>';
    echo '<p>Repita a senha:</p> <p><input name="txt_senha2" id="entravalor" size="12" maxlength="13" type="password" class="form-control" required /></p>';
    echo '<p><input value="Limpar" type="reset" class="btn btn-default"><input value="Enviar" type="submit" class="btn btn-default" onclick="return validar()"></p>';
	echo '</form>';
}
?>
	<script language="javascript" type="text/javascript">
function validar() {
var senha1 = document.form1.txt_senha1.value;
var senha2 = document.form1.txt_senha2.value;

if (senha1 != senha2) {
alert('Senhas diferentes');
form1.txt_senha1.focus();
return false;
}



}
</script>
	<div id="footer" class="well well-sm">
		Desenvolvimento: Ademir Marques Junior - 2016 </div>
</div>

</body>

</html>
