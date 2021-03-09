ALTER TABLE `atracoes`
    CHANGE COLUMN `ficha_tecnica` `ficha_tecnica` LONGTEXT NULL DEFAULT NULL COLLATE 'utf8_general_ci' AFTER `nome_atracao`;