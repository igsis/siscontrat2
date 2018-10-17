-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema siscontrat
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema siscontrat
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `siscontrat` DEFAULT CHARACTER SET utf8 ;
USE `siscontrat` ;

-- -----------------------------------------------------
-- Table `siscontrat`.`bancos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`bancos` (
  `id` SMALLINT(3) NOT NULL AUTO_INCREMENT COMMENT 'index',
  `banco` VARCHAR(60) NOT NULL COMMENT 'index',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `siscontrat`.`etnias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`etnias` (
  `id` TINYINT(1) NOT NULL AUTO_INCREMENT COMMENT 'index',
  `descricao` VARCHAR(15) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `siscontrat`.`nacionalidades`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`nacionalidades` (
  `id` SMALLINT(4) NOT NULL AUTO_INCREMENT COMMENT '',
  `nacionalidade` VARCHAR(20) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`pessoa_fisicas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`pessoa_fisicas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `nome` VARCHAR(70) NOT NULL COMMENT 'index',
  `nome_artistico` VARCHAR(70) NOT NULL COMMENT '',
  `rg` VARCHAR(20) NOT NULL COMMENT '',
  `passaporte` VARCHAR(10) NULL DEFAULT '' COMMENT '',
  `cpf` CHAR(14) NULL DEFAULT '' COMMENT '',
  `ccm` CHAR(11) NULL DEFAULT '' COMMENT '',
  `data_nascimento` DATE NOT NULL COMMENT '',
  `nacionalidade_id` SMALLINT(4) NULL COMMENT '',
  `email` VARCHAR(60) NOT NULL COMMENT '',
  `ultima_atualizacao` DATE NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `rg_UNIQUE` (`rg` ASC)  COMMENT '',
  INDEX `fk_pessoa_fisicas_nacionalidades1_idx` (`nacionalidade_id` ASC)  COMMENT '',
  CONSTRAINT `fk_pessoa_fisicas_nacionalidades`
    FOREIGN KEY (`nacionalidade_id`)
    REFERENCES `siscontrat`.`nacionalidades` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `siscontrat`.`regiaos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`regiaos` (
  `id` TINYINT(2) NOT NULL AUTO_INCREMENT COMMENT '',
  `nome` VARCHAR(40) NOT NULL COMMENT 'index',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `siscontrat`.`grau_instrucoes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`grau_instrucoes` (
  `id` TINYINT(1) NOT NULL AUTO_INCREMENT COMMENT '',
  `grau_instrucao` VARCHAR(15) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`pf_detalhes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`pf_detalhes` (
  `pessoa_fisica_id` INT(11) NOT NULL COMMENT 'index',
  `etnia_id` TINYINT(1) NOT NULL COMMENT '',
  `regiao_id` TINYINT(2) NOT NULL COMMENT '',
  `grau_instrucao_id` TINYINT(1) NOT NULL COMMENT '',
  `curriculo` LONGTEXT NOT NULL COMMENT '',
  INDEX `fk_detalhesPessoaFisica_pessoaFisica1_idx` (`pessoa_fisica_id` ASC)  COMMENT '',
  INDEX `fk_detalhesPessoaFisica_etnias1_idx` (`etnia_id` ASC)  COMMENT '',
  INDEX `fk_detalhesPessoaFisica_regioes1_idx` (`regiao_id` ASC)  COMMENT '',
  PRIMARY KEY (`pessoa_fisica_id`)  COMMENT '',
  INDEX `fk_pessoa_fisica_detalhes_grau_instrucao_idx` (`grau_instrucao_id` ASC)  COMMENT '',
  CONSTRAINT `fk_detalhesPessoaFisica_etnias1`
    FOREIGN KEY (`etnia_id`)
    REFERENCES `siscontrat`.`etnias` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalhesPessoaFisica_pessoaFisica1`
    FOREIGN KEY (`pessoa_fisica_id`)
    REFERENCES `siscontrat`.`pessoa_fisicas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalhesPessoaFisica_regioes1`
    FOREIGN KEY (`regiao_id`)
    REFERENCES `siscontrat`.`regiaos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pessoa_fisica_detalhes_grau_instrucao`
    FOREIGN KEY (`grau_instrucao_id`)
    REFERENCES `siscontrat`.`grau_instrucoes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `siscontrat`.`drts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`drts` (
  `pessoa_fisica_id` INT NOT NULL COMMENT '',
  `drt` VARCHAR(15) NOT NULL COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  PRIMARY KEY (`pessoa_fisica_id`)  COMMENT '',
  CONSTRAINT `fk_drt_pessoa_fisica`
    FOREIGN KEY (`pessoa_fisica_id`)
    REFERENCES `siscontrat`.`pessoa_fisicas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `siscontrat`.`nits`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`nits` (
  `pessoa_fisica_id` INT NOT NULL COMMENT '',
  `nit` VARCHAR(45) NOT NULL COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  PRIMARY KEY (`pessoa_fisica_id`)  COMMENT '',
  CONSTRAINT `fk_nit_pessoa_fisica`
    FOREIGN KEY (`pessoa_fisica_id`)
    REFERENCES `siscontrat`.`pessoa_fisicas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `siscontrat`.`ombs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`ombs` (
  `pessoa_fisica_id` INT NOT NULL COMMENT '',
  `omb` VARCHAR(25) NOT NULL COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  PRIMARY KEY (`pessoa_fisica_id`)  COMMENT '',
  CONSTRAINT `fk_omb_pessoa_fisica`
    FOREIGN KEY (`pessoa_fisica_id`)
    REFERENCES `siscontrat`.`pessoa_fisicas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `siscontrat`.`pf_bancos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`pf_bancos` (
  `pessoa_fisica_id` INT NOT NULL COMMENT '',
  `banco_id` SMALLINT(3) NOT NULL COMMENT '',
  `agencia` VARCHAR(12) NOT NULL COMMENT '',
  `conta` VARCHAR(12) NOT NULL COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  PRIMARY KEY (`pessoa_fisica_id`)  COMMENT '',
  INDEX `fk_pf_bancos_bancos1_idx` (`banco_id` ASC)  COMMENT '',
  CONSTRAINT `fk_pessoa_fisica_bancos_pf`
    FOREIGN KEY (`pessoa_fisica_id`)
    REFERENCES `siscontrat`.`pessoa_fisicas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pf_bancos_bancos`
    FOREIGN KEY (`banco_id`)
    REFERENCES `siscontrat`.`bancos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `siscontrat`.`pf_enderecos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`pf_enderecos` (
  `pessoa_fisica_id` INT NOT NULL COMMENT '',
  `logradouro` VARCHAR(200) NOT NULL COMMENT '',
  `numero` INT(5) NOT NULL COMMENT '',
  `complemento` VARCHAR(20) NULL DEFAULT '' COMMENT '',
  `bairro` VARCHAR(80) NOT NULL COMMENT '',
  `cidade` VARCHAR(50) NOT NULL COMMENT '',
  `uf` VARCHAR(2) NOT NULL COMMENT '',
  `cep` CHAR(9) NOT NULL COMMENT '',
  PRIMARY KEY (`pessoa_fisica_id`)  COMMENT '',
  CONSTRAINT `pessoa_fisica_enderecos_pf`
    FOREIGN KEY (`pessoa_fisica_id`)
    REFERENCES `siscontrat`.`pessoa_fisicas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `siscontrat`.`pf_observacoes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`pf_observacoes` (
  `pessoa_fisica_id` INT NOT NULL COMMENT '',
  `observacao` LONGTEXT NOT NULL COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  PRIMARY KEY (`pessoa_fisica_id`)  COMMENT '',
  CONSTRAINT `fk_pessoa_fisica_observacao`
    FOREIGN KEY (`pessoa_fisica_id`)
    REFERENCES `siscontrat`.`pessoa_fisicas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `siscontrat`.`pf_telefones`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`pf_telefones` (
  `pessoa_fisica_id` INT NOT NULL COMMENT '',
  `telefone` VARCHAR(15) NOT NULL COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  CONSTRAINT `fk_pessoa_fisica_telefones`
    FOREIGN KEY (`pessoa_fisica_id`)
    REFERENCES `siscontrat`.`pessoa_fisicas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `siscontrat`.`territorios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`territorios` (
  `id` TINYINT(2) NOT NULL AUTO_INCREMENT COMMENT '',
  `territorio` VARCHAR(15) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`coordenadorias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`coordenadorias` (
  `id` TINYINT(2) NOT NULL AUTO_INCREMENT COMMENT '',
  `coordenadoria` VARCHAR(25) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`subprefeituras`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`subprefeituras` (
  `id` TINYINT(2) NOT NULL AUTO_INCREMENT COMMENT '',
  `subprefeitura` VARCHAR(55) NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`programas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`programas` (
  `id` TINYINT(2) NOT NULL AUTO_INCREMENT COMMENT '',
  `programa` VARCHAR(45) NOT NULL COMMENT '',
  `verba_id` INT(5) NOT NULL COMMENT '',
  `edital` VARCHAR(40) NOT NULL COMMENT '',
  `descricao` LONGTEXT NOT NULL COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`linguagens`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`linguagens` (
  `id` TINYINT(2) NOT NULL AUTO_INCREMENT COMMENT '',
  `linguagem` VARCHAR(20) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`projetos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`projetos` (
  `id` TINYINT(2) NOT NULL AUTO_INCREMENT COMMENT '',
  `projeto` VARCHAR(25) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`formacao_cargos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`formacao_cargos` (
  `id` TINYINT(2) NOT NULL AUTO_INCREMENT COMMENT '',
  `cargo` VARCHAR(70) NOT NULL COMMENT '',
  `justificativa` LONGTEXT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`formacao_vigencias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`formacao_vigencias` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `ano` SMALLINT(4) NOT NULL COMMENT '',
  `descricao` VARCHAR(45) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`formacao_status`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`formacao_status` (
  `id` TINYINT(1) NOT NULL AUTO_INCREMENT COMMENT '',
  `status` VARCHAR(15) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`perfis`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`perfis` (
  `id` TINYINT(2) NOT NULL AUTO_INCREMENT COMMENT '',
  `descricao` VARCHAR(30) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`usuarios` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `nome_completo` VARCHAR(70) NOT NULL COMMENT '',
  `usuario` CHAR(7) NOT NULL COMMENT '',
  `senha` VARCHAR(90) NOT NULL COMMENT '',
  `email` VARCHAR(60) NOT NULL COMMENT '',
  `telefone` VARCHAR(15) NOT NULL COMMENT '',
  `perfil_id` TINYINT(2) NOT NULL COMMENT '',
  `fiscal` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '',
  `data_cadastro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '',
  `ultimo_acesso` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_usuarios_perfil1_idx` (`perfil_id` ASC)  COMMENT '',
  CONSTRAINT `fk_usuarios_perfil`
    FOREIGN KEY (`perfil_id`)
    REFERENCES `siscontrat`.`perfis` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`origem_tipos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`origem_tipos` (
  `id` TINYINT(1) NOT NULL AUTO_INCREMENT COMMENT '',
  `origem` VARCHAR(10) NOT NULL COMMENT '1 = Evento\n2 = Formação\n3 = EMIA',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`pessoa_tipos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`pessoa_tipos` (
  `id` TINYINT(1) NOT NULL AUTO_INCREMENT COMMENT '',
  `pessoa` VARCHAR(8) NOT NULL COMMENT '1 = física\n2 = jurídica',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`verbas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`verbas` (
  `id` SMALLINT(3) NOT NULL AUTO_INCREMENT COMMENT '',
  `verba` VARCHAR(80) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`pedido_status`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`pedido_status` (
  `id` TINYINT(2) NOT NULL AUTO_INCREMENT COMMENT '',
  `status` VARCHAR(50) NOT NULL COMMENT '',
  `ordem` TINYINT(2) NOT NULL COMMENT '',
  `area` TINYINT(1) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`emia_cargos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`emia_cargos` (
  `id` TINYINT(2) NOT NULL AUTO_INCREMENT COMMENT '',
  `cargo` VARCHAR(70) NOT NULL COMMENT '',
  `justificativa` LONGTEXT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`emia_vigencias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`emia_vigencias` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `ano` SMALLINT(4) NOT NULL COMMENT '',
  `descricao` VARCHAR(45) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`emia_status`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`emia_status` (
  `id` TINYINT(1) NOT NULL AUTO_INCREMENT COMMENT '',
  `status` VARCHAR(15) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`instituicoes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`instituicoes` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `nome` VARCHAR(60) NOT NULL COMMENT '',
  `sigla` VARCHAR(8) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`locais`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`locais` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `instituicao_id` INT NOT NULL COMMENT '',
  `local` VARCHAR(100) NOT NULL COMMENT '',
  `logradouro` VARCHAR(200) NOT NULL COMMENT '',
  `numero` INT(5) NOT NULL COMMENT '',
  `complemento` VARCHAR(20) NULL DEFAULT '' COMMENT '',
  `bairro` VARCHAR(80) NOT NULL COMMENT '',
  `cidade` VARCHAR(50) NOT NULL COMMENT '',
  `uf` VARCHAR(2) NOT NULL COMMENT '',
  `cep` CHAR(9) NOT NULL COMMENT '',
  `rider` VARCHAR(250) NULL DEFAULT '' COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_local_instituicao1_idx` (`instituicao_id` ASC)  COMMENT '',
  CONSTRAINT `fk_local_instituicao`
    FOREIGN KEY (`instituicao_id`)
    REFERENCES `siscontrat`.`instituicoes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `siscontrat`.`emia_contratacao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`emia_contratacao` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `pessoa_fisica_id` INT NOT NULL COMMENT '',
  `ano` SMALLINT(4) NOT NULL COMMENT '',
  `emia_status_id` TINYINT(1) NOT NULL COMMENT '',
  `local_id` INT NOT NULL COMMENT '',
  `emia_cargo_id` TINYINT(2) NOT NULL COMMENT '',
  `emia_vigencia_id` INT NOT NULL COMMENT '',
  `cronograma` LONGTEXT NULL COMMENT '',
  `observacao` VARCHAR(255) NULL DEFAULT '' COMMENT '',
  `pedido_id` INT NULL COMMENT '',
  `fiscal_id` INT NULL COMMENT '',
  `suplente_id` INT NULL COMMENT '',
  `num_processo_pagto` CHAR(19) NULL DEFAULT '' COMMENT '',
  `usuario_id` INT NOT NULL COMMENT '',
  `data_envio` DATETIME NOT NULL COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_form_pre_pedidos_pf_idx` (`pessoa_fisica_id` ASC)  COMMENT '',
  INDEX `fk_emia_pre_pedidos_form_cargos_idx` (`emia_cargo_id` ASC)  COMMENT '',
  INDEX `fk_emia_pre_pedidos_form_idx` (`emia_status_id` ASC)  COMMENT '',
  INDEX `fk_emia_pre_pedidos_emia_vigencias_idx` (`emia_vigencia_id` ASC)  COMMENT '',
  INDEX `fk_emia_pre_pedidos_emia_local_idx` (`local_id` ASC)  COMMENT '',
  INDEX `fk_emia_pre_pedidos_emia_usuario_idx` (`usuario_id` ASC)  COMMENT '',
  CONSTRAINT `fk_emia_pre_pedidos_pf`
    FOREIGN KEY (`pessoa_fisica_id`)
    REFERENCES `siscontrat`.`pessoa_fisicas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_emia_pre_pedidos_emia_cargos`
    FOREIGN KEY (`emia_cargo_id`)
    REFERENCES `siscontrat`.`emia_cargos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_emia_pre_pedidos_emia_vigencias`
    FOREIGN KEY (`emia_vigencia_id`)
    REFERENCES `siscontrat`.`emia_vigencias` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_emia_pre_pedidos_emia_status`
    FOREIGN KEY (`emia_status_id`)
    REFERENCES `siscontrat`.`emia_status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_emia_pre_pedidos_emia_local`
    FOREIGN KEY (`local_id`)
    REFERENCES `siscontrat`.`locais` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_emia_pre_pedidos_emia_usuario`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `siscontrat`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`representante_legais`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`representante_legais` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `nome` VARCHAR(70) NOT NULL COMMENT '',
  `rg` VARCHAR(20) NOT NULL COMMENT '',
  `cpf` CHAR(14) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`pessoa_juridicas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`pessoa_juridicas` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `razao_social` VARCHAR(100) NOT NULL COMMENT '',
  `cnpj` CHAR(18) NOT NULL COMMENT '',
  `ccm` VARCHAR(45) NULL DEFAULT '' COMMENT '',
  `email` VARCHAR(60) NOT NULL COMMENT '',
  `representante_legal1_id` INT NOT NULL COMMENT '',
  `representante_legal2_id` INT NULL DEFAULT 0 COMMENT '',
  `ultima_atualizacao` DATE NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  UNIQUE INDEX `cnpj_UNIQUE` (`cnpj` ASC)  COMMENT '',
  INDEX `fk_pj_representante_legal1_idx` (`representante_legal1_id` ASC)  COMMENT '',
  INDEX `fk_pj_representante_legal2_idx` (`representante_legal2_id` ASC)  COMMENT '',
  CONSTRAINT `fk_pj_representante_legal1`
    FOREIGN KEY (`representante_legal1_id`)
    REFERENCES `siscontrat`.`representante_legais` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pj_representante_legal2`
    FOREIGN KEY (`representante_legal2_id`)
    REFERENCES `siscontrat`.`representante_legais` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`pedidos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`pedidos` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `origem_tipo_id` TINYINT(1) NOT NULL COMMENT '',
  `origem_id` INT NOT NULL COMMENT '',
  `pessoa_tipo_id` TINYINT(1) NOT NULL COMMENT '',
  `pessoa_juridica_id` INT NOT NULL COMMENT '',
  `pessoa_fisica_id` INT NOT NULL COMMENT '',
  `verba_id` SMALLINT(3) NOT NULL COMMENT '',
  `numero_parcelas` TINYINT(2) NOT NULL COMMENT '',
  `valor_total` DECIMAL(7,2) NOT NULL COMMENT '',
  `forma_pagamento` LONGTEXT NOT NULL COMMENT '',
  `data_kit_pagamento` DATE NOT NULL COMMENT '',
  `justificativa` LONGTEXT NOT NULL COMMENT '',
  `status_pedido_id` TINYINT(2) NOT NULL COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_pedidos_origem_tipos_idx` (`origem_tipo_id` ASC)  COMMENT '',
  INDEX `fk_pedidos_pessoa_tipos_idx` (`pessoa_tipo_id` ASC)  COMMENT '',
  INDEX `fk_pedidos_verbas_idx` (`verba_id` ASC)  COMMENT '',
  INDEX `fk_pedidos_status_pedidos_idx` (`status_pedido_id` ASC)  COMMENT '',
  INDEX `fk_pedidos_emia_pre_pedidos_idx` (`origem_id` ASC)  COMMENT '',
  INDEX `fk_pedidos_pessoa_juridicas_idx` (`pessoa_juridica_id` ASC)  COMMENT '',
  INDEX `fk_pedidos_pessoa_fisicas_idx` (`pessoa_fisica_id` ASC)  COMMENT '',
  CONSTRAINT `fk_pedidos_origem_tipos`
    FOREIGN KEY (`origem_tipo_id`)
    REFERENCES `siscontrat`.`origem_tipos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedidos_pessoa_tipos`
    FOREIGN KEY (`pessoa_tipo_id`)
    REFERENCES `siscontrat`.`pessoa_tipos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedidos_verbas`
    FOREIGN KEY (`verba_id`)
    REFERENCES `siscontrat`.`verbas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedidos_status_pedidos`
    FOREIGN KEY (`status_pedido_id`)
    REFERENCES `siscontrat`.`pedido_status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedidos_emia_pre_pedidos`
    FOREIGN KEY (`origem_id`)
    REFERENCES `siscontrat`.`emia_contratacao` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedidos_form_pre_pedidos`
    FOREIGN KEY (`origem_id`)
    REFERENCES `siscontrat`.`formacao_contratacoes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedidos_pessoa_juridicas`
    FOREIGN KEY (`pessoa_juridica_id`)
    REFERENCES `siscontrat`.`pessoa_juridicas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedidos_pessoa_fisicas`
    FOREIGN KEY (`pessoa_fisica_id`)
    REFERENCES `siscontrat`.`pessoa_fisicas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`formacao_contratacoes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`formacao_contratacoes` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `pessoa_fisica_id` INT NOT NULL COMMENT '',
  `ano` SMALLINT(4) NOT NULL COMMENT '',
  `form_status_id` TINYINT(1) NOT NULL COMMENT '',
  `chamado` TINYINT(2) NULL DEFAULT 0 COMMENT '',
  `classificacao` SMALLINT(4) NULL DEFAULT 0 COMMENT '',
  `territorio_id` TINYINT(2) NOT NULL COMMENT '',
  `coordenadoria_id` TINYINT(2) NOT NULL COMMENT '',
  `subprefeitura_id` TINYINT(2) NOT NULL COMMENT '',
  `programa_id` TINYINT(2) NOT NULL COMMENT '',
  `linguagem_id` TINYINT(2) NOT NULL COMMENT '',
  `projeto_id` TINYINT(2) NOT NULL COMMENT '',
  `form_cargo_id` TINYINT(2) NOT NULL COMMENT '',
  `form_vigencia_id` INT NOT NULL COMMENT '',
  `observacao` VARCHAR(255) NULL DEFAULT '' COMMENT '',
  `pedido_id` INT NULL COMMENT '',
  `fiscal_id` INT NULL COMMENT '',
  `suplente_id` INT NULL COMMENT '',
  `num_processo_pagto` CHAR(19) NULL DEFAULT '' COMMENT '',
  `usuario_id` INT NOT NULL COMMENT '',
  `data_envio` DATETIME NOT NULL COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  `formacao_contratacoes` VARCHAR(45) NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_form_pre_pedidos_pf_idx` (`pessoa_fisica_id` ASC)  COMMENT '',
  INDEX `form_pre_pedidos_territorios_idx` (`territorio_id` ASC)  COMMENT '',
  INDEX `fk_form_pre_pedidos_coordenadorias_idx` (`coordenadoria_id` ASC)  COMMENT '',
  INDEX `fk_form_pre_pedidos_subprefeituras_idx` (`subprefeitura_id` ASC)  COMMENT '',
  INDEX `fk_form_pre_pedidos_programas_idx` (`programa_id` ASC)  COMMENT '',
  INDEX `fk_form_pre_pedidos_linguagem_idx` (`linguagem_id` ASC)  COMMENT '',
  INDEX `fk_form_pre_pedidos_projetos_idx` (`projeto_id` ASC)  COMMENT '',
  INDEX `fk_form_pre_pedidos_form_cargos_idx` (`form_cargo_id` ASC)  COMMENT '',
  INDEX `fk_form_pre_pedidos_form_vigencias_idx` (`form_vigencia_id` ASC)  COMMENT '',
  INDEX `fk_form_pre_pedidos_form_status_idx` (`form_status_id` ASC)  COMMENT '',
  INDEX `fk_form_pre_pedidos_usuarios_idx` (`usuario_id` ASC)  COMMENT '',
  INDEX `fk_form_pre_pedidos_pedidos1_idx` (`pedido_id` ASC)  COMMENT '',
  CONSTRAINT `fk_form_pre_pedidos_pf`
    FOREIGN KEY (`pessoa_fisica_id`)
    REFERENCES `siscontrat`.`pessoa_fisicas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_form_pre_pedidos_territorios`
    FOREIGN KEY (`territorio_id`)
    REFERENCES `siscontrat`.`territorios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_form_pre_pedidos_coordenadorias`
    FOREIGN KEY (`coordenadoria_id`)
    REFERENCES `siscontrat`.`coordenadorias` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_form_pre_pedidos_subprefeituras`
    FOREIGN KEY (`subprefeitura_id`)
    REFERENCES `siscontrat`.`subprefeituras` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_form_pre_pedidos_programas`
    FOREIGN KEY (`programa_id`)
    REFERENCES `siscontrat`.`programas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_form_pre_pedidos_linguagem`
    FOREIGN KEY (`linguagem_id`)
    REFERENCES `siscontrat`.`linguagens` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_form_pre_pedidos_projetos`
    FOREIGN KEY (`projeto_id`)
    REFERENCES `siscontrat`.`projetos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_form_pre_pedidos_form_cargos`
    FOREIGN KEY (`form_cargo_id`)
    REFERENCES `siscontrat`.`formacao_cargos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_form_pre_pedidos_form_vigencias`
    FOREIGN KEY (`form_vigencia_id`)
    REFERENCES `siscontrat`.`formacao_vigencias` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_form_pre_pedidos_form_status`
    FOREIGN KEY (`form_status_id`)
    REFERENCES `siscontrat`.`formacao_status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_form_pre_pedidos_usuarios`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `siscontrat`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_form_pre_pedidos_pedidos`
    FOREIGN KEY (`pedido_id`)
    REFERENCES `siscontrat`.`pedidos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`formacao_parcelas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`formacao_parcelas` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `formacao_vigencia_id` INT NOT NULL COMMENT '',
  `numero_parcelas` TINYINT(2) NOT NULL COMMENT '',
  `valor` DECIMAL(5,2) NOT NULL COMMENT '',
  `data_inicio` DATE NOT NULL COMMENT '',
  `data_fim` DATE NOT NULL COMMENT '',
  `data_pagamento` DATE NOT NULL COMMENT '',
  `carga_horaria` TINYINT(2) NOT NULL COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_form_parcelas_form_vigencias_idx` (`formacao_vigencia_id` ASC)  COMMENT '',
  CONSTRAINT `fk_form_parcelas_form_vigencias`
    FOREIGN KEY (`formacao_vigencia_id`)
    REFERENCES `siscontrat`.`formacao_vigencias` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`pj_telefones`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`pj_telefones` (
  `pessoa_juridica_id` INT NOT NULL COMMENT '',
  `telefone` VARCHAR(15) NOT NULL COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  CONSTRAINT `fk_pj_telefones_pj`
    FOREIGN KEY (`pessoa_juridica_id`)
    REFERENCES `siscontrat`.`pessoa_juridicas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `siscontrat`.`pj_observacoes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`pj_observacoes` (
  `pessoa_juridica_id` INT NOT NULL COMMENT '',
  `observacao` LONGTEXT NOT NULL COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  PRIMARY KEY (`pessoa_juridica_id`)  COMMENT '',
  CONSTRAINT `fk_pj_observacao_pj`
    FOREIGN KEY (`pessoa_juridica_id`)
    REFERENCES `siscontrat`.`pessoa_juridicas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `siscontrat`.`pj_enderecos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`pj_enderecos` (
  `pessoa_juridica_id` INT NOT NULL COMMENT '',
  `logradouro` VARCHAR(200) NOT NULL COMMENT '',
  `numero` INT(5) NOT NULL COMMENT '',
  `complemento` VARCHAR(20) NULL DEFAULT '' COMMENT '',
  `bairro` VARCHAR(80) NOT NULL COMMENT '',
  `cidade` VARCHAR(50) NOT NULL COMMENT '',
  `uf` VARCHAR(2) NOT NULL COMMENT '',
  `cep` CHAR(9) NOT NULL COMMENT '',
  PRIMARY KEY (`pessoa_juridica_id`)  COMMENT '',
  CONSTRAINT `fk_pj_enderecos_pj`
    FOREIGN KEY (`pessoa_juridica_id`)
    REFERENCES `siscontrat`.`pessoa_juridicas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `siscontrat`.`pj_bancos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`pj_bancos` (
  `pessoa_juridica_id` INT NOT NULL COMMENT '',
  `banco_id` SMALLINT(3) NOT NULL COMMENT '',
  `agencia` VARCHAR(12) NOT NULL COMMENT '',
  `conta` VARCHAR(12) NOT NULL COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  PRIMARY KEY (`pessoa_juridica_id`)  COMMENT '',
  INDEX `fk_pf_bancos_bancos1_idx` (`banco_id` ASC)  COMMENT '',
  CONSTRAINT `fk_pj_bancos_bancos`
    FOREIGN KEY (`banco_id`)
    REFERENCES `siscontrat`.`bancos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pj_bancos_pj`
    FOREIGN KEY (`pessoa_juridica_id`)
    REFERENCES `siscontrat`.`pessoa_juridicas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `siscontrat`.`modulos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`modulos` (
  `id` TINYINT(2) NOT NULL AUTO_INCREMENT COMMENT '',
  `sigla` VARCHAR(4) NOT NULL COMMENT '',
  `descricao` VARCHAR(20) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`modulo_perfis`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`modulo_perfis` (
  `modulo_id` TINYINT(2) NOT NULL COMMENT '',
  `perfil_id` TINYINT(2) NOT NULL COMMENT '',
  INDEX `fk_modulo_perfil_modulos_idx` (`modulo_id` ASC)  COMMENT '',
  PRIMARY KEY (`modulo_id`, `perfil_id`)  COMMENT '',
  CONSTRAINT `fk_modulo_perfil_perfil`
    FOREIGN KEY (`perfil_id`)
    REFERENCES `siscontrat`.`perfis` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_modulo_perfil_modulos`
    FOREIGN KEY (`modulo_id`)
    REFERENCES `siscontrat`.`modulos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`usuario_contratos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`usuario_contratos` (
  `usuario_id` INT NOT NULL COMMENT '',
  `nivel_acesso` TINYINT(1) NOT NULL COMMENT '',
  PRIMARY KEY (`usuario_id`)  COMMENT '',
  CONSTRAINT `fk_usuario_contratos_usuarios`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `siscontrat`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`usuario_pagamentos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`usuario_pagamentos` (
  `usuario_id` INT NOT NULL COMMENT '',
  `nivel_acesso` TINYINT(1) NOT NULL COMMENT '',
  PRIMARY KEY (`usuario_id`)  COMMENT '',
  CONSTRAINT `fk_usuario_pagamentos_usuarios`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `siscontrat`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`espacos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`espacos` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `local_id` INT NOT NULL COMMENT '',
  `espaco` VARCHAR(100) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  CONSTRAINT `fk_espacos_local`
    FOREIGN KEY (`local_id`)
    REFERENCES `siscontrat`.`locais` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`formacao_locais`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`formacao_locais` (
  `form_pre_pedido_id` INT NOT NULL COMMENT '',
  `local_id` INT NOT NULL COMMENT '',
  INDEX `fk_form_local_form_pre_pedidos_idx` (`form_pre_pedido_id` ASC)  COMMENT '',
  INDEX `fk_form_local_local_idx` (`local_id` ASC)  COMMENT '',
  PRIMARY KEY (`form_pre_pedido_id`, `local_id`)  COMMENT '',
  CONSTRAINT `fk_form_local_form_pre_pedidos`
    FOREIGN KEY (`form_pre_pedido_id`)
    REFERENCES `siscontrat`.`formacao_contratacoes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_form_local_local`
    FOREIGN KEY (`local_id`)
    REFERENCES `siscontrat`.`locais` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`local_usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`local_usuarios` (
  `local_id` INT NOT NULL COMMENT '',
  `usuario_id` INT NOT NULL COMMENT '',
  INDEX `fk_local_usuarios_local1_idx` (`local_id` ASC)  COMMENT '',
  INDEX `fk_local_usuarios_usuarios1_idx` (`usuario_id` ASC)  COMMENT '',
  PRIMARY KEY (`local_id`, `usuario_id`)  COMMENT '',
  CONSTRAINT `fk_local_usuarios_local`
    FOREIGN KEY (`local_id`)
    REFERENCES `siscontrat`.`locais` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_local_usuarios_usuarios`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `siscontrat`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`parecer_artisticos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`parecer_artisticos` (
  `pedido_id` INT NOT NULL COMMENT '',
  `topico1` LONGTEXT NULL COMMENT '',
  `topico2` LONGTEXT NULL COMMENT '',
  `topico3` LONGTEXT NULL COMMENT '',
  `topico4` LONGTEXT NULL COMMENT '',
  PRIMARY KEY (`pedido_id`)  COMMENT '',
  CONSTRAINT `fk_parecer_artisticos_pedidos`
    FOREIGN KEY (`pedido_id`)
    REFERENCES `siscontrat`.`pedidos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`pedido_etapas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`pedido_etapas` (
  `pedido_id` INT NOT NULL COMMENT '',
  `data_contrato` DATETIME NOT NULL COMMENT '',
  `data_proposta` DATETIME NOT NULL COMMENT '',
  `data_reserva` DATETIME NOT NULL COMMENT '',
  `data_juridico` DATETIME NOT NULL COMMENT '',
  `data_publicacao` DATETIME NOT NULL COMMENT '',
  `data_contabilidade` DATETIME NOT NULL COMMENT '',
  `data_pagamento` DATETIME NOT NULL COMMENT '',
  PRIMARY KEY (`pedido_id`)  COMMENT '',
  CONSTRAINT `fk_pedido_etapas_pedidos`
    FOREIGN KEY (`pedido_id`)
    REFERENCES `siscontrat`.`pedidos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`penalidades`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`penalidades` (
  `id` TINYINT(2) NOT NULL AUTO_INCREMENT COMMENT '',
  `modelo` VARCHAR(40) NOT NULL COMMENT '',
  `texto` LONGTEXT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`verba_detalhes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`verba_detalhes` (
  `verba_id` SMALLINT(3) NOT NULL COMMENT '',
  `ano` SMALLINT(4) NOT NULL COMMENT '',
  `pf` DECIMAL(7,2) NOT NULL COMMENT '',
  `pj` DECIMAL(7,2) NOT NULL COMMENT '',
  PRIMARY KEY (`verba_id`, `ano`)  COMMENT '',
  CONSTRAINT `fk_verba_detalhes_verbas`
    FOREIGN KEY (`verba_id`)
    REFERENCES `siscontrat`.`verbas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`contratos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`contratos` (
  `pedido_id` INT NOT NULL COMMENT '',
  `pendencia_documentacao` VARCHAR(255) NULL COMMENT '',
  `penalidade_id` TINYINT(2) NULL COMMENT '',
  `numero_processo` CHAR(19) NULL COMMENT '',
  `usuario_contrato_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`pedido_id`)  COMMENT '',
  INDEX `fk_contratos_penalidades1_idx` (`penalidade_id` ASC)  COMMENT '',
  CONSTRAINT `fk_contratos_pedidos1`
    FOREIGN KEY (`pedido_id`)
    REFERENCES `siscontrat`.`pedidos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contratos_penalidades1`
    FOREIGN KEY (`penalidade_id`)
    REFERENCES `siscontrat`.`penalidades` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`juridicos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`juridicos` (
  `pedido_id` INT NOT NULL COMMENT '',
  `amparo_legal` LONGTEXT NOT NULL COMMENT '',
  `dotacao` VARCHAR(255) NOT NULL COMMENT '',
  `finalizacao` LONGTEXT NOT NULL COMMENT '',
  PRIMARY KEY (`pedido_id`)  COMMENT '',
  CONSTRAINT `fk_juridicos_pedidos1`
    FOREIGN KEY (`pedido_id`)
    REFERENCES `siscontrat`.`pedidos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`pagamentos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`pagamentos` (
  `pedido_id` INT NOT NULL COMMENT '',
  `nota_empenho` CHAR(6) NULL COMMENT '',
  `emissao_nota_empenho` DATE NULL COMMENT '',
  `entrega_nota_empenho` DATE NULL COMMENT '',
  `usuario_pagamento_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`pedido_id`)  COMMENT '',
  CONSTRAINT `fk_pagamentos_pedidos1`
    FOREIGN KEY (`pedido_id`)
    REFERENCES `siscontrat`.`pedidos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`tipo_documentos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`tipo_documentos` (
  `id` TINYINT(1) NOT NULL AUTO_INCREMENT COMMENT '',
  `tipo_documento` VARCHAR(8) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`lista_documentos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`lista_documentos` (
  `id` SMALLINT(3) NOT NULL AUTO_INCREMENT COMMENT '',
  `tipo_documento_id` TINYINT(1) NOT NULL COMMENT '',
  `documento` VARCHAR(120) NOT NULL COMMENT '',
  `sigla` VARCHAR(10) NOT NULL COMMENT '',
  `teatro` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '',
  `musica` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '',
  `oficina` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '',
  `edital` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '',
  `validade` TINYINT(3) NOT NULL DEFAULT 0 COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_documentos_tipo_documentos_idx` (`tipo_documento_id` ASC)  COMMENT '',
  CONSTRAINT `fk_documentos_tipo_documentos`
    FOREIGN KEY (`tipo_documento_id`)
    REFERENCES `siscontrat`.`tipo_documentos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`arquivos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`arquivos` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `origem_id` INT NOT NULL COMMENT '',
  `lista_documento_id` SMALLINT(3) NOT NULL COMMENT '',
  `arquivo` VARCHAR(45) NOT NULL COMMENT '',
  `data` DATETIME NOT NULL COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_arquivos_lista_documentos_idx` (`lista_documento_id` ASC)  COMMENT '',
  INDEX `fk_arquivos_emia_contratacao_idx` (`origem_id` ASC)  COMMENT '',
  CONSTRAINT `fk_arquivos_lista_documentos`
    FOREIGN KEY (`lista_documento_id`)
    REFERENCES `siscontrat`.`lista_documentos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_arquivos_emia_contratacao`
    FOREIGN KEY (`origem_id`)
    REFERENCES `siscontrat`.`emia_contratacao` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_arquivos_formacao_contratacao`
    FOREIGN KEY (`origem_id`)
    REFERENCES `siscontrat`.`formacao_contratacoes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_arquivos_pessoa_fisicas`
    FOREIGN KEY (`origem_id`)
    REFERENCES `siscontrat`.`pessoa_fisicas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_arquivos_pessoa_juridicas`
    FOREIGN KEY (`origem_id`)
    REFERENCES `siscontrat`.`pessoa_juridicas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`relacao_juridicas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`relacao_juridicas` (
  `id` TINYINT(2) NOT NULL AUTO_INCREMENT COMMENT '',
  `relacao_juridica` VARCHAR(70) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`projeto_especiais`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`projeto_especiais` (
  `id` TINYINT(2) NOT NULL AUTO_INCREMENT COMMENT '',
  `projeto_especial` VARCHAR(70) BINARY NOT NULL COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`evento_status`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`evento_status` (
  `id` TINYINT(2) NOT NULL AUTO_INCREMENT COMMENT '',
  `status` VARCHAR(45) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`eventos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`eventos` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `nome_evento` VARCHAR(240) NOT NULL COMMENT '',
  `relacao_juridica_id` TINYINT(2) NOT NULL COMMENT '',
  `projeto_especial_id` TINYINT(2) NOT NULL COMMENT '',
  `tipo` TINYINT(1) NOT NULL COMMENT 'tipo:\n1-atracação\n2-oficina\n3-filme',
  `sinopse` LONGTEXT NOT NULL COMMENT '',
  `fiscal_id` INT NOT NULL COMMENT '',
  `suplente_id` INT NOT NULL COMMENT '',
  `usuario_id` INT NOT NULL COMMENT '',
  `contratacao` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  `original` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Original\n0-Evento original\n1-Evento copiado',
  `evento_status_id` TINYINT(2) NOT NULL COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_eventos_relacao_juridicas_idx` (`relacao_juridica_id` ASC)  COMMENT '',
  INDEX `fk_eventos_projeto_especiais_idx` (`projeto_especial_id` ASC)  COMMENT '',
  INDEX `fk_eventos_usuarios_fiscal_idx` (`fiscal_id` ASC)  COMMENT '',
  INDEX `fk_eventos_usuarios_suplente_idx` (`suplente_id` ASC)  COMMENT '',
  INDEX `fk_eventos_usuarios_idx` (`usuario_id` ASC)  COMMENT '',
  INDEX `fk_eventos_evento_status1_idx` (`evento_status_id` ASC)  COMMENT '',
  CONSTRAINT `fk_eventos_relacao_juridicas`
    FOREIGN KEY (`relacao_juridica_id`)
    REFERENCES `siscontrat`.`relacao_juridicas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_eventos_projeto_especiais`
    FOREIGN KEY (`projeto_especial_id`)
    REFERENCES `siscontrat`.`projeto_especiais` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_eventos_usuarios_fiscal`
    FOREIGN KEY (`fiscal_id`)
    REFERENCES `siscontrat`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_eventos_usuarios_suplente`
    FOREIGN KEY (`suplente_id`)
    REFERENCES `siscontrat`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_eventos_usuarios`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `siscontrat`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_eventos_evento_status`
    FOREIGN KEY (`evento_status_id`)
    REFERENCES `siscontrat`.`evento_status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`classificacao_indicativas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`classificacao_indicativas` (
  `id` TINYINT(1) NOT NULL AUTO_INCREMENT COMMENT '',
  `classificacao_indicativa` VARCHAR(7) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`produtores`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`produtores` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `nome` VARCHAR(120) NOT NULL COMMENT '',
  `email` VARCHAR(60) NOT NULL COMMENT '',
  `telefone1` VARCHAR(15) NOT NULL COMMENT '',
  `telefone2` VARCHAR(15) NULL DEFAULT '' COMMENT '',
  `observacao` VARCHAR(255) NULL DEFAULT '' COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`avisos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`avisos` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `titulo` VARCHAR(70) NOT NULL COMMENT '',
  `mensagem` LONGTEXT NOT NULL COMMENT '',
  `data` DATETIME NOT NULL COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`tipo_atracoes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`tipo_atracoes` (
  `id` TINYINT(2) NOT NULL AUTO_INCREMENT COMMENT '',
  `tipo_atracao` VARCHAR(45) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`atracoes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`atracoes` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `nome_atracao` VARCHAR(100) NOT NULL COMMENT '',
  `tipo_atracacao_id` TINYINT(2) NOT NULL COMMENT '',
  `ficha_tecnica` LONGTEXT NOT NULL COMMENT '',
  `integrantes` LONGTEXT NOT NULL COMMENT '',
  `classificacao_indicativa_id` TINYINT(1) NOT NULL COMMENT '',
  `release_comunicacao` LONGTEXT NOT NULL COMMENT '',
  `links` LONGTEXT NULL COMMENT '',
  `quantidade_apresentacao` TINYINT(2) NOT NULL COMMENT '',
  `valor_individual` DECIMAL(7,2) NOT NULL COMMENT '',
  `produtor_id` INT NOT NULL COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_atracoes_classificacao_indicativas1_idx` (`classificacao_indicativa_id` ASC)  COMMENT '',
  INDEX `fk_atracoes_produtores_idx` (`produtor_id` ASC)  COMMENT '',
  INDEX `fk_atracoes_tipo_atracoes_idx` (`tipo_atracacao_id` ASC)  COMMENT '',
  CONSTRAINT `fk_atracoes_classificacao_indicativas1`
    FOREIGN KEY (`classificacao_indicativa_id`)
    REFERENCES `siscontrat`.`classificacao_indicativas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_atracoes_produtores`
    FOREIGN KEY (`produtor_id`)
    REFERENCES `siscontrat`.`produtores` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_atracoes_tipo_atracoes`
    FOREIGN KEY (`tipo_atracacao_id`)
    REFERENCES `siscontrat`.`tipo_atracoes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`exposicoes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`exposicoes` (
  `id` INT NOT NULL COMMENT '',
  `atracao_id` INT NOT NULL COMMENT '',
  `numero_contratados` TINYINT(2) NOT NULL COMMENT '',
  `tipo_contratacao` VARCHAR(20) NOT NULL COMMENT '',
  `valor` DECIMAL(7,2) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_exposicoes_atracoes1_idx` (`atracao_id` ASC)  COMMENT '',
  CONSTRAINT `fk_exposicoes_atracoes1`
    FOREIGN KEY (`atracao_id`)
    REFERENCES `siscontrat`.`atracoes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`paises`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`paises` (
  `id` INT NOT NULL COMMENT '',
  `nome_pais` VARCHAR(45) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`filmes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`filmes` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `titulo` VARCHAR(100) NOT NULL COMMENT '',
  `titulo_original` VARCHAR(100) NULL COMMENT '',
  `ano_producao` SMALLINT(4) NULL COMMENT '',
  `genero` VARCHAR(20) NULL COMMENT '',
  `bitola` VARCHAR(30) NULL COMMENT '',
  `direcao` LONGTEXT NULL COMMENT '',
  `sinopse` LONGTEXT NULL COMMENT '',
  `elenco` LONGTEXT NULL COMMENT '',
  `duracao` SMALLINT(3) NULL COMMENT '',
  `link_trailer` VARCHAR(60) NULL COMMENT '',
  `classificacao_indicativa_id` TINYINT(1) NULL COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  `pais_origem_id` INT NOT NULL COMMENT '',
  `pais_origem_coproducao_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_filmes_classificacao_indicativas1_idx` (`classificacao_indicativa_id` ASC)  COMMENT '',
  INDEX `fk_filmes_pais_origem1_idx` (`pais_origem_id` ASC)  COMMENT '',
  INDEX `fk_filmes_pais1_idx` (`pais_origem_coproducao_id` ASC)  COMMENT '',
  CONSTRAINT `fk_filmes_classificacao_indicativas1`
    FOREIGN KEY (`classificacao_indicativa_id`)
    REFERENCES `siscontrat`.`classificacao_indicativas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_filmes_pais_origem1`
    FOREIGN KEY (`pais_origem_id`)
    REFERENCES `siscontrat`.`paises` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_filmes_pais1`
    FOREIGN KEY (`pais_origem_coproducao_id`)
    REFERENCES `siscontrat`.`paises` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`ocorrencia_tipos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`ocorrencia_tipos` (
  `id` TINYINT(1) NOT NULL AUTO_INCREMENT COMMENT '',
  `tipo_ocorrencia` VARCHAR(25) NOT NULL COMMENT '1 - evento\n2 - subevento\n3 - oficina',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`oficinas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`oficinas` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `evento_id` INT NOT NULL COMMENT '',
  `certificado` TINYINT(1) NOT NULL COMMENT '',
  `vagas` SMALLINT(3) NOT NULL COMMENT '',
  `publico_alvo` LONGTEXT NOT NULL COMMENT '',
  `material` LONGTEXT NOT NULL COMMENT '',
  `inscricao` TINYINT(1) NOT NULL COMMENT '',
  `valor_hora` DECIMAL(7,2) NOT NULL COMMENT '',
  `venda` TINYINT(1) NOT NULL COMMENT '',
  `data_divulgacao` DATE NOT NULL COMMENT '',
  `carga_horaria` SMALLINT(3) NOT NULL COMMENT '',
  `produtor_id` INT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_oficinas_produtores1_idx` (`produtor_id` ASC)  COMMENT '',
  CONSTRAINT `fk_oficinas_produtores1`
    FOREIGN KEY (`produtor_id`)
    REFERENCES `siscontrat`.`produtores` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`retirada_ingressos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`retirada_ingressos` (
  `id` TINYINT(2) NOT NULL AUTO_INCREMENT COMMENT '',
  `retirada_ingresso` VARCHAR(45) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`ocorrencias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`ocorrencias` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `tipo_ocorrencia_id` TINYINT(1) NOT NULL COMMENT '',
  `origem_ocorrencia_id` INT NOT NULL COMMENT '',
  `local_id` INT NOT NULL COMMENT '',
  `espaco_id` INT NULL COMMENT '',
  `data_inicio` DATE NOT NULL COMMENT '',
  `data_fim` DATE NULL COMMENT '',
  `segunda` TINYINT(1) NULL COMMENT '',
  `terca` TINYINT(1) NULL COMMENT '',
  `quarta` TINYINT(1) NULL COMMENT '',
  `quinta` TINYINT(1) NULL COMMENT '',
  `sexta` TINYINT(1) NULL COMMENT '',
  `sabado` TINYINT(1) NULL COMMENT '',
  `domingo` TINYINT(1) NULL COMMENT '',
  `horario_inicio` TIME NOT NULL COMMENT '',
  `horario_fim` TIME NOT NULL COMMENT '',
  `retirada_ingresso_id` TINYINT(2) NOT NULL COMMENT '',
  `valor_ingresso` DECIMAL(3,2) NOT NULL COMMENT '',
  `observacao` VARCHAR(120) NULL COMMENT '',
  `atracoes_id` INT NOT NULL COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_ocorrencias_ocorrencia_tipos1_idx` (`tipo_ocorrencia_id` ASC)  COMMENT '',
  INDEX `fk_ocorrencias_locais1_idx` (`local_id` ASC)  COMMENT '',
  INDEX `fk_ocorrencias_espacos1_idx` (`espaco_id` ASC)  COMMENT '',
  INDEX `fk_ocorrencias_retirada_ingressos1_idx` (`retirada_ingresso_id` ASC)  COMMENT '',
  INDEX `fk_ocorrencias_filmes1_idx` (`origem_ocorrencia_id` ASC)  COMMENT '',
  INDEX `fk_ocorrencias_atracoes1_idx` (`atracoes_id` ASC)  COMMENT '',
  CONSTRAINT `fk_ocorrencias_ocorrencia_tipos`
    FOREIGN KEY (`tipo_ocorrencia_id`)
    REFERENCES `siscontrat`.`ocorrencia_tipos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ocorrencias_oficinas`
    FOREIGN KEY (`origem_ocorrencia_id`)
    REFERENCES `siscontrat`.`oficinas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ocorrencias_locais`
    FOREIGN KEY (`local_id`)
    REFERENCES `siscontrat`.`locais` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ocorrencias_espacos`
    FOREIGN KEY (`espaco_id`)
    REFERENCES `siscontrat`.`espacos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ocorrencias_retirada_ingressos`
    FOREIGN KEY (`retirada_ingresso_id`)
    REFERENCES `siscontrat`.`retirada_ingressos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ocorrencias_filmes1`
    FOREIGN KEY (`origem_ocorrencia_id`)
    REFERENCES `siscontrat`.`filmes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ocorrencias_atracoes1`
    FOREIGN KEY (`atracoes_id`)
    REFERENCES `siscontrat`.`atracoes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`evento_envios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`evento_envios` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `evento_id` INT NOT NULL COMMENT '',
  `data_envio` DATETIME NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_evento_envios_eventos_idx` (`evento_id` ASC)  COMMENT '',
  CONSTRAINT `fk_evento_envios_eventos`
    FOREIGN KEY (`evento_id`)
    REFERENCES `siscontrat`.`eventos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`especificidades`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`especificidades` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `atracao_id` INT NOT NULL COMMENT '',
  `estreia` TINYINT(1) NOT NULL COMMENT '',
  `genero` VARCHAR(60) NOT NULL COMMENT '',
  `venda` TINYINT(1) NOT NULL COMMENT '',
  `material` LONGTEXT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_especificidades_atracoes1_idx` (`atracao_id` ASC)  COMMENT '',
  CONSTRAINT `fk_especificidades_atracoes1`
    FOREIGN KEY (`atracao_id`)
    REFERENCES `siscontrat`.`atracoes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`ocorrencia_excecoes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`ocorrencia_excecoes` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `atracao_id` INT NOT NULL COMMENT '',
  `data_excecao` DATE NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_ocorrencia_excecao_ocorrencias_idx` (`atracao_id` ASC)  COMMENT '',
  CONSTRAINT `fk_ocorrencia_excecao_ocorrencias`
    FOREIGN KEY (`atracao_id`)
    REFERENCES `siscontrat`.`ocorrencias` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`parcelas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`parcelas` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `pedido_id` INT NOT NULL COMMENT '',
  `numero_parcelas` TINYINT(2) NOT NULL COMMENT '',
  `valor` DECIMAL(5,2) NOT NULL COMMENT '',
  `data_pagamento` DATE NOT NULL COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_parcelas_pedidos1_idx` (`pedido_id` ASC)  COMMENT '',
  CONSTRAINT `fk_parcelas_pedidos1`
    FOREIGN KEY (`pedido_id`)
    REFERENCES `siscontrat`.`pedidos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`parcela_complementos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`parcela_complementos` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `parcela_id` INT NOT NULL COMMENT '',
  `data_inicio` DATE NOT NULL COMMENT '',
  `data_fim` DATE NOT NULL COMMENT '',
  `carga_horaria` TINYINT(2) NOT NULL COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  INDEX `fk_parcela_complementos_parcelas_idx` (`parcela_id` ASC)  COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  CONSTRAINT `fk_parcela_complementos_parcelas`
    FOREIGN KEY (`parcela_id`)
    REFERENCES `siscontrat`.`parcelas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`emia_parcelas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`emia_parcelas` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `emia_vigencia_id` INT NOT NULL COMMENT '',
  `numero_parcelas` TINYINT(2) NOT NULL COMMENT '',
  `valor` DECIMAL(5,2) NOT NULL COMMENT '',
  `data_inicio` DATE NOT NULL COMMENT '',
  `data_fim` DATE NOT NULL COMMENT '',
  `data_pagamento` DATE NOT NULL COMMENT '',
  `mes_referencia` VARCHAR(10) NOT NULL COMMENT '',
  `carga_horaria` TINYINT(2) NOT NULL COMMENT '',
  `publicado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_emia_parcelas_emia_vigencias1_idx` (`emia_vigencia_id` ASC)  COMMENT '',
  CONSTRAINT `fk_emia_parcelas_emia_vigencias1`
    FOREIGN KEY (`emia_vigencia_id`)
    REFERENCES `siscontrat`.`emia_vigencias` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`exposicoes_comunicacoes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`exposicoes_comunicacoes` (
  `exposicao_id` INT NOT NULL COMMENT '',
  `painel` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '',
  `legenda` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '',
  `identidade` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '',
  `suporte` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '',
  `acervo` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '',
  INDEX `fk_exposicoes_comunicacoes_exposicoes1_idx` (`exposicao_id` ASC)  COMMENT '',
  PRIMARY KEY (`exposicao_id`)  COMMENT '',
  CONSTRAINT `fk_exposicoes_comunicacoes_exposicoes1`
    FOREIGN KEY (`exposicao_id`)
    REFERENCES `siscontrat`.`exposicoes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`atracao_eventos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`atracao_eventos` (
  `atracao_id` INT NOT NULL COMMENT '',
  `evento_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`evento_id`, `atracao_id`)  COMMENT '',
  INDEX `fk_atracao_eventos_atracoes_idx` (`atracao_id` ASC)  COMMENT '',
  CONSTRAINT `fk_atracao_eventos_eventos`
    FOREIGN KEY (`evento_id`)
    REFERENCES `siscontrat`.`eventos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_atracao_eventos_atracoes`
    FOREIGN KEY (`atracao_id`)
    REFERENCES `siscontrat`.`atracoes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`protocolos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`protocolos` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `tipo_origem_id` TINYINT(1) NOT NULL COMMENT '',
  `origem_id` INT NOT NULL COMMENT '',
  `protocolo` VARCHAR(15) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`filme_eventos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`filme_eventos` (
  `filme_id` INT NOT NULL COMMENT '',
  `evento_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`filme_id`, `evento_id`)  COMMENT '',
  INDEX `fk_filme_eventos_eventos1_idx` (`evento_id` ASC)  COMMENT '',
  CONSTRAINT `fk_filme_eventos_filmes1`
    FOREIGN KEY (`filme_id`)
    REFERENCES `siscontrat`.`filmes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_filme_eventos_eventos1`
    FOREIGN KEY (`evento_id`)
    REFERENCES `siscontrat`.`eventos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`oficina_eventos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`oficina_eventos` (
  `oficina_id` INT NOT NULL COMMENT '',
  `evento_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`oficina_id`, `evento_id`)  COMMENT '',
  INDEX `fk_oficina_eventos_eventos1_idx` (`evento_id` ASC)  COMMENT '',
  CONSTRAINT `fk_oficina_eventos_oficinas1`
    FOREIGN KEY (`oficina_id`)
    REFERENCES `siscontrat`.`oficinas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_oficina_eventos_eventos1`
    FOREIGN KEY (`evento_id`)
    REFERENCES `siscontrat`.`eventos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `siscontrat`.`eventos_relacionados`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `siscontrat`.`eventos_relacionados` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `evento_id` INT NOT NULL COMMENT '',
  `evento_relacionado_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `fk_eventos_relacionados_eventos1_idx` (`evento_id` ASC)  COMMENT '',
  INDEX `fk_eventos_relacionados_eventos2_idx` (`evento_relacionado_id` ASC)  COMMENT '',
  CONSTRAINT `fk_eventos_relacionados_eventos1`
    FOREIGN KEY (`evento_id`)
    REFERENCES `siscontrat`.`eventos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_eventos_relacionados_eventos2`
    FOREIGN KEY (`evento_relacionado_id`)
    REFERENCES `siscontrat`.`eventos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
-- begin attached script 'script'
-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.1.31-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win32
-- HeidiSQL Versão:              9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Copiando dados para a tabela siscontrat.perfis:
DELETE FROM `evento_status`;
/*!40000 ALTER TABLE `evento_status` DISABLE KEYS */;
INSERT INTO `evento_status` (`id`, `status`) VALUES
  (1, 'Em elaboração'),
  (2, 'Aguardando'),
  (3, 'Enviado'),
  (4, 'Concluido'),
  (5, 'Cancelado');
/*!40000 ALTER TABLE `evento_status` ENABLE KEYS */;

-- Copiando dados para a tabela siscontrat.perfis:
DELETE FROM `perfis`;
/*!40000 ALTER TABLE `perfis` DISABLE KEYS */;
INSERT INTO `perfis` (`id`, `descricao`) VALUES
  (1, 'root');
/*!40000 ALTER TABLE `perfis` ENABLE KEYS */;

-- Copiando dados para a tabela siscontrat.relacao_juridicas:
DELETE FROM `relacao_juridicas`;
/*!40000 ALTER TABLE `relacao_juridicas` DISABLE KEYS */;
INSERT INTO `relacao_juridicas` (`id`,`relacao_juridica`) VALUES 
	(1,'Não há relação jurídica'),
	(2,'Parceria'),
	(3,'Doação de serviços'),
	(4,'Cessão de espaço'),
	(5,'Contratação artística'),
	(6,'Reversão de bilheteria'),
	(7,'Reversão de bilheteria e cachê'),
	(8,'Programa de rádio, tv e site'),
	(9,'Prefeitura / SMC'),
	(10,'Evento interno sem público'),
	(11,'Contratação de serviço sem evento'),
	(12,'Contrapartida'),
	(13,'Contrato por exclusividade'),
	(14,'Contratação de Serviços Profissionais de Natureza Intelectua'),
	(15,'Fomentos'),
	(16,'Contratação por notório conhecimento técnico/experiência'),
	(17,'Premiação');
/*!40000 ALTER TABLE `relacao_juridicas` ENABLE KEYS */;

-- Copiando dados para a tabela siscontrat.projeto_especiais:
DELETE FROM `projeto_especiais`;
/*!40000 ALTER TABLE `projeto_especiais` DISABLE KEYS */;
INSERT INTO `projeto_especiais` (`id`,`projeto_especial`,`publicado`) VALUES 
	(1,'Não pertence a nenhum projeto especial',0),
	(2,'Mês da Cultura Independente (MCI)',1),
	(3,'50 anos do golpe',1),
	(4,'Edital Programa de Exposições CCSP',1),
	(5,'Edital de Mediação em Arte',1),
	(6,'Edital ccsp dança em site específico',1),
	(7,'Aniversário do CCSP',1),
	(8,'Semanas de Dança',1),
	(9,'Mostra de Fomento',1),
	(10,'Outubro Mês da Criança ',1),
	(11,'Livre Acesso',1),
	(12,'Circuito Cultural',1),
	(13,'Circuito Municipal de Cultura 2016',1),
	(14,'Aniversário da Cidade de São Paulo 2016',1),
	(15,'Carnaval 2016',1),
	(16,'FunkSP',1),
	(17,'Programação do Museu da Cidade ',1),
	(18,'LIVRE ACESSO',0),
	(19,'Circuito Municipal de Cultura 2016 / Ruas Abertas',0),
	(20,'Vitrine da Dança ',1),
	(21,'Choro no Mercadão',1),
	(22,'A Hora do Choro',1),
	(23,'Virada Cultural 2016',1),
	(24,'Virada Cultural 2016 e Circuito Municipal de Cultura 2016',1),
	(25,'Programa Veia e Ventania',1),
	(26,'Edital Ocupação Folhetaria ',1),
	(27,'Ateliê Sonoro CCSP',1),
	(28,'Aniversário EMIA - 35 anos',1),
	(29,'Circuito Municipal de Cultura 2016 / Mostra [D]escontructo',1),
	(30,'Emenda Parlamentar',1),
	(31,'Jornada do Patrimônio',1),
	(32,'XII FESTIVAL A ARTE DE CONTAR HISTÓRIAS',1),
	(33,'Território Hip Hop',1),
	(35,'Arte&Sexualidade',1),
	(36,'II Feira de Arte Impressa',0),
	(37,'Exposição Mostra Folhetaria - Segunda Edição',0),
	(38,'II Feira de Arte Impressa do CCSP e Exposição Mostra Folhetaria',1),
	(39,'Aniversário da Cidade de São Paulo 2017',1),
	(41,'Virada Cultural 2017',1),
	(42,'Semana Márioswald - Cem anos de uma amizade',1),
	(43,'Biblioteca Viva',1),
	(44,'Curso de Formação para Funcionários',1),
	(45,'Mês do Hip Hop',1),
	(46,'Edital da Mostra de Dramaturgia em Pequenos Formatos Cênicos',1),
	(47,'Mês do Rock',1),
	(48,'Quintas da Boa Música',1),
	(49,'Gala de Balé',1),
	(50,'70 +',0),
	(51,'XIII FESTIVAL A ARTE DE CONTAR HISTÓRIAS',1),
	(52,'Mês do Samba',1),
	(53,'Guimarães Rosa',1),
	(54,'Virada Cultural 2018',1),
	(55,'Aniversário da Cidade de São Paulo 2018',1),
	(56,'Abril Para a Dança',1),
	(57,'Explosão 68',1),
	(58,'De Palco em Palco',1),
	(59,'Criançada',1),
	(60,'Giro da Cultura',1),
	(61,'Edital de Seleção de Oficineiros 2017 CCSP',1),
	(62,'Peripatumen – Conversas Filosóficas para Crianças',1),
	(63,'80 anos da Missão de Pesquisas Folclóricas de Mário de Andrade',1),
	(64,'CUCA',1),
	(65,'Edital de Credenciamento nº 01/2017 – SMC/GAB',1);

/*!40000 ALTER TABLE `projeto_especiais` ENABLE KEYS */;

-- Copiando dados para a tabela siscontrat.usuarios:
DELETE FROM `usuarios`;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` (`id`, `nome_completo`, `usuario`, `senha`, `email`, `telefone`, `perfil_id`, `fiscal`, `data_cadastro`, `ultimo_acesso`) VALUES
  (1, 'Qwerty da Silva', '0000000', 'e10adc3949ba59abbe56e057f20f883e', 'henrique714tinem@gmail.com', '(11) 1111-1111', 1, 1, '2018-10-16 00:00:00', '2018-10-16 00:00:00');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;


/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

-- end attached script 'script'
