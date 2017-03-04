CREATE TABLE `cpf` (
  `cpf` varchar(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `telefone` varchar(100) default NULL,
  `cidade` varchar(100) default NULL,
  `situacao` tinyint(1) NOT NULL,
  PRIMARY KEY  (`cpf`)
);