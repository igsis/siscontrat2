CREATE TABLE IF NOT EXISTS `siscontrat`.`formacao_lista_documentos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `documento` VARCHAR(150) NOT NULL,
  `sigla` VARCHAR(10) NOT NULL,
  `ordem` TINYINT(2) NULL DEFAULT 0,
  `obrigatorio` TINYINT(1) NULL DEFAULT 0,
  `publicado` TINYINT(1) NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `documento_UNIQUE` (`documento` ASC),
  UNIQUE INDEX `sigla_UNIQUE` (`sigla` ASC))
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `siscontrat`.`formacao_arquivos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `formacao_lista_documento_id` INT NOT NULL,
  `formacao_contratacao_id` INT NOT NULL,
  `arquivo` VARCHAR(100) NOT NULL,
  `data` DATETIME NOT NULL,
  `publicado` TINYINT(1) NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX `fk_formacao_arquivos_formacao_lista_documentos1_idx` (`formacao_lista_documento_id` ASC),
  INDEX `fk_formacao_arquivos_formacao_contratacoes1_idx` (`formacao_contratacao_id` ASC),
  CONSTRAINT `fk_formacao_arquivos_formacao_lista_documentos1`
    FOREIGN KEY (`formacao_lista_documento_id`)
    REFERENCES `siscontrat`.`formacao_lista_documentos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_formacao_arquivos_formacao_contratacoes1`
    FOREIGN KEY (`formacao_contratacao_id`)
    REFERENCES `siscontrat`.`formacao_contratacoes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


INSERT INTO `formacao_lista_documentos` (`id`, `documento`, `sigla`, `ordem`, `obrigatorio`, `publicado`) VALUES
(1, 'RG/RNE/PASSAPORTE', 'rg', 0, 1, 1),
(2, 'CPF', 'cpf', 0, 1, 1),
(3, 'Comprovante de residência', 'residencia', 0, 1, 1),
(4, 'PIS/PASEP/NIT', 'pis_pasep_', 0, 1, 1),
(5, 'Currículo', 'curriculo', 0, 1, 1),
(6, 'DRT', 'drt', 0, 0, 1),
(7, 'Anexos III a V (arquivo único)', 'anex3a5', 0, 1, 0),
(8, 'Comprovante de formação 1', 'com_form1', 0, 1, 1),
(9, 'Comprovante de formação 2', 'com_form2', 0, 0, 1),
(10, 'Comprovante de formação 3', 'com_form3', 0, 0, 1),
(11, 'Comprovante de formação 4', 'com_form4', 0, 0, 1),
(12, 'Comprovante de experiência artística 1', 'com_art1', 0, 1, 1),
(13, 'Comprovante de experiência artística 2', 'com_art2', 0, 0, 1),
(14, 'Comprovante de experiência artística 3', 'com_art3', 0, 0, 1),
(15, 'Comprovante de experiência artística 4', 'com_art4', 0, 0, 1),
(16, 'Comprovante de experiência artístico-pedagógica 1', 'comartped1', 0, 1, 1),
(17, 'Comprovante de experiência artístico-pedagógica 2', 'comartped2', 0, 0, 1),
(18, 'Comprovante de experiência artístico-pedagógica 3', 'comartped3', 0, 0, 1),
(19, 'Comprovante de experiência artístico-pedagógica 4', 'comartped4', 0, 0, 1),
(20, 'Comprovante de Experiência em Articulação/Coordenação 1', 'comcoord1', 0, 1, 1),
(21, 'Comprovante de Experiência em Articulação/Coordenação 2', 'comcoord2', 0, 0, 1),
(22, 'Comprovante de Experiência em Articulação/Coordenação 3', 'comcoord3', 0, 0, 1),
(23, 'Comprovante de Experiência em Articulação/Coordenação 4', 'comcoord4', 0, 0, 1),
(24, 'Anexo II a IV (arquivo único)', 'f-anxII-IV', 0, 1, 1),
(25, 'Anexo V (carta de intenção)', 'f-anexoV', 0, 1, 1),
(26, 'Anexo VI (somente para opção de cotas étnico-raciais)', 'f-anexoVI', 0, 0, 1),
(27, 'Anexo VII (somente para declarar opção de uso do nome social)', 'f-anexoVII', 0, 0, 1),
(28, 'Laudo médico com CID (somente para pessoa com deficiência)', 'f-laudopcd', 0, 0, 1),
(29, 'ANEXO VIII: Indicação de Membros da Sociedade Civil para a Comissão de Seleção', 'f-anxVIII', 0, 0, 1);