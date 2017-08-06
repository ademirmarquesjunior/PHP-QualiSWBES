<?php
/* 
------------------
Language: Português
------------------
*/

$lang = array();

$lang['PAGE_TITLE'] = 'QualiSWBES';
$lang['HEADER_TITLE'] = 'QualiSWBES';
$lang['SITE_NAME'] = 'QualiSWBES';
$lang['HEADING'] = 'Método para avaliação da qualidade<br> de Sistemas Educacionais baseados<br> em Web Semântica (SWBES)';

// Menu
$lang['MENU_INTRODUCTION'] = 'Introdução';
$lang['MENU_EVALUATE'] = 'Avaliar';
$lang['MENU_MANAGER'] = 'Gerenciar avaliações';
$lang['MENU_ADMINISTRATOR'] = 'Administrador';
	$lang['MENU_ADM_USERS'] = 'Gerênciar usuários';
	$lang['MENU_ADM_QUESTIONS'] = 'Gerênciar questões';
$lang['MENU_SIGNUP'] = 'Cadastre-se';


// INDEX
$lang['INDEX_TEXT'] = '<p>A ferramenta QualiSWBES tem por objetivo automatizar a avaliação da qualidade de Sistemas Educacionais baseados em Web Semântica (SWBES, do inglês Semantic Web based Educational Systems) de modo que os profissionais (desenvolvedores, autores, professores, tutores, gestores de instituições de ensino, estudantes etc.) que precisam adquirir/eleger um SWBES para uso, possam avaliar os mesmos de forma mais rápida, eficiente e eficaz.</p>
<p>A Abordagem que originou a ferramenta QuaSWebES foi desenvolvida com base em pesquisas realizadas nos Modelos de Avaliação de Qualidade existentes na literatura, tanto os que avaliam Software, Software Educacional, Interface, quanto os que avaliam Objetos de Aprendizagem e Tecnologias da Web Semântica (principalmente Ontologias).</p>
<p>Além disso, foram considerados os padrões/normas internacionais ISO/IEC SQuaRE 25000 e os adotados pelo W3C que contribuíram para estabelecer e definir os fatores e critérios que compuseram a Abordagem.</p>
<p>Por fim, por se tratar de Abordagem que avalia SWBES, as Heurísticas de Nielsen e Regras de Ouro de Shneiderman também foram analisadas, pois usabilidade é uma característica da qualidade do sistema que contribui para facilitar seu uso pelos usuários, principalmente estudantes, e permite que eles possam se concentrar em aprender os conteúdos e não gastar tempo e esforço para aprender a usar a ferramenta.</p>
<p>Dessa forma, os fatores e critérios foram ajustados e se definiu os Artefatos e Avaliadores responsáveis pelo processo de avaliação.</p>
<p>Os <b>Fatores</b> que serão avaliados são Adaptabilidade, Interoperabilidade, Personalização, Reusabilidade E Usabilidade.<br>
Os <b>Artefatos</b> são: Ontologias, Objetos de Aprendizagem, Interface e Software.<br>
Os <b>Avaliadores</b> são: Engenheiro de Conhecimento (ou Ontologias), Desenvolvedores, Autores, Professores, Tutores (ou mediadores), Estudantes e Gestores.</p>';

// index3
$lang['INDEX_MANAGER'] = 'Gerente de avaliações';
$lang['INDEX_MANAGER_NEW'] = 'Cadastre um novo sistema a ser avaliado';
$lang['INDEX_MANAGER_PLACEHOLDER_NAME'] = 'Nome do Sistema';
$lang['INDEX_MANAGER_PLACEHOLDER_TEXT'] = 'Breve descrição';
$lang['INDEX_MANAGER_BUTTON'] = 'Inserir';

// Form
$lang['FORM_ONTOLOGY_TEXT'] = 'Você está avaliando a ontologia';
$lang['FORM_LEARNINGOBJECT_TEXT'] = 'Você está avaliando o objeto de aprendizagem';
$lang['FORM_INTERFACE_TEXT'] = 'Você está avaliando a interface';
$lang['FORM_SOFTWARE_TEXT'] = 'Você está avaliando o software';
$lang['FORM_BUTTON'] = 'Seguir';

// Results
//$lang[''] = '';
$lang['RESULTS_ADEQUATE'] = '<h3>Sistema Adequado:</h3> Mostra que os fatores de qualidade adaptabilidade, interoperabilidade, reusabilidade, personalização e usabilidade foram atendidos em 80 a 100% da nota possível. Assim, esse resultado indica que o SWBES em questão atende aos requisitos de um sistema educacional Web de qualidade de acordo com a literatura (Bittencourt et al., 2008; Devedžić, 2006; Pandit, 2010; Stojanovic et al., 2001)';
$lang['RESULTS_RESTRICT_ADEQUATE'] = '<h3>Sistema Adequado com restrições:</h3> Mostra que os fatores de qualidade adaptabilidade, interoperabilidade, reusabilidade, personalização e usabilidade foram atendidos em 50 a 80% da nota possível. Assim, esse resultado indica que o SWBES em questão atende em parte aos requisitos de um sistema educacional Web de qualidade, de acordo com a literatura (Bittencourt et al., 2008; Devedžić, 2006; Pandit, 2010; Stojanovic et al., 2001)';
$lang['RESULTS_INADEQUATE'] = '<h3>Sistema Inadequado:</h3> Mostra que os fatores de qualidade adaptabilidade, interoperabilidade, reusabilidade, personalização e usabilidade não atingiram 50 % da nota possível. Assim, esse resultado indica que o SWBES em questão não atende aos requisitos de um sistema educacional Web de qualidade de acordo com a literatura (Bittencourt et al., 2008; Devedžić, 2006; Pandit, 2010; Stojanovic et al., 2001)';
$lang['Factors'] = '<b>Adaptabilidade</b> – refere-se: ao grau de facilidade com que o artefato pode ser adaptado às mudanças inesperadas na especificação ou requisitos do sistema; à capacidade de ampliar/estender as funcionalidades do artefato sem muito esforço (outros dispositivos, sistemas, navegadores etc.); ao grau de facilidade de manutenção do artefato;</br>
<b>Interoperabilidade</b> – refere-se: à habilidade de dois ou mais sistemas interagirem e trocarem dados entre si; ao grau de compartilhamento de informação por meio de algumas ferramentas, tais como linguagem de marcação adequada, uso de metadados e arquiteturas de metadados.</br>
<b>Personalização</b> – refere-se ao grau em que o artefato está adaptado às necessidades do usuário (linguagem, design etc.); ao grau em que cada usuário tem sua própria visão do sistema educacional por meio da anotação semântica dos conceitos usados e gerados durante o processo de aprendizagem; à geração e recuperação de feedback a partir dos dados de contexto do conteúdo de aprendizagem anotado.</br>
<b>Reusabilidade</b> – refere-se: ao grau de reutilização do artefato em diversos sistemas e contextos diferentes, para diferentes propósitos na aprendizagem, não exclusivamente para o qual foi concebido; ao uso de qualquer artefato, já produzido, na construção de outro sistema (OA, ontologias, código fonte, requisitos, diagramas UML, testes, manual etc.).</br>
<b>Usabilidade</b> – refere-se: à facilidade que os usuários têm em aprender a utilizar o artefato para alcançar seus objetivos e o quão satisfeitos ficam com o processo; à fácilidade de aprender, à eficiência no uso, à facilidade de memorizar, à robustez; ao grau de padronização do sistema; ao grau de facilidade de navegação e qualidade dos recursos de ajuda da interface.
';


?>