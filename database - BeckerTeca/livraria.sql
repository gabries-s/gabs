-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           5.7.40 - MySQL Community Server (GPL)
-- OS do Servidor:               Win32
-- HeidiSQL Versão:              12.6.0.6765
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Copiando dados para a tabela livraria.avaliacoes: 0 rows
/*!40000 ALTER TABLE `avaliacoes` DISABLE KEYS */;
/*!40000 ALTER TABLE `avaliacoes` ENABLE KEYS */;

-- Copiando dados para a tabela livraria.categorias: 142 rows
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` (`id`, `nome_pt`) VALUES
	(1, 'Ficção'),
	(2, 'Romance'),
	(3, 'Mistério'),
	(4, 'Fantasia'),
	(5, 'Aventura'),
	(6, 'Terror'),
	(7, 'História'),
	(8, 'Biografia'),
	(9, 'Autoajuda'),
	(10, 'Psicologia'),
	(11, 'Ciência'),
	(12, 'Tecnologia'),
	(13, 'Educação'),
	(14, 'Artes'),
	(15, 'Culinária'),
	(16, 'Saúde'),
	(17, 'Economia'),
	(18, 'Política'),
	(19, 'Religião'),
	(20, 'Filosofia'),
	(21, 'Literatura Brasileira'),
	(22, 'Literatura Clássica'),
	(23, 'Poesia'),
	(24, 'Contos'),
	(25, 'Literatura Infantil'),
	(26, 'Ficção Científica'),
	(27, 'Romance Policial'),
	(28, 'Suspense'),
	(29, 'Drama'),
	(30, 'Arte e Cultura'),
	(31, 'Música'),
	(32, 'Cinema'),
	(33, 'Design'),
	(34, 'Arquitetura'),
	(35, 'Fotografia'),
	(36, 'Poemas'),
	(37, 'Crônicas'),
	(38, 'Dramaturgia'),
	(39, 'Estudos Sociais'),
	(40, 'Ciências Políticas'),
	(41, 'História da Arte'),
	(42, 'Economia Doméstica'),
	(43, 'Gestão'),
	(44, 'Marketing'),
	(45, 'Empreendedorismo'),
	(46, 'Computação'),
	(47, 'Inteligência Artificial'),
	(48, 'Programação'),
	(49, 'Redes Sociais'),
	(50, 'Matemática'),
	(51, 'Astronomia'),
	(52, 'Biologia'),
	(53, 'Química'),
	(54, 'Física'),
	(55, 'Geografia'),
	(56, 'Sociologia'),
	(57, 'Antropologia'),
	(58, 'Linguística'),
	(59, 'Literatura Inglesa'),
	(60, 'Literatura Espanhola'),
	(61, 'Literatura Francesa'),
	(62, 'Literatura Alemã'),
	(63, 'Poesia Moderna'),
	(64, 'Letras'),
	(65, 'Teatro'),
	(66, 'Estudos Culturais'),
	(67, 'Estudos Literários'),
	(68, 'Cultura Popular'),
	(69, 'Mídia'),
	(70, 'Jornalismo'),
	(71, 'Publicidade'),
	(72, 'Cultura Digital'),
	(73, 'Design Gráfico'),
	(74, 'Desenvolvimento Pessoal'),
	(75, 'Gestão de Pessoas'),
	(76, 'Gestão Financeira'),
	(77, 'Relações Públicas'),
	(78, 'Liderança'),
	(79, 'Gestão de Projetos'),
	(80, 'Mindfulness'),
	(81, 'Coaching'),
	(82, 'Motivação'),
	(83, 'Bem-estar'),
	(84, 'Saúde Mental'),
	(85, 'Nutrição'),
	(86, 'Yoga'),
	(87, 'Meditação'),
	(88, 'Espiritualidade'),
	(89, 'Autoconhecimento'),
	(90, 'Relações Interpessoais'),
	(91, 'Psicoterapia'),
	(92, 'Psicologia Positiva'),
	(93, 'Neurociência'),
	(94, 'Psicologia Social'),
	(95, 'Psicologia Organizacional'),
	(96, 'Psicologia Infantil'),
	(97, 'Psicologia Cognitiva'),
	(98, 'Psicologia do Desenvolvimento'),
	(99, 'Estudos de Gênero'),
	(100, 'Feminismo'),
	(101, 'Diversidade Cultural'),
	(102, 'Direitos Humanos'),
	(103, 'Movimentos Sociais'),
	(104, 'Cidadania'),
	(105, 'Futebol'),
	(106, 'Esportes'),
	(107, 'Moda'),
	(108, 'Beleza'),
	(109, 'Design de Interiores'),
	(110, 'Habilidades Sociais'),
	(111, 'Empoderamento'),
	(112, 'Psicologia do Trabalho'),
	(113, 'Desafios Pessoais'),
	(114, 'Relações Familiares'),
	(115, 'Cidades Sustentáveis'),
	(116, 'Histórias de Vida'),
	(117, 'Sustentabilidade'),
	(118, 'Ecologia'),
	(119, 'Energias Renováveis'),
	(120, 'Cultura Brasileira'),
	(121, 'Cultura Regional'),
	(122, 'Turismo'),
	(123, 'Viagens'),
	(124, 'História Antiga'),
	(125, 'História Medieval'),
	(126, 'História Moderna'),
	(127, 'História Contemporânea'),
	(128, 'Saúde Pública'),
	(129, 'Enfermagem'),
	(130, 'Medicina'),
	(131, 'Fisioterapia'),
	(132, 'Anatomia'),
	(133, 'Educação Física'),
	(134, 'Odontologia'),
	(135, 'Psicopedagogia'),
	(136, 'Nutrição Esportiva'),
	(137, 'Genética'),
	(138, 'Genômica'),
	(139, 'Neurociência Cognitiva'),
	(140, 'Comportamento Humano'),
	(141, 'Terapias Alternativas'),
	(142, 'Psicologia Forense');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;

-- Copiando dados para a tabela livraria.configuracoes: 1 rows
/*!40000 ALTER TABLE `configuracoes` DISABLE KEYS */;
INSERT INTO `configuracoes` (`id`, `titulo_site`, `idioma`, `adm_login`, `adm_senha`, `habilitar_categorias`, `habilitar_livros`, `habilitar_logins`, `habilitar_manutencao`, `habilitar_avaliacao`, `habilitar_botao_comprar`, `habilitar_botao_emprestar`) VALUES
	(1, 'BeckerTeca - Livraria', 'ptbr', 'admin', 'admin', 1, 1, 1, 0, 1, 0, 1);
/*!40000 ALTER TABLE `configuracoes` ENABLE KEYS */;

-- Copiando dados para a tabela livraria.emprestados: 0 rows
/*!40000 ALTER TABLE `emprestados` DISABLE KEYS */;
/*!40000 ALTER TABLE `emprestados` ENABLE KEYS */;

-- Copiando dados para a tabela livraria.livros: 0 rows
/*!40000 ALTER TABLE `livros` DISABLE KEYS */;
/*!40000 ALTER TABLE `livros` ENABLE KEYS */;

-- Copiando dados para a tabela livraria.users: 1 rows
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `ativo`, `adm`, `nome`, `login`, `senha`, `codigo`, `idade`, `perfil_img`, `data_nascimento`, `cpf`, `rg`) VALUES
	(1, 1, 1, 'Administrador', 'admin', 'admin2024@', '4619573', '0', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTsPGtUdlUIuWthwagsnGkxSIe9JXi6dXlJb9hCKdKBvpZwSX6Tz2cXkLQ_jiRkqb-QRM8&usqp=CAU', '00/00/00', '0', 'ADMADM');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
