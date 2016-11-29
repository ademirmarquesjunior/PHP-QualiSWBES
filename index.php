<!DOCTYPE html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type" />
<!-- <link rel="stylesheet" href="estilo.css" type="text/css" media="screen" /> -->
<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="css/style.css" rel="stylesheet" />
<title></title>
</head>

<body>

<div class="container-fluid">
	<div class="jumbotron">
		<h2>Modelo de Avaliação de Qualidade dos Sistemas Educacionais baseados 
		em Web Semântica (SEWebS) </h2>
	</div>
	<div id="login" class="well well-sm">
		Bem vindo <?php
//include("valida.php");
if(isset($_SESSION['user_login'])) {
echo " ".$_SESSION['user_login']."' ";
echo "<a href='logout.php'>Sair</a>";
} ?></div>
	<div class="row">
		<div class="col-md-8">
			<p>Esse Modelo de Avaliação de Qualidade dos Sistemas Educacionais baseados 
			em Web Semântica (SEWebS) tem por objetivo permitir que os profissionais 
			(engenheiros do conhecimento, engenheiros de ontologias, desenvolvedores, 
			autores, professores, tutores, gestores de instituições de ensino, estudantes) 
			que precisam adquirir/eleger um sistema para uso possam Avaliar os mesmos, 
			de acordo com critérios estabelecidos e identificados a partir dos domínios 
			da Engenharia de Software, Educação e Web Semântica. Esse documento 
			apresenta o processo de definição dos critérios, assim como a descrição 
			deles, as assertivas que orientam a avaliação, os artefatos que devem 
			ser avaliados em um SEWebS e as referências que dão suporte aos avaliadores. 
			</p>
			<p>Por meio de um Mapeamento Sistemático da Literatura (MS) realizado em 
			2013, foram identificados métodos, modelos, frameworks, técnicas, checklists 
			para avaliação de Sistemas Educacionais Web. Também foi realizada uma 
			revisão adhoc da literatura, para identificar abordagens de avaliação 
			de Sistemas Web Semânticos. Assim, os critérios utilizados nessas abordagens 
			foram abstraídos, mesclados, organizados e adequados para constituir 
			o Modelo de Avaliação em questão.</p>
			<p>O que norteou o processo de ajuste 
			desses critérios foram os problemas encontrados nos Sistemas e-Learning 
			atuais que, apesar de terem evoluído em relação aos sistemas educacionais 
			tradicionais, ainda carecem de funcionalidades que os tornem mais eficazes, 
			eficientes e possam diminuir a carga de trabalho dos professores e autores 
			de conteúdos. De acordo com Stojanovic et al. (2001), os sistemas educacionais 
			tradicionais são centralizados (autoridade – conteúdo selecionado pelo 
			professor), a entrega é “empurrada” (professor empurra o conhecimento 
			para estudantes), falta personalização (conteúdo deve satisfazer as 
			necessidades de muitos estudantes), e o processo de aprendizagem é linear/estático 
			(conteúdo não muda, definido pelo professor no início do curso e permanece 
			assim até o final).</p>
			<p>Nos sistemas e-Learning, o processo de aprendizagem 
			não está mais centralizado no professor (mas sim orientado pelo aluno, 
			que decide quando acessar os conteúdos e atividades), é mais personalizado 
			(mas ainda desenvolvidos para todos os alunos de um curso e não para 
			as necessidades individuais de cada aluno) e também mais linear/dinâmico, 
			(ou seja, o conteúdo está sempre disponível, o acesso se dá de acordo 
			com a agenda do aluno). </p>
			<a class="btn btn-primary btn-large" href="index2.php">Começar uma avaliação</a>
			
		</div>
		<div class="col-md-2">
		</div>
	</div>
</div>
<div id="footer" class="well well-sm">
	Desenvolvimento: Ademir Marques Junior - 2016 </div>

</body>

</html>
