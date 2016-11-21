<!DOCTYPE html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="" type="text/css" media="screen" />
<title>Cadastrar Usuário</title>



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
	
	echo '<form action="cadastrar.php" method="post" name="form1">';
    echo '<p>Nome Completo: </p> <p><input maxlength="60" name="txt_nome" id="entravalor2" size="50" required /></p>';
    echo '<p>Email para login:</p> <p><input name="txt_email" id="entravalor3" size="40" type="email" required /></p>';
	
	echo '<p>Escolha um tipo de usuário</p> <p><select name="sel_usuario" id="usuario">';
	//echo '<option value=""></option>';
	$Sql = "SELECT * FROM tbusertype";
	$rs = mysql_query($Sql, $conexao);
		while($linha = mysql_fetch_array($rs))
		{
		echo "<option value=".$linha['idtbUserType'].">".$linha['tbUserTypeDescripton']."</option>"; 
	}
	echo 	'</select></p>';
    echo '<p>Senha:</p> <p><input name="txt_senha1" id="entravalor4" size="12" type="password" required /></p>';
    echo '<p>Repita a senha:</p> <p><input name="txt_senha2" id="entravalor" size="12" maxlength="13" type="password" required /></p>';
    echo '<p><input value="Limpar" type="reset" /><input value="Enviar" type="submit" onclick="return validar()"></p>';
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
</body>
</html>