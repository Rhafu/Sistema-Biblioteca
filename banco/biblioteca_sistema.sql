-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04-Fev-2023 às 18:48
-- Versão do servidor: 10.4.18-MariaDB
-- versão do PHP: 8.0.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `biblioteca_sistema`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `alunos`
--

CREATE TABLE `alunos` (
  `nomeAluno` varchar(100) NOT NULL,
  `emailAluno` varchar(100) NOT NULL,
  `senhaAluno` varchar(255) NOT NULL,
  `telefoneAluno` varchar(50) NOT NULL,
  `raAluno` varchar(11) NOT NULL,
  `fotoAluno` varchar(255) NOT NULL,
  `dataNascAluno` date NOT NULL,
  `tokenAluno` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `autor`
--

CREATE TABLE `autor` (
  `codigoAutor` int(11) NOT NULL,
  `nomeAutor` varchar(40) DEFAULT NULL,
  `dataNascAutor` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `bibliotecarios`
--

CREATE TABLE `bibliotecarios` (
  `nomeBibliotecario` varchar(100) NOT NULL,
  `emailBibliotecario` varchar(100) NOT NULL,
  `senhaBibliotecario` varchar(255) NOT NULL,
  `telefoneBibliotecario` varchar(50) NOT NULL,
  `cpfBibliotecario` varchar(11) NOT NULL,
  `fotoBibliotecario` varchar(255) NOT NULL,
  `dataNascBibliotecario` date NOT NULL,
  `tokenBibliotecario` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `bibliotecarios`
--

INSERT INTO `bibliotecarios` (`nomeBibliotecario`, `emailBibliotecario`, `senhaBibliotecario`, `telefoneBibliotecario`, `cpfBibliotecario`, `fotoBibliotecario`, `dataNascBibliotecario`, `tokenBibliotecario`) VALUES
('Rafael Mattos', 'rhafu29@gmail.com', '$2y$10$3QIuBMHYdOABTm38DX8iSeARpr1SJRxqUTntavJOAiHPZV3IQbdA2', '55189916369', '79999999999', 'a1e3c5b4b12edf355c93a674d51765e91.PNG', '2021-11-26', '725e1fb844a1491c3e744b178d6f26ff'),
('Alexandre Marcelino', 'alexandre@gmail.com', '$2y$10$vBApEf0qsJMZ8R86f1I8Ae/rpFfMRq82LIkwFKxQ5w6JigOg.P7uO', '(19) 99999-9999', '75315948625', '69899428fe0fd1bb5bd953fa0c036db4Xandex.png', '2004-01-15', 'fd240b6b8042b0456e8f2d45ff3ce5c6');

-- --------------------------------------------------------

--
-- Estrutura da tabela `editora`
--

CREATE TABLE `editora` (
  `codigoEditora` int(11) NOT NULL,
  `nomeEditora` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `genero`
--

CREATE TABLE `genero` (
  `codigoGenero` int(11) NOT NULL,
  `nomeGenero` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `idioma`
--

CREATE TABLE `idioma` (
  `codigoIdioma` int(11) NOT NULL,
  `nomeIdioma` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `livros`
--

CREATE TABLE `livros` (
  `codigoLivro` int(11) NOT NULL,
  `nomeLivro` varchar(255) NOT NULL,
  `nroPagLivro` int(11) NOT NULL,
  `dataPublicacaoLivro` date NOT NULL,
  `editoraLivro` int(11) NOT NULL,
  `generoLivro` int(11) NOT NULL,
  `idiomaLivro` int(11) NOT NULL,
  `quantidadeLivro` int(11) NOT NULL,
  `imagemLivro` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `locacoes`
--

CREATE TABLE `locacoes` (
  `codigoLocacao` int(11) NOT NULL,
  `codigoLivro` int(11) NOT NULL,
  `raAluno` varchar(11) NOT NULL,
  `dataInicio` date NOT NULL,
  `dataTermino` date NOT NULL,
  `dataRetirada` date DEFAULT NULL,
  `dataDevolucao` date DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `atrasado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `obraautor`
--

CREATE TABLE `obraautor` (
  `codigoLivro` int(11) NOT NULL,
  `codigoAutor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `alunos`
--
ALTER TABLE `alunos`
  ADD PRIMARY KEY (`raAluno`),
  ADD UNIQUE KEY `emailAluno` (`emailAluno`);

--
-- Índices para tabela `autor`
--
ALTER TABLE `autor`
  ADD PRIMARY KEY (`codigoAutor`);

--
-- Índices para tabela `bibliotecarios`
--
ALTER TABLE `bibliotecarios`
  ADD PRIMARY KEY (`cpfBibliotecario`),
  ADD UNIQUE KEY `emailBibliotecario` (`emailBibliotecario`);

--
-- Índices para tabela `editora`
--
ALTER TABLE `editora`
  ADD PRIMARY KEY (`codigoEditora`);

--
-- Índices para tabela `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`codigoGenero`);

--
-- Índices para tabela `idioma`
--
ALTER TABLE `idioma`
  ADD PRIMARY KEY (`codigoIdioma`);

--
-- Índices para tabela `livros`
--
ALTER TABLE `livros`
  ADD PRIMARY KEY (`codigoLivro`),
  ADD UNIQUE KEY `imagemLivro` (`imagemLivro`),
  ADD KEY `FK_LIVROS_EDITORA` (`editoraLivro`),
  ADD KEY `FK_LIVROS_GENERO` (`generoLivro`),
  ADD KEY `FK_LIVROS_IDIOMA` (`idiomaLivro`);

--
-- Índices para tabela `locacoes`
--
ALTER TABLE `locacoes`
  ADD PRIMARY KEY (`codigoLocacao`);

--
-- Índices para tabela `obraautor`
--
ALTER TABLE `obraautor`
  ADD PRIMARY KEY (`codigoAutor`,`codigoLivro`),
  ADD KEY `FK_OBRAAUTOR_LIVRO` (`codigoLivro`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `locacoes`
--
ALTER TABLE `locacoes`
  MODIFY `codigoLocacao` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `livros`
--
ALTER TABLE `livros`
  ADD CONSTRAINT `FK_LIVROS_EDITORA` FOREIGN KEY (`editoraLivro`) REFERENCES `editora` (`codigoEditora`),
  ADD CONSTRAINT `FK_LIVROS_GENERO` FOREIGN KEY (`generoLivro`) REFERENCES `genero` (`codigoGenero`),
  ADD CONSTRAINT `FK_LIVROS_IDIOMA` FOREIGN KEY (`idiomaLivro`) REFERENCES `idioma` (`codigoIdioma`);

--
-- Limitadores para a tabela `obraautor`
--
ALTER TABLE `obraautor`
  ADD CONSTRAINT `FK_OBRAAUTOR_AUTOR` FOREIGN KEY (`codigoAutor`) REFERENCES `autor` (`codigoAutor`),
  ADD CONSTRAINT `FK_OBRAAUTOR_LIVRO` FOREIGN KEY (`codigoLivro`) REFERENCES `livros` (`codigoLivro`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
