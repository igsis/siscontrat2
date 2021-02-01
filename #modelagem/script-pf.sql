ALTER TABLE `pessoa_fisicas`
	CHANGE COLUMN `ccm` `pis_nit` CHAR(11) NULL DEFAULT '' COLLATE 'utf8_general_ci' AFTER `cpf`;
