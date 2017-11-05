-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 04-Set-2017 às 02:36
-- Versão do servidor: 5.5.51-38.2
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `caede741_cida`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbapplication`
--

CREATE TABLE IF NOT EXISTS `tbapplication` (
  `idtbApplication` int(11) NOT NULL,
  `tbApplicationName` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `tbApplicationDescription` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `tbUser_idtbUser` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbartifact`
--

CREATE TABLE IF NOT EXISTS `tbartifact` (
  `idtbArtifact` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `tbartifact`
--

INSERT INTO `tbartifact` (`idtbArtifact`) VALUES
(1),
(2),
(3),
(4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbartifacttext`
--

CREATE TABLE IF NOT EXISTS `tbartifacttext` (
  `tbArtifact_idtbArtifact` int(11) NOT NULL,
  `tbLanguage_idtbLanguage` int(11) NOT NULL,
  `tbArtifactName` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `tbArtifactDesc` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `tbartifacttext`
--

INSERT INTO `tbartifacttext` (`tbArtifact_idtbArtifact`, `tbLanguage_idtbLanguage`, `tbArtifactName`, `tbArtifactDesc`) VALUES
(1, 1, 'Ontologia', 'Ontologias definem um vocabulário comum para o domínio dos SWBES, composto por classes, relacionamentos, regras e instâncias, de modo que esse mesmo vocabulário possa ser reutilizado em outro sistema.'),
(1, 2, 'Ontology', 'Ontology'),
(2, 1, 'Objeto de Aprendizagem', 'Objetos de aprendizagem são entidades digitais utilizadas no processo de ensino e aprendizagem. Possui três componentes básicos: conteúdo, atividades de aprendizagem e elementos de contexto (devem conter metadados, que definem os atributos necessários para descrição completa do OA).'),
(2, 2, 'Learning Object', 'Learning Object'),
(3, 1, 'Interface', 'A interface é responsável pela interação entre os usuários e o sistema, é um ambiente gráfico que oferece suporte aos papéis distintos dos usuários, de acordo com a necessidade do sistema (ambientes de desenvolvimento, produção etc.).'),
(3, 2, 'Interface', 'Interface'),
(4, 1, 'Software', 'O software compreende qualquer elemento do sistema (tais como como linhas de código, diagramas UML, documentação etc.).'),
(4, 2, 'Software', 'Software');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbfactor`
--

CREATE TABLE IF NOT EXISTS `tbfactor` (
  `idtbFactor` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `tbfactor`
--

INSERT INTO `tbfactor` (`idtbFactor`) VALUES
(1),
(2),
(3),
(4),
(5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbfactortext`
--

CREATE TABLE IF NOT EXISTS `tbfactortext` (
  `tbFactor_idtbFactor` int(11) NOT NULL,
  `tbLanguage_idtbLanguage` int(11) NOT NULL,
  `tbFactorName` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `tbFactorDesc` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `tbfactortext`
--

INSERT INTO `tbfactortext` (`tbFactor_idtbFactor`, `tbLanguage_idtbLanguage`, `tbFactorName`, `tbFactorDesc`) VALUES
(1, 1, 'Adaptabilidade', NULL),
(1, 2, 'Adaptability', 'Adaptability'),
(2, 1, 'Interoperabilidade', NULL),
(2, 2, 'Interoperability', 'Interoperability'),
(3, 1, 'Personalização', NULL),
(3, 2, 'Customization', 'Customization'),
(4, 1, 'Reusabilidade', NULL),
(4, 2, 'Reusability', 'Reusability'),
(5, 1, 'Usabilidade', NULL),
(5, 2, 'Usability', 'Usability');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbform`
--

CREATE TABLE IF NOT EXISTS `tbform` (
  `idtbForm` int(11) NOT NULL,
  `tbApplication_idtbApplication` int(11) NOT NULL,
  `tbUser_idtbUser` int(11) NOT NULL,
  `tbFormCompleted` tinyint(1) NOT NULL DEFAULT '0',
  `tbFormStatus` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tbUserType_idtbUserType` int(11) NOT NULL,
  `tbFormDate` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Estrutura da tabela `tbform_has_tbuserquestion`
--

CREATE TABLE IF NOT EXISTS `tbform_has_tbuserquestion` (
  `idtbForm_has_tbUserQuestion` int(11) NOT NULL,
  `tbForm_idtbForm` int(11) NOT NULL,
  `tbUserQuestion_idtbUserQuestion` int(11) NOT NULL,
  `tbForm_has_tbUserQuestionAnswer` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1276 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Estrutura da tabela `tblanguage`
--

CREATE TABLE IF NOT EXISTS `tblanguage` (
  `idtbLanguage` int(11) NOT NULL,
  `tbLanguageDesc` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `tblanguage`
--

INSERT INTO `tblanguage` (`idtbLanguage`, `tbLanguageDesc`) VALUES
(1, 'Português'),
(2, 'English');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblearningobjects`
--

CREATE TABLE IF NOT EXISTS `tblearningobjects` (
  `idLearningObjects` int(11) NOT NULL,
  `tbLearningObjectsName` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `tbLearningObjectsDesc` tinytext COLLATE utf8_unicode_ci,
  `tbApplication_idtbApplication` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbontologies`
--

CREATE TABLE IF NOT EXISTS `tbontologies` (
  `idOntologies` int(11) NOT NULL,
  `tbOntologiesName` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `tbOntologiesText` tinytext COLLATE utf8_unicode_ci,
  `tbApplication_idtbApplication` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Estrutura da tabela `tbquestion`
--

CREATE TABLE IF NOT EXISTS `tbquestion` (
  `idtbQuestion` int(11) NOT NULL,
  `tbArtifact_idtbArtifact` int(11) NOT NULL,
  `tbFactor_idtbFactor` int(11) NOT NULL,
  `tbSubFactor_idtbSubFactor` int(11) NOT NULL,
  `tbQuestionId_idtbQuestionId` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=169 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `tbquestion`
--

INSERT INTO `tbquestion` (`idtbQuestion`, `tbArtifact_idtbArtifact`, `tbFactor_idtbFactor`, `tbSubFactor_idtbSubFactor`, `tbQuestionId_idtbQuestionId`) VALUES
(40, 1, 5, 9, 1),
(96, 1, 1, 12, 52),
(97, 1, 1, 12, 53),
(98, 1, 1, 12, 54),
(99, 1, 1, 15, 55),
(100, 1, 1, 15, 56),
(101, 1, 2, 2, 57),
(102, 1, 2, 6, 58),
(103, 1, 2, 22, 59),
(104, 1, 4, 1, 60),
(105, 1, 4, 1, 61),
(107, 1, 4, 1, 62),
(108, 1, 4, 1, 63),
(109, 1, 4, 1, 64),
(110, 1, 4, 5, 65),
(111, 1, 4, 5, 66),
(112, 1, 4, 5, 67),
(113, 1, 4, 5, 68),
(114, 1, 4, 7, 69),
(115, 1, 4, 9, 70),
(116, 1, 4, 10, 71),
(117, 1, 4, 15, 72),
(118, 1, 4, 17, 73),
(119, 1, 4, 20, 74),
(120, 1, 4, 23, 75),
(121, 1, 5, 2, 76),
(122, 1, 5, 2, 77),
(123, 1, 4, 2, 78),
(124, 1, 5, 9, 79),
(125, 1, 5, 9, 80),
(154, 1, 4, 6, 58),
(155, 1, 4, 22, 59),
(158, 1, 4, 2, 57),
(159, 1, 5, 2, 57),
(160, 1, 1, 12, 103),
(167, 1, 4, 23, 109),
(60, 2, 1, 12, 16),
(61, 2, 1, 15, 17),
(62, 2, 1, 21, 18),
(63, 2, 2, 2, 19),
(64, 2, 2, 22, 20),
(65, 2, 2, 22, 21),
(66, 2, 3, 3, 22),
(67, 2, 3, 4, 23),
(68, 2, 3, 14, 24),
(69, 2, 3, 14, 25),
(70, 2, 3, 14, 26),
(71, 2, 3, 18, 27),
(72, 2, 3, 18, 28),
(73, 2, 3, 18, 29),
(74, 2, 4, 1, 30),
(75, 2, 4, 1, 31),
(76, 2, 4, 1, 32),
(77, 2, 4, 5, 33),
(78, 2, 4, 5, 34),
(79, 2, 4, 6, 35),
(80, 2, 4, 6, 36),
(81, 2, 4, 7, 37),
(82, 2, 4, 10, 38),
(83, 2, 4, 15, 39),
(84, 2, 4, 15, 40),
(85, 2, 4, 15, 41),
(86, 2, 4, 17, 42),
(87, 2, 4, 17, 43),
(88, 2, 4, 23, 44),
(89, 2, 5, 8, 45),
(90, 2, 5, 9, 46),
(91, 2, 5, 11, 47),
(92, 2, 5, 13, 48),
(93, 2, 5, 16, 49),
(94, 2, 5, 19, 50),
(95, 2, 5, 24, 51),
(148, 2, 4, 2, 19),
(149, 2, 5, 2, 19),
(150, 2, 4, 22, 20),
(151, 2, 4, 22, 21),
(163, 2, 4, 23, 104),
(164, 2, 5, 8, 105),
(165, 2, 5, 9, 106),
(44, 3, 3, 4, 4),
(46, 3, 1, 15, 2),
(47, 3, 1, 21, 3),
(48, 3, 3, 14, 5),
(49, 3, 3, 18, 6),
(50, 3, 3, 18, 7),
(52, 3, 5, 2, 8),
(53, 3, 5, 8, 9),
(54, 3, 5, 9, 10),
(55, 3, 5, 11, 11),
(56, 3, 5, 13, 12),
(57, 3, 5, 16, 13),
(58, 3, 5, 19, 14),
(59, 3, 5, 24, 15),
(166, 3, 3, 18, 107),
(126, 4, 1, 12, 81),
(127, 4, 1, 15, 82),
(129, 4, 2, 2, 84),
(130, 4, 2, 22, 85),
(131, 4, 4, 1, 86),
(132, 4, 4, 5, 87),
(134, 4, 4, 6, 89),
(136, 4, 4, 10, 91),
(137, 4, 4, 15, 92),
(138, 4, 4, 20, 93),
(139, 4, 4, 22, 94),
(140, 4, 4, 23, 95),
(141, 4, 5, 8, 96),
(142, 4, 5, 9, 97),
(143, 4, 5, 11, 98),
(144, 4, 5, 13, 99),
(145, 4, 5, 16, 100),
(146, 4, 5, 19, 101),
(147, 4, 5, 24, 102),
(156, 4, 4, 2, 84),
(157, 4, 5, 2, 84),
(161, 4, 1, 21, 83),
(162, 4, 4, 6, 88),
(168, 4, 4, 7, 90);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbquestionid`
--

CREATE TABLE IF NOT EXISTS `tbquestionid` (
  `idtbQuestionId` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `tbquestionid`
--

INSERT INTO `tbquestionid` (`idtbQuestionId`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13),
(14),
(15),
(16),
(17),
(18),
(19),
(20),
(21),
(22),
(23),
(24),
(25),
(26),
(27),
(28),
(29),
(30),
(31),
(32),
(33),
(34),
(35),
(36),
(37),
(38),
(39),
(40),
(41),
(42),
(43),
(44),
(45),
(46),
(47),
(48),
(49),
(50),
(51),
(52),
(53),
(54),
(55),
(56),
(57),
(58),
(59),
(60),
(61),
(62),
(63),
(64),
(65),
(66),
(67),
(68),
(69),
(70),
(71),
(72),
(73),
(74),
(75),
(76),
(77),
(78),
(79),
(80),
(81),
(82),
(83),
(84),
(85),
(86),
(87),
(88),
(89),
(90),
(91),
(92),
(93),
(94),
(95),
(96),
(97),
(98),
(99),
(100),
(101),
(102),
(103),
(104),
(105),
(106),
(107),
(108),
(109);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbquestiontext`
--

CREATE TABLE IF NOT EXISTS `tbquestiontext` (
  `tbQuestionText` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `tbQuestionTextHowTo` text COLLATE utf8_unicode_ci,
  `tbLanguage_idtbLanguage` int(11) NOT NULL,
  `tbQuestionId_idtbQuestionId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `tbquestiontext`
-- As questões ainda precisam ser traduzidas para o inglês

INSERT INTO `tbquestiontext` (`tbQuestionText`, `tbQuestionTextHowTo`, `tbLanguage_idtbLanguage`, `tbQuestionId_idtbQuestionId`) VALUES
('A arquitetura organizacional, o (complexo) middleware de aplicação, as propriedades comerciais, o custo, a acessibilidade, o esforço de desenvolvimento da ontologia contribuem com o sistema?', '', 1, 1),
('A interface pode ser configurada para uso em outros sistemas, cursos, disciplinas?', 'Por exemplo, a interface ou alguma parte dela pode ser utilizada nas disciplinas de matemática e química; ou a interface com recursos para a disciplina de introdução à computação no curso de engenharia pode ser utilizada para o curso de física.', 1, 2),
('A interface pode ser utilizada em outros dispositivos, sistemas operacionais e navegadores?', 'A mesma interface funciona em laptop, tablet, smartphone; e também no google chrome, explorer, mozilla firefox etc.', 1, 3),
('A interface facilita a colaboração entre os pares (estudante-professor, estudante-sistema, professor-sistema, autor-sistema etc.)?', 'Por exemplo, há mecanismos na interface que possibilitam a criação de foruns (ou tópicos nos fóruns já existentes), chats, envio de atividades, download de materiais etc.?', 1, 4),
('A interface facilita o fornecimento de feedback ao usuário (de acordo com seu perfil de uso do sistema)?', 'Por exemplo, há estudantes que tem um desempenho melhor por meio de funcionalidades de gamificação, outros preferem respostas textuais etc.', 1, 5),
('A interface facilita a busca dos conteúdos no sistema?', '', 1, 6),
('A interface permite controle pelo usuário (ele pode cancelar, avançar, voltar de acordo com sua segurança na atividade em questão)?', '', 1, 7),
('A interface foi desenvolvida com base em padrões internacionais (W3C etc.)?', '', 1, 8),
('O esforço do estudante para uso da interface é baixo?', 'Por exemplo: a interface apresenta gráficos, figuras, botões de navegação intuitivos, usuais, comuns e fáceis de serem compreendidos, de acordo com os padrões universais.', 1, 9),
('A interface permite que o estudante realize a atividade de aprendizagem de forma rápida e segura?', '', 1, 10),
('A interface oferece aos usuários condições para que realize a atividade de acordo com seu nível de experiência no uso da interface?', 'Por exemplo, usuários mais experientes têm acesso a atalhos para realizar suas tarefas, podem "pular" determinadas etapas da navegação no sistema.', 1, 11),
('A interface facilita o processo de aprendizagem, deixa claro os objetivos da atividade, do conteúdo?', '', 1, 12),
('A interface contribui para que o estudante realize as atividade sem erros, com segurança, e possa recuperar-se se o erro acontecer?', 'Por exemplo, presença de botões e menus com instruções para caso de erros, possibilidade de refazer a atividade, retornar à atividade anterior ao erro ou avançar para a próxima atividade, a despeito do erro.', 1, 13),
('O conteúdo da interface está organizado em ordem lógica que facilite memorização e aprendizagem?', '', 1, 14),
('A interface possui elementos que motivam o usuário a realizar as atividades em pouco tempo e bons resultados?', 'Por exemplo, há elementos gráficos, visuais, intuitivos? A interface conta com elementos de gamificação?', 1, 15),
('É possível ampliar o uso do OA para outros domínios?', 'Por exemplo, utilizar o mesmo OA em atividades num curso de Engenharia e num curso de Física?', 1, 16),
('O OA pode ser usado em outros sistemas, cursos, disciplinas, situações pedagógicas por meio de sua configuração?', 'Por exemplo, um outro sistema educacional (e não aquele para o qual foi criado) ou outro curso (um tutorial sobre java ser utilizado nos cursos de computação e engenharia) etc.', 1, 17),
('O OA pode ser utilizado em outros dispositivos, navegadores, sistemas operacionais sem necessitar alterações?', 'O OA é exibido com a mesma qualidade em um aparelho celular, tablet, desktop e ainda, se for usado navegador google chrome, explorer ou mozilla firefox etc.', 1, 18),
('Foram utilizados padrões no desenvolvimento do OA (IEEE-LOM, UK-LOM, IMS, IEEE-LTSC, SCORM)?', '', 1, 19),
('Foram utilizados padrões (Dublin Core, LOM etc.) nos metadados que descrevem o OA?', '', 1, 20),
('Os metadados estão claros, consistentes, completos, coerentes com o conteúdo do OA?', '', 1, 21),
('O OA está relacionado aos conceitos da ontologia de domínio (anotação semântica por metadados padronizados)?', '', 1, 22),
('O OA ou os metadados do OA estão armazenados em repositórios de modo que possam ser recuperados pelos usuários ou outros sistemas?', '', 1, 23),
('O OA provê cada feedback e constrói um modelo do aluno para individualizar as suas atividades e ambiente?', '', 1, 24),
('O OA provê mensagens sobre as atividades de acordo com o perfil do estudante?', '', 1, 25),
('O feedback para o OA é devidamente armazenado em repositórios no sistema?', '', 1, 26),
('O OA permite autonomia do estudante (ou seja, ele consegue realizar sozinho e no momento que quiser a atividade)?', 'Por exemplo, o OA é claro o suficiente para o estudante resolver o que fazer, quando e como?', 1, 27),
('O OA permite boa cognição e percepção do objetivo de aprendizagem pelo estudante?', '', 1, 28),
('O OA contribui para que o estudante tenha controle sobre o processo de aprendizagem (ou seja, ele pode cancelar, avançar, voltar de acordo com sua segurança ao OA em questão)?', '', 1, 29),
('As definições do OA estão completas, sem erros e organizadas?', '', 1, 30),
('As obras utilizadas no OA foram citadas e referenciadas?', '', 1, 31),
('Há sumário das atividades do OA?', '', 1, 32),
('Não há redundâncias ou ambiguidades na definição do OA ?', '', 1, 33),
('O OA está devida e formalmente descrito para seu entendimento por agentes humanos e computacionais?', '', 1, 34),
('A URI do OA está explícita, válida e disponível?', '', 1, 35),
('Não há restrições, necessidade de registro/licença ou custo para acesso ao OA?', '', 1, 36),
('A documentação do OA condiz com a modelagem?', '', 1, 37),
('O formato dos metadados do OA é simples, estruturado ou rico? (nota zero se não existir metadados)', 'Simples – dados não-estruturados, cuja recuperação é feita de modo automático, gerados por robôs, apresenta na maioria das vezes uma semântica reduzida; \r\nFormato Estruturado -  mais estruturado baseado em normas emergentes e que proporciona uma descrição mais clara do recurso por proporcionar o armazenamento da informação em campos, facilitando assim a recuperação do recurso. Nessa categoria começa a ser inserido a ajuda de especialistas em informação. Como exemplo dessa categoria podemos citar o padrão Dublin Core; \r\nFormato Rico -  mais complexos, com alto grau de descrição, baseados em normas especializadas e códigos específicos. Seu alto nível de especificidade possibilita a descrição ideal de recursos, sendo eles individuais ou pertencentes a coleções em um repositório, facilitando assim sua localização. Como exemplo dessa categoria podemos citar o formato MARC', 1, 38),
('OA contempla conteúdos de currículos internacionais?', '', 1, 39),
('OA é independente do contexto (pode ser utilizado em mais que uma disciplina, por exemplo, sem necessidade de alterações)?', '', 1, 40),
('OA está representado numa ontologia?', '', 1, 41),
('O elemento visual do OA é "forte", ou seja, é possivel compreender os objetivos de aprendizagem a partir do rótulo, ícone ou animação do OA?', '', 1, 42),
('O tamanho do OA é o menor possível e sua complexidade é baixa?', '', 1, 43),
('O URI do OA está explícito e é válido?', '', 1, 44),
('O esforço do estudante para uso do OA é baixo?', '', 1, 45),
('O OA permite que o estudante realize a atividade de aprendizagem de forma rápida e segura?', '', 1, 46),
('O OA oferece aos usuários condições para que realize a atividade de acordo com seu nível de experiência no assunto do OA.?', '', 1, 47),
('O OA facilita o processo de aprendizagem (ou seja, conteúdo e os objetivos da atividade estão claros)?', '', 1, 48),
('O OA contribui para que o estudante realize as atividades com segurança (ou seja, que possa recuperar-se se acontecer algum erro)?', '', 1, 49),
('O conteúdo do OA está organizado em ordem lógica que facilite memorização e aprendizagem?', '', 1, 50),
('O OA possui elementos que motivem o usuário a realizar as atividades e aprender (por exemplo, conseguir realizar em pouco tempo e ter bons resultados)?', '', 1, 51),
('Novos conceitos adicionados à ontologia não prejudicam as  informações já existentes, nem as relações estabelecidas?', '', 1, 52),
('Os conceitos existentes permitem adicionar novas classes e subclasses a partir deles?', '', 1, 53),
('A ontologia é suficientemente formal para permitir adicionar novos conceitos sem necessidade de inserir novas regras ou axiomas?', '', 1, 54),
('A ontologia pode ser utilizada em outros domínios sem que sejam necessárias alterações nos conceitos existentes?', '', 1, 55),
('As instâncias da ontologia podem ser facilmente manipuladas e aplicadas em diferentes tarefas e domínios?', '', 1, 56),
('Foram utilizados padrões (OWL, RDF, RDFs, SWRL etc.) na especificação da Ontologia?', '', 1, 57),
('A ontologia está disponível (tempo, custo, direitos autorais etc.)?', '', 1, 58),
('Os metadados possuem informações balanceadas, são claros, simples e padronizados?', 'balanceados: entre o que é necessário e o que é suficiente;\r\nclaros: os elementos de metadados devem ser bem definidos e devem ser providas descrições claras; \r\nsimples: o vocabulário deve ser fácil de usar\r\npadronizados: foram utilizados padrões (Dublin Core, MARC etc.)', 1, 59),
('A distância semântica entre conceitos irmãos é a menor possível? (por ex., entre um conceito e outro não deve existir outro termo não definido na ontologia)', 'Exemplo: Ontologia Mesa - conceitos: Móvel, Madeira, Tampo, Pernas, Pé. Se faltar o conceito "pernas", a distância entre "pé" e "tampo" fica maior.)', 1, 60),
('A ontologia está completa?', '', 1, 61),
('As definições dos conceitos são objetivas e independentes do contexto?', 'Por exemplo o conceito "pé" - se for definido dependente do contexto, será definido como "pé da mesa" e aí não poderá ser reutilizado como "pé de humano". Para ser independente do contexto, deve ser definido como "pé" e estar relacionado ou "à mesa" ou "ao humano".', 1, 62),
('Os conceitos e relacionamentos das ontologias estão claros?', '', 1, 63),
('Os conceitos da ontologia estão corretos, isentos de erros e confiáveis?', '', 1, 64),
('A ontologia é de Domínio, Tarefa, Aplicação ou Topo (alto nível)?', '', 1, 65),
('As propriedades da ontologia são coerentes com o domínio?', '', 1, 66),
('Existem axiomas redundantes?', '', 1, 67),
('O entendimento da modelagem é fácil (documentação, escrita e anotações em expressão humana)?', '', 1, 68),
('A documentação da ontologia condiz com a modelagem da ontologia?', '', 1, 69),
('O desempenho dos raciocinadores é adequado, com respostas eficientes e rápidas?', '', 1, 70),
('Os metadados possuem formato Simples, Estruturado ou Rico? (se não estiverem definidos metadados, nota zero; a nota deve aumentar do simples: 1 até o rico: 5)', '', 1, 71),
('A ontologia pode ser utilizada para outros domínios com pouco esforço, ou seja, sem que sejam alterados seus conceitos?', '', 1, 72),
('O tamanho dos módulos da ontologia contribuem para que possam ser reutilizadas em domínios diferentes?', '', 1, 73),
('A ontologia está constituída em módulos que podem ser compreendidos por outras ontologias e outros módulos?', '', 1, 74),
('A ontologia está acessível na Web?', '', 1, 75),
('Foram utilizados padrões no desenvolvimento da ontologia?', '', 1, 76),
('A ontologia contém informações sobre propriedades estruturais, funcionais ou orientadas para o engenheiro de ontologias/conhecimento?', '', 1, 77),
('Existem também propriedades puramente orientadas para o ciclo de vida, como a autoria, preço, versão, implantação organizacional, interface?', '', 1, 78),
('A arquitetura organizacional, o custo, a acessibilidade, o esforço de desenvolvimento da ontologia contribuem com o bom desempenho do sistema?', '', 1, 79),
('A ontologia inclui anotações e metadados?', '', 1, 80),
('Código, parte do código, diagramas, componentes são extensíveis a outros softwares em outros domínios?', '', 1, 81),
('O software  pode ser usado em outros sistemas, cursos, disciplinas, situações pedagógicas?', '', 1, 82),
('O software pode ser utilizado em outros dispositivos, sistemas operacionais e navegadores sem necessitar alterações?', '', 1, 83),
('O software está desenvolvido de acordo com os padrões universais (W3C, ISO-IEC, IEEE e demais padrões da Engenharia de Software)?', '', 1, 84),
('Os metadados estão claros, simples, padronizados, extensíveis, breves?', '', 1, 85),
('O código fonte (diagramas UML, documento de requisitos etc.) estão completos, isentos de erro e disponíveis?', '', 1, 86),
('O código fonte está claro, sem ambiguidades?', '', 1, 87),
('Há ontologia com informações sobre código, requisitos, acesso ao código (software)?', '', 1, 88),
('O código fonte está disponível 24X7, sem custo, sem necessidade de registro para o acesso?', '', 1, 89),
('A documentação existe e está disponível (diagramas UML, casos de uso, requisitos, código fonte etc.)?', '', 1, 90),
('A estrutura de metadados permite a reusabilidade de componentes da biblioteca ou ontologia ?', '', 1, 91),
('O software é flexível, pode ser utilizado em outros contextos com poucos ajustes?', '', 1, 92),
('O software é modularizado, ou seja, o código fonte está dividido em partes pequenas o suficiente para seu  entendimento?', '', 1, 93),
('Os metadados estão claros, consistentes, completos, coerentes com o software reutilizável?', '', 1, 94),
('O software e componentes de software possuem endereço conhecido (nas bibliotecas e ontologias) de modo que possam ser encontrados e recuperados?', '', 1, 95),
('O esforço do estudante para uso do software é baixo?', '', 1, 96),
('O software  permite que o estudante realize a atividade de aprendizagem de forma rápida, segura, com sucesso?', '', 1, 97),
('O software  oferece aos usuários condições para que realize a atividade de acordo com seu nível de experiência no uso da interface?', '', 1, 98),
('O software facilita o processo de aprendizagem, deixa claro os objetivos da atividade, do conteúdo?', '', 1, 99),
('O software  contribui para que o estudante realize as atividade sem erros, com segurança, e possa recuperar-se se o erro acontecer?', '', 1, 100),
('O conteúdo do software  está organizado em ordem lógica que facilite memorização e aprendizagem?', '', 1, 101),
('O software  possui elementos que motivem o usuário a realizar as atividades, aprender, em pouco tempo e bons resultados?', '', 1, 102),
('Novas instâncias adicionadas à ontologia não prejudicam as  informações já existentes, nem as relações estabelecidas?', '', 1, 103),
('O nome do OA é coerente com o conteúdo do OA?', '', 1, 104),
('A taxa de erros no uso do OA é pequena?', '', 1, 105),
('O OA permite que o estudante realize a atividade de aprendizagem com sucesso?', '', 1, 106),
('A interface facilita a troca de informações entre usuários e entre eles e o sistema?', '', 1, 107),
('A taxa de erros no uso da interface é pequena?', '', 1, 108),
('O nome da ontologia é coerente com o conteúdo dela?', '', 1, 109);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbsubfactor`
--

CREATE TABLE IF NOT EXISTS `tbsubfactor` (
  `idtbSubFactor` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `tbsubfactor`
--

INSERT INTO `tbsubfactor` (`idtbSubFactor`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13),
(14),
(15),
(16),
(17),
(18),
(19),
(20),
(21),
(22),
(23),
(24);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbsubfactortext`
--

CREATE TABLE IF NOT EXISTS `tbsubfactortext` (
  `tbSubFactor_idtbSubFactor` int(11) NOT NULL,
  `tbLanguage_idtbLanguage` int(11) NOT NULL,
  `tbSubFactorName` varchar(45) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `tbsubfactortext`
--

INSERT INTO `tbsubfactortext` (`tbSubFactor_idtbSubFactor`, `tbLanguage_idtbLanguage`, `tbSubFactorName`) VALUES
(1, 1, 'Acuracidade'),
(1, 2, 'Accuracy'),
(2, 1, 'Adequação aos padrões'),
(2, 2, 'Compliance with standards'),
(3, 1, 'Anotação semântica'),
(3, 2, 'Semantic annotation'),
(4, 1, 'Colaboração'),
(4, 2, 'Colaboration'),
(5, 1, 'Consistência'),
(5, 2, 'Consistency'),
(6, 1, 'Disponibilidade'),
(6, 2, 'Availability'),
(7, 1, 'Documentação'),
(7, 2, 'Documentation'),
(8, 1, 'Eficácia para aprendizagem'),
(8, 2, 'Efficacy for learning'),
(9, 1, 'Eficiência'),
(9, 2, 'Eficiency'),
(10, 1, 'Estrutura de metadados'),
(10, 2, 'Metadata structure'),
(11, 1, 'Experiência do usuário'),
(11, 2, 'User experience'),
(12, 1, 'Extensibilidade'),
(12, 2, 'Extensibility'),
(13, 1, 'Facilidade na aprendizagem'),
(13, 2, 'Ease in learning'),
(14, 1, 'Feedback'),
(14, 2, 'Feedback'),
(15, 1, 'Flexibilidade'),
(15, 2, 'Flexibility'),
(16, 1, 'Gestão de erros e segurança'),
(16, 2, 'Error and security management'),
(17, 1, 'Granularidade'),
(17, 2, 'Granularity'),
(18, 1, 'Interação/Comunicação'),
(18, 2, 'Interaction / Communication'),
(19, 1, 'Memorização'),
(19, 2, 'Memorization'),
(20, 1, 'Modularidade'),
(20, 2, 'Modularity'),
(21, 1, 'Portabilidade'),
(21, 2, 'Portability'),
(22, 1, 'Qualidade metadados'),
(22, 2, 'Quality Metadata'),
(23, 1, 'Rastreabilidade'),
(23, 2, 'Traceability'),
(24, 1, 'Satisfação do usuário'),
(24, 2, 'User Satisfaction');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbuser`
--

CREATE TABLE IF NOT EXISTS `tbuser` (
  `idtbUser` int(11) NOT NULL,
  `tbUserName` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `tbUserEmail` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `tbUserPassword` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `tbUserLevel` int(11) NOT NULL DEFAULT '1',
  `tbUserValid` int(11) DEFAULT NULL,
  `tbUserManagerRequest` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `tbuser`
-- A senha de admin@admin é 'admin'

INSERT INTO `tbuser` (`idtbUser`, `tbUserName`, `tbUserEmail`, `tbUserPassword`, `tbUserLevel`, `tbUserValid`, `tbUserManagerRequest`) VALUES
(1, 'Admin', 'admin@admin', '21232f297a57a5a743894a0e4a801fc3', 3, 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbuserprofile`
--

CREATE TABLE IF NOT EXISTS `tbuserprofile` (
  `idtbUserProfile` int(11) NOT NULL,
  `tbUserProfileAge` int(11) NOT NULL,
  `tbUserProfileEducation` int(11) NOT NULL,
  `tbUserProfileGender` int(11) NOT NULL,
  `tbUserProfileOccupation` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `tbUserProfileInstitution` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `tbUserProfileCountry` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `tbUser_idtbUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Estrutura da tabela `tbusertype`
--

CREATE TABLE IF NOT EXISTS `tbusertype` (
  `idtbUserType` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `tbusertype`
--

INSERT INTO `tbusertype` (`idtbUserType`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbusertypetext`
--

CREATE TABLE IF NOT EXISTS `tbusertypetext` (
  `tbUserType_idtbUserType` int(11) NOT NULL,
  `tbLanguage_idtbLanguage` int(11) NOT NULL,
  `tbUserTypeDesc` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `tbUserTypeText` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `tbusertypetext`
--

INSERT INTO `tbusertypetext` (`tbUserType_idtbUserType`, `tbLanguage_idtbLanguage`, `tbUserTypeDesc`, `tbUserTypeText`) VALUES
(1, 1, 'Engenheiro do Conhecimento + Autor', 'Engenheiro do Conhecimento + Autor ou Engenheiro de Ontologias - responsável pelo desenvolvimento e manutenção da ou das Ontologias do Sistema.\n'),
(1, 2, 'Knowledge Engineer + Author', 'Knowledge Engineer + Author'),
(2, 1, 'Autor', 'Responsável pelo desenvolvimento e criação dos materiais/conteúdos de aprendizagem.'),
(2, 2, 'Author', 'Author'),
(3, 1, 'Professor/Tutor', 'Qualquer usuário do Sistema Educacional: professores, estudantes, tutores virtuais/online, tutores presenciais, gestores da instituição, coordenadores, diretores etc.'),
(3, 2, 'Teacher/Tutor', 'User'),
(4, 1, 'Desenvolvedor', 'Profissional responsável pelo desenvolvimento e manutenção do SWBES (documentação - requisitos, diagramas - UML etc.), código, base de dados, interface (GUI) etc.)'),
(4, 2, 'Developer', 'Developer'),
(5, 1, 'Estudante', NULL),
(5, 2, 'Student', NULL),
(6, 1, 'Gestor', NULL),
(6, 2, 'Manager', NULL),
(7, 1, 'Especialista', NULL),
(7, 2, 'Specialist', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbusertype_has_tbuserquestion`
--

CREATE TABLE IF NOT EXISTS `tbusertype_has_tbuserquestion` (
  `tbUserType_idtbUserType` int(11) NOT NULL,
  `tbUserType_has_tbUserQuestionWeight` decimal(4,2) NOT NULL DEFAULT '1.00',
  `tbQuestionId_idtbQuestionId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `tbusertype_has_tbuserquestion`
--

INSERT INTO `tbusertype_has_tbuserquestion` (`tbUserType_idtbUserType`, `tbUserType_has_tbUserQuestionWeight`, `tbQuestionId_idtbQuestionId`) VALUES
(1, '1.00', 4),
(1, '1.00', 5),
(1, '1.00', 6),
(1, '1.00', 7),
(1, '1.00', 9),
(1, '1.00', 10),
(1, '1.00', 11),
(1, '1.00', 12),
(1, '1.00', 14),
(1, '1.00', 15),
(1, '1.00', 16),
(1, '1.00', 17),
(1, '1.00', 18),
(1, '1.00', 19),
(1, '1.00', 20),
(1, '1.00', 21),
(1, '1.00', 22),
(1, '1.00', 23),
(1, '1.00', 25),
(1, '1.00', 26),
(1, '1.00', 27),
(1, '1.00', 28),
(1, '1.00', 29),
(1, '1.00', 30),
(1, '1.00', 31),
(1, '1.00', 32),
(1, '1.00', 33),
(1, '1.00', 34),
(1, '1.00', 35),
(1, '1.00', 36),
(1, '1.00', 37),
(1, '1.00', 38),
(1, '1.00', 39),
(1, '1.00', 40),
(1, '1.00', 41),
(1, '1.00', 42),
(1, '1.00', 43),
(1, '1.00', 44),
(1, '1.00', 45),
(1, '1.00', 46),
(1, '1.00', 47),
(1, '1.00', 48),
(1, '1.00', 49),
(1, '1.00', 50),
(1, '1.00', 51),
(1, '1.00', 52),
(1, '1.00', 53),
(1, '1.00', 54),
(1, '1.00', 55),
(1, '1.00', 56),
(1, '1.00', 57),
(1, '1.00', 58),
(1, '1.00', 59),
(1, '1.00', 60),
(1, '1.00', 61),
(1, '1.00', 62),
(1, '1.00', 63),
(1, '1.00', 64),
(1, '1.00', 66),
(1, '1.00', 67),
(1, '1.00', 68),
(1, '1.00', 69),
(1, '1.00', 70),
(1, '1.00', 71),
(1, '1.00', 72),
(1, '1.00', 73),
(1, '1.00', 74),
(1, '1.00', 75),
(1, '1.00', 77),
(1, '1.00', 78),
(1, '1.00', 79),
(1, '1.00', 80),
(1, '1.00', 103),
(1, '1.00', 104),
(1, '1.00', 105),
(1, '1.00', 106),
(1, '1.00', 107),
(1, '1.00', 109),
(2, '1.00', 104),
(2, '1.00', 105),
(2, '1.00', 106),
(2, '1.00', 107),
(3, '1.00', 4),
(3, '1.00', 5),
(3, '1.00', 6),
(3, '1.00', 7),
(3, '1.00', 9),
(3, '1.00', 10),
(3, '1.00', 11),
(3, '1.00', 12),
(3, '1.00', 14),
(3, '1.00', 15),
(3, '1.00', 18),
(3, '1.00', 25),
(3, '1.00', 26),
(3, '1.00', 27),
(3, '1.00', 28),
(3, '1.00', 29),
(3, '1.00', 45),
(3, '1.00', 46),
(3, '1.00', 47),
(3, '1.00', 48),
(3, '1.00', 49),
(3, '1.00', 50),
(3, '1.00', 51),
(3, '1.00', 104),
(3, '1.00', 105),
(3, '1.00', 106),
(3, '1.00', 107),
(4, '1.00', 2),
(4, '1.00', 3),
(4, '1.00', 4),
(4, '1.00', 5),
(4, '1.00', 6),
(4, '1.00', 7),
(4, '1.00', 8),
(4, '1.00', 9),
(4, '1.00', 10),
(4, '1.00', 11),
(4, '1.00', 12),
(4, '1.00', 13),
(4, '1.00', 14),
(4, '1.00', 15),
(4, '1.00', 81),
(4, '1.00', 82),
(4, '1.00', 83),
(4, '1.00', 84),
(4, '1.00', 85),
(4, '1.00', 86),
(4, '1.00', 87),
(4, '1.00', 88),
(4, '1.00', 89),
(4, '1.00', 90),
(4, '1.00', 91),
(4, '1.00', 92),
(4, '1.00', 93),
(4, '1.00', 94),
(4, '1.00', 95),
(4, '1.00', 96),
(4, '1.00', 100),
(4, '1.00', 102),
(4, '1.00', 107),
(5, '1.00', 4),
(5, '1.00', 5),
(5, '1.00', 6),
(5, '1.00', 7),
(5, '1.00', 9),
(5, '1.00', 10),
(5, '1.00', 11),
(5, '1.00', 12),
(5, '1.00', 14),
(5, '1.00', 15),
(5, '1.00', 18),
(5, '1.00', 25),
(5, '1.00', 26),
(5, '1.00', 27),
(5, '1.00', 28),
(5, '1.00', 29),
(5, '1.00', 45),
(5, '1.00', 46),
(5, '1.00', 47),
(5, '1.00', 48),
(5, '1.00', 49),
(5, '1.00', 50),
(5, '1.00', 51),
(5, '1.00', 104),
(5, '1.00', 105),
(5, '1.00', 106),
(5, '1.00', 107),
(6, '1.00', 4),
(6, '1.00', 5),
(6, '1.00', 6),
(6, '1.00', 7),
(6, '1.00', 9),
(6, '1.00', 10),
(6, '1.00', 11),
(6, '1.00', 12),
(6, '1.00', 14),
(6, '1.00', 15),
(6, '1.00', 25),
(6, '1.00', 26),
(6, '1.00', 27),
(6, '1.00', 28),
(6, '1.00', 29),
(6, '1.00', 45),
(6, '1.00', 46),
(6, '1.00', 47),
(6, '1.00', 48),
(6, '1.00', 49),
(6, '1.00', 50),
(6, '1.00', 51),
(6, '1.00', 104),
(6, '1.00', 105),
(6, '1.00', 106),
(6, '1.00', 107),
(7, '1.00', 2),
(7, '1.00', 3),
(7, '1.00', 4),
(7, '1.00', 5),
(7, '1.00', 6),
(7, '1.00', 7),
(7, '1.00', 8),
(7, '1.00', 9),
(7, '1.00', 10),
(7, '1.00', 11),
(7, '1.00', 12),
(7, '1.00', 13),
(7, '1.00', 14),
(7, '1.00', 15),
(7, '1.00', 16),
(7, '1.00', 17),
(7, '1.00', 18),
(7, '1.00', 19),
(7, '1.00', 20),
(7, '1.00', 21),
(7, '1.00', 22),
(7, '1.00', 23),
(7, '1.00', 25),
(7, '1.00', 26),
(7, '1.00', 27),
(7, '1.00', 28),
(7, '1.00', 29),
(7, '1.00', 30),
(7, '1.00', 31),
(7, '1.00', 32),
(7, '1.00', 33),
(7, '1.00', 34),
(7, '1.00', 35),
(7, '1.00', 36),
(7, '1.00', 37),
(7, '1.00', 38),
(7, '1.00', 39),
(7, '1.00', 40),
(7, '1.00', 41),
(7, '1.00', 42),
(7, '1.00', 43),
(7, '1.00', 44),
(7, '1.00', 45),
(7, '1.00', 46),
(7, '1.00', 47),
(7, '1.00', 48),
(7, '1.00', 49),
(7, '1.00', 50),
(7, '1.00', 51),
(7, '1.00', 52),
(7, '1.00', 53),
(7, '1.00', 54),
(7, '1.00', 55),
(7, '1.00', 56),
(7, '1.00', 57),
(7, '1.00', 58),
(7, '1.00', 59),
(7, '1.00', 60),
(7, '1.00', 61),
(7, '1.00', 62),
(7, '1.00', 63),
(7, '1.00', 64),
(7, '1.00', 66),
(7, '1.00', 67),
(7, '1.00', 68),
(7, '1.00', 69),
(7, '1.00', 70),
(7, '1.00', 71),
(7, '1.00', 72),
(7, '1.00', 73),
(7, '1.00', 74),
(7, '1.00', 75),
(7, '1.00', 77),
(7, '1.00', 78),
(7, '1.00', 79),
(7, '1.00', 80),
(7, '1.00', 81),
(7, '1.00', 82),
(7, '1.00', 83),
(7, '1.00', 84),
(7, '1.00', 85),
(7, '1.00', 86),
(7, '1.00', 87),
(7, '1.00', 88),
(7, '1.00', 89),
(7, '1.00', 90),
(7, '1.00', 91),
(7, '1.00', 92),
(7, '1.00', 93),
(7, '1.00', 94),
(7, '1.00', 95),
(7, '1.00', 96),
(7, '1.00', 100),
(7, '1.00', 102),
(7, '1.00', 103),
(7, '1.00', 104),
(7, '1.00', 105),
(7, '1.00', 106),
(7, '1.00', 107),
(7, '1.00', 109);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbapplication`
--
ALTER TABLE `tbapplication`
  ADD PRIMARY KEY (`idtbApplication`,`tbUser_idtbUser`), ADD KEY `fk_tbApplication_tbUser1_idx` (`tbUser_idtbUser`);

--
-- Indexes for table `tbartifact`
--
ALTER TABLE `tbartifact`
  ADD PRIMARY KEY (`idtbArtifact`);

--
-- Indexes for table `tbartifacttext`
--
ALTER TABLE `tbartifacttext`
  ADD PRIMARY KEY (`tbArtifact_idtbArtifact`,`tbLanguage_idtbLanguage`), ADD KEY `fk_tbArtifact_has_tbLanguage_tbLanguage1_idx` (`tbLanguage_idtbLanguage`), ADD KEY `fk_tbArtifact_has_tbLanguage_tbArtifact1_idx` (`tbArtifact_idtbArtifact`);

--
-- Indexes for table `tbfactor`
--
ALTER TABLE `tbfactor`
  ADD PRIMARY KEY (`idtbFactor`);

--
-- Indexes for table `tbfactortext`
--
ALTER TABLE `tbfactortext`
  ADD PRIMARY KEY (`tbFactor_idtbFactor`,`tbLanguage_idtbLanguage`), ADD KEY `fk_tbFactor_has_tbLanguage_tbLanguage1_idx` (`tbLanguage_idtbLanguage`), ADD KEY `fk_tbFactor_has_tbLanguage_tbFactor1_idx` (`tbFactor_idtbFactor`);

--
-- Indexes for table `tbform`
--
ALTER TABLE `tbform`
  ADD PRIMARY KEY (`idtbForm`,`tbApplication_idtbApplication`,`tbUser_idtbUser`), ADD KEY `fk_tbForm_tbApplication1_idx` (`tbApplication_idtbApplication`), ADD KEY `fk_tbForm_tbUser1_idx` (`tbUser_idtbUser`), ADD KEY `fk_tbForm_tbUserType1_idx` (`tbUserType_idtbUserType`);

--
-- Indexes for table `tbform_has_tbuserquestion`
--
ALTER TABLE `tbform_has_tbuserquestion`
  ADD PRIMARY KEY (`idtbForm_has_tbUserQuestion`), ADD KEY `fk_tbForm_has_tbUserQuestion_tbUserQuestion1_idx` (`tbUserQuestion_idtbUserQuestion`), ADD KEY `fk_tbForm_has_tbUserQuestion_tbForm1_idx` (`tbForm_idtbForm`);

--
-- Indexes for table `tblanguage`
--
ALTER TABLE `tblanguage`
  ADD PRIMARY KEY (`idtbLanguage`);

--
-- Indexes for table `tblearningobjects`
--
ALTER TABLE `tblearningobjects`
  ADD PRIMARY KEY (`idLearningObjects`,`tbApplication_idtbApplication`), ADD KEY `fk_tbLearningObjects_tbApplication1_idx` (`tbApplication_idtbApplication`);

--
-- Indexes for table `tbontologies`
--
ALTER TABLE `tbontologies`
  ADD PRIMARY KEY (`idOntologies`,`tbApplication_idtbApplication`), ADD KEY `fk_tbOntologies_tbApplication1_idx` (`tbApplication_idtbApplication`);

--
-- Indexes for table `tbquestion`
--
ALTER TABLE `tbquestion`
  ADD PRIMARY KEY (`idtbQuestion`,`tbArtifact_idtbArtifact`,`tbFactor_idtbFactor`,`tbSubFactor_idtbSubFactor`,`tbQuestionId_idtbQuestionId`), ADD KEY `fk_tbUserQuestion_tbArtifact1_idx` (`tbArtifact_idtbArtifact`), ADD KEY `fk_tbUserQuestion_tbFactor1_idx` (`tbFactor_idtbFactor`), ADD KEY `fk_tbUserQuestion_tbSubFactor1_idx` (`tbSubFactor_idtbSubFactor`), ADD KEY `fk_tbQuestion_tbQuestionId1_idx` (`tbQuestionId_idtbQuestionId`);

--
-- Indexes for table `tbquestionid`
--
ALTER TABLE `tbquestionid`
  ADD PRIMARY KEY (`idtbQuestionId`);

--
-- Indexes for table `tbquestiontext`
--
ALTER TABLE `tbquestiontext`
  ADD PRIMARY KEY (`tbLanguage_idtbLanguage`,`tbQuestionId_idtbQuestionId`), ADD KEY `fk_tbQuestionText_tbLanguage1_idx` (`tbLanguage_idtbLanguage`), ADD KEY `fk_tbQuestionText_tbQuestionId1_idx` (`tbQuestionId_idtbQuestionId`);

--
-- Indexes for table `tbsubfactor`
--
ALTER TABLE `tbsubfactor`
  ADD PRIMARY KEY (`idtbSubFactor`);

--
-- Indexes for table `tbsubfactortext`
--
ALTER TABLE `tbsubfactortext`
  ADD PRIMARY KEY (`tbSubFactor_idtbSubFactor`,`tbLanguage_idtbLanguage`), ADD KEY `fk_tbSubFactor_has_tbLanguage_tbLanguage1_idx` (`tbLanguage_idtbLanguage`), ADD KEY `fk_tbSubFactor_has_tbLanguage_tbSubFactor1_idx` (`tbSubFactor_idtbSubFactor`);

--
-- Indexes for table `tbuser`
--
ALTER TABLE `tbuser`
  ADD PRIMARY KEY (`idtbUser`), ADD UNIQUE KEY `tbUserEmail_UNIQUE` (`tbUserEmail`);

--
-- Indexes for table `tbuserprofile`
--
ALTER TABLE `tbuserprofile`
  ADD PRIMARY KEY (`idtbUserProfile`,`tbUser_idtbUser`), ADD KEY `fk_tbUserProfile_tbUser1_idx` (`tbUser_idtbUser`);

--
-- Indexes for table `tbusertype`
--
ALTER TABLE `tbusertype`
  ADD PRIMARY KEY (`idtbUserType`);

--
-- Indexes for table `tbusertypetext`
--
ALTER TABLE `tbusertypetext`
  ADD PRIMARY KEY (`tbUserType_idtbUserType`,`tbLanguage_idtbLanguage`), ADD KEY `fk_tbUserType_has_tbLanguage_tbLanguage1_idx` (`tbLanguage_idtbLanguage`), ADD KEY `fk_tbUserType_has_tbLanguage_tbUserType1_idx` (`tbUserType_idtbUserType`);

--
-- Indexes for table `tbusertype_has_tbuserquestion`
--
ALTER TABLE `tbusertype_has_tbuserquestion`
  ADD PRIMARY KEY (`tbUserType_idtbUserType`,`tbQuestionId_idtbQuestionId`), ADD KEY `fk_tbUserType_has_tbUserQuestion_tbUserType1_idx` (`tbUserType_idtbUserType`), ADD KEY `fk_tbUserType_has_tbUserQuestion_tbQuestionId1_idx` (`tbQuestionId_idtbQuestionId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbapplication`
--
ALTER TABLE `tbapplication`
  MODIFY `idtbApplication` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `tbartifact`
--
ALTER TABLE `tbartifact`
  MODIFY `idtbArtifact` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tbfactor`
--
ALTER TABLE `tbfactor`
  MODIFY `idtbFactor` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tbform`
--
ALTER TABLE `tbform`
  MODIFY `idtbForm` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT for table `tbform_has_tbuserquestion`
--
ALTER TABLE `tbform_has_tbuserquestion`
  MODIFY `idtbForm_has_tbUserQuestion` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1276;
--
-- AUTO_INCREMENT for table `tblanguage`
--
ALTER TABLE `tblanguage`
  MODIFY `idtbLanguage` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tblearningobjects`
--
ALTER TABLE `tblearningobjects`
  MODIFY `idLearningObjects` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `tbontologies`
--
ALTER TABLE `tbontologies`
  MODIFY `idOntologies` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `tbquestion`
--
ALTER TABLE `tbquestion`
  MODIFY `idtbQuestion` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=169;
--
-- AUTO_INCREMENT for table `tbquestionid`
--
ALTER TABLE `tbquestionid`
  MODIFY `idtbQuestionId` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=110;
--
-- AUTO_INCREMENT for table `tbsubfactor`
--
ALTER TABLE `tbsubfactor`
  MODIFY `idtbSubFactor` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `tbuser`
--
ALTER TABLE `tbuser`
  MODIFY `idtbUser` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `tbusertype`
--
ALTER TABLE `tbusertype`
  MODIFY `idtbUserType` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `tbapplication`
--
ALTER TABLE `tbapplication`
ADD CONSTRAINT `fk_tbApplication_tbUser1` FOREIGN KEY (`tbUser_idtbUser`) REFERENCES `tbuser` (`idtbUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tbartifacttext`
--
ALTER TABLE `tbartifacttext`
ADD CONSTRAINT `fk_tbArtifact_has_tbLanguage_tbArtifact1` FOREIGN KEY (`tbArtifact_idtbArtifact`) REFERENCES `tbartifact` (`idtbArtifact`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_tbArtifact_has_tbLanguage_tbLanguage1` FOREIGN KEY (`tbLanguage_idtbLanguage`) REFERENCES `tblanguage` (`idtbLanguage`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tbfactortext`
--
ALTER TABLE `tbfactortext`
ADD CONSTRAINT `fk_tbFactor_has_tbLanguage_tbFactor1` FOREIGN KEY (`tbFactor_idtbFactor`) REFERENCES `tbfactor` (`idtbFactor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_tbFactor_has_tbLanguage_tbLanguage1` FOREIGN KEY (`tbLanguage_idtbLanguage`) REFERENCES `tblanguage` (`idtbLanguage`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tbform`
--
ALTER TABLE `tbform`
ADD CONSTRAINT `fk_tbForm_tbApplication1` FOREIGN KEY (`tbApplication_idtbApplication`) REFERENCES `tbapplication` (`idtbApplication`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_tbForm_tbUser1` FOREIGN KEY (`tbUser_idtbUser`) REFERENCES `tbuser` (`idtbUser`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_tbForm_tbUserType1` FOREIGN KEY (`tbUserType_idtbUserType`) REFERENCES `tbusertype` (`idtbUserType`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tbform_has_tbuserquestion`
--
ALTER TABLE `tbform_has_tbuserquestion`
ADD CONSTRAINT `fk_tbForm_has_tbUserQuestion_tbForm1` FOREIGN KEY (`tbForm_idtbForm`) REFERENCES `tbform` (`idtbForm`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_tbForm_has_tbUserQuestion_tbUserQuestion1` FOREIGN KEY (`tbUserQuestion_idtbUserQuestion`) REFERENCES `tbquestion` (`idtbQuestion`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tblearningobjects`
--
ALTER TABLE `tblearningobjects`
ADD CONSTRAINT `fk_tbLearningObjects_tbApplication1` FOREIGN KEY (`tbApplication_idtbApplication`) REFERENCES `tbapplication` (`idtbApplication`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tbontologies`
--
ALTER TABLE `tbontologies`
ADD CONSTRAINT `fk_tbOntologies_tbApplication1` FOREIGN KEY (`tbApplication_idtbApplication`) REFERENCES `tbapplication` (`idtbApplication`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tbquestion`
--
ALTER TABLE `tbquestion`
ADD CONSTRAINT `fk_tbQuestion_tbQuestionId1` FOREIGN KEY (`tbQuestionId_idtbQuestionId`) REFERENCES `tbquestionid` (`idtbQuestionId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_tbUserQuestion_tbArtifact1` FOREIGN KEY (`tbArtifact_idtbArtifact`) REFERENCES `tbartifact` (`idtbArtifact`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_tbUserQuestion_tbFactor1` FOREIGN KEY (`tbFactor_idtbFactor`) REFERENCES `tbfactor` (`idtbFactor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_tbUserQuestion_tbSubFactor1` FOREIGN KEY (`tbSubFactor_idtbSubFactor`) REFERENCES `tbsubfactor` (`idtbSubFactor`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tbquestiontext`
--
ALTER TABLE `tbquestiontext`
ADD CONSTRAINT `fk_tbQuestionText_tbLanguage1` FOREIGN KEY (`tbLanguage_idtbLanguage`) REFERENCES `tblanguage` (`idtbLanguage`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_tbQuestionText_tbQuestionId1` FOREIGN KEY (`tbQuestionId_idtbQuestionId`) REFERENCES `tbquestionid` (`idtbQuestionId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tbsubfactortext`
--
ALTER TABLE `tbsubfactortext`
ADD CONSTRAINT `fk_tbSubFactor_has_tbLanguage_tbLanguage1` FOREIGN KEY (`tbLanguage_idtbLanguage`) REFERENCES `tblanguage` (`idtbLanguage`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_tbSubFactor_has_tbLanguage_tbSubFactor1` FOREIGN KEY (`tbSubFactor_idtbSubFactor`) REFERENCES `tbsubfactor` (`idtbSubFactor`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tbuserprofile`
--
ALTER TABLE `tbuserprofile`
ADD CONSTRAINT `fk_tbUserProfile_tbUser1` FOREIGN KEY (`tbUser_idtbUser`) REFERENCES `tbuser` (`idtbUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tbusertypetext`
--
ALTER TABLE `tbusertypetext`
ADD CONSTRAINT `fk_tbUserType_has_tbLanguage_tbLanguage1` FOREIGN KEY (`tbLanguage_idtbLanguage`) REFERENCES `tblanguage` (`idtbLanguage`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_tbUserType_has_tbLanguage_tbUserType1` FOREIGN KEY (`tbUserType_idtbUserType`) REFERENCES `tbusertype` (`idtbUserType`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tbusertype_has_tbuserquestion`
--
ALTER TABLE `tbusertype_has_tbuserquestion`
ADD CONSTRAINT `fk_tbUserType_has_tbUserQuestion_tbQuestionId1` FOREIGN KEY (`tbQuestionId_idtbQuestionId`) REFERENCES `tbquestionid` (`idtbQuestionId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_tbUserType_has_tbUserQuestion_tbUserType1` FOREIGN KEY (`tbUserType_idtbUserType`) REFERENCES `tbusertype` (`idtbUserType`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;