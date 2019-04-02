USE siscontrat;

INSERT INTO `eventos` (`id`, `tipo_evento_id`, `original`, `nome_evento`, `relacao_juridica_id`, `projeto_especial_id`, `sinopse`, `fiscal_id`, `suplente_id`, `usuario_id`, `contratacao`, `evento_status_id`, `evento_interno`, `publicado`) VALUES
(1, 1, 1, 'Teste Evento 1', 1, 1, 'Ana Cañas faz o show de lançamento do seu quarto disco, “Tô na Vida” (Som Livre/Guela Records). Produzido por Lúcio Maia (Nação Zumbi) em parceria com Ana e mixado por Mario Caldato Jr, é o primeiro disco totalmente autoral da carreira da cantora e traz parcerias com Arnaldo Antunes e Dadi entre outros.', 1, 1, 1, 1, 1, 0, 1);

INSERT INTO `produtores` (`id`, `nome`, `email`, `telefone1`, `telefone2`, `observacao`) VALUES
(1, 'Produtor 01', 'email@email.com', '(11) 96532-5228', '(11) 3397-0110', ''),
(2, 'Produtor 02', 'stidesenvolvimento@prefeitura.sp.gov.br', '(11) 3397-0110', '', '');

INSERT INTO `atracoes` (`id`, `evento_id`, `nome_atracao`, `categoria_atracao_id`, `ficha_tecnica`, `integrantes`, `classificacao_indicativa_id`, `release_comunicacao`, `links`, `quantidade_apresentacao`, `valor_individual`, `produtor_id`, `publicado`) VALUES
(1, 1, 'Atração de teste', 11, 'Lúcio Silva (guitarra e vocal)\r\nFabio Sá (baixo)\r\nMarco da Costa (bateria)\r\nEloá Faria (figurinista)\r\nLeonardo Kuero (técnico de som)', 'Ana Cañas RG 00000000-0 CPF 000.000.000-00\r\nLúcio Maia RG 00000000-0 CPF 000.000.000-00\r\nFabá Jimenez RG 00000000-0 CPF 000.000.000-00\r\nFabio Sá RG 00000000-0 CPF 000.000.000-00\r\nMarco da Costa RG 00000000-0 CPF 000.000.000-00', 1, 'A cantora e compositora paulistana lançou, em 2007, o seu primeiro disco, \"Amor e Caos\". Dois anos depois, lançou \"Hein?\", disco produzido por Liminha e que contou com \"Esconderijo\", canção composta por Ana, eleita entre as melhores do ano pela revista Rolling Stone e que alcançou repercussão nacional por integrar a trilha sonora da novela \"Viver a Vida\" de Manoel Carlos, na Rede Globo. Ainda em 2009, grava, a convite do cantor e compositor Nando Reis, a bela canção \"Pra Você Guardei o Amor\". Em 2012, Ana lança o terceiro disco de inéditas, \"Volta\", com versões para Led Zeppelin (\"Rock\'n\'Roll\") e Edith Piaf (\"La Vie en Rose\"), além das inéditas autorais \"Urubu Rei\" (que ganhou clipe dirigido por Vera Egito) e \"Será Que Você Me Ama?\". Em 2013, veio o primeiro DVD, \"Coração Inevitável\", registrando o show que contou com a direção e iluminação de Ney Matogrosso.', 'https://www.facebook.com/anacanasoficial/\r\nhttps://www.youtube.com/user/anacanasoficial', 1, '0.00', 1, 1),
(2, 1, 'Atração de teste 2', 7, 'Lúcio Silva (guitarra e vocal)\r\nFabio Sá (baixo)\r\nMarco da Costa (bateria)\r\nEloá Faria (figurinista)\r\nLeonardo Kuero (técnico de som)', 'Ana Cañas RG 00000000-0 CPF 000.000.000-00\r\nLúcio Maia RG 00000000-0 CPF 000.000.000-00\r\nFabá Jimenez RG 00000000-0 CPF 000.000.000-00\r\nFabio Sá RG 00000000-0 CPF 000.000.000-00\r\nMarco da Costa RG 00000000-0 CPF 000.000.000-00', 6, 'A cantora e compositora paulistana lançou, em 2007, o seu primeiro disco, \"Amor e Caos\". Dois anos depois, lançou \"Hein?\", disco produzido por Liminha e que contou com \"Esconderijo\", canção composta por Ana, eleita entre as melhores do ano pela revista Rolling Stone e que alcançou repercussão nacional por integrar a trilha sonora da novela \"Viver a Vida\" de Manoel Carlos, na Rede Globo. Ainda em 2009, grava, a convite do cantor e compositor Nando Reis, a bela canção \"Pra Você Guardei o Amor\". Em 2012, Ana lança o terceiro disco de inéditas, \"Volta\", com versões para Led Zeppelin (\"Rock\'n\'Roll\") e Edith Piaf (\"La Vie en Rose\"), além das inéditas autorais \"Urubu Rei\" (que ganhou clipe dirigido por Vera Egito) e \"Será Que Você Me Ama?\". Em 2013, veio o primeiro DVD, \"Coração Inevitável\", registrando o show que contou com a direção e iluminação de Ney Matogrosso.', 'Links de exemplo:\r\nhttps://www.facebook.com/anacanasoficial/\r\nhttps://www.youtube.com/user/anacanasoficial', 1, '20.00', 2, 1);

INSERT INTO `ocorrencias` (`id`, `tipo_ocorrencia_id`, `origem_ocorrencia_id`, `instituicao_id`, `local_id`, `espaco_id`, `data_inicio`, `data_fim`, `segunda`, `terca`, `quarta`, `quinta`, `sexta`, `sabado`, `domingo`, `horario_inicio`, `horario_fim`, `retirada_ingresso_id`, `valor_ingresso`, `observacao`, `virada`, `publicado`) VALUES
(1, 1, 1, 7, 596, 0, '2019-05-19', '0000-00-00', 0, 0, 0, 0, 0, 0, 1, '19:00:00', '20:00:00', 6, '0.00', '', 0, 1),
(2, 1, 1, 7, 596, 0, '2019-05-18', '0000-00-00', 0, 0, 0, 0, 0, 0, 1, '21:00:00', '23:00:00', 6, '0.00', '', 0, 1),
(3, 1, 2, 2, 11, 42, '2019-06-23', '2019-07-23', 0, 0, 0, 0, 1, 1, 0, '19:00:00', '20:00:00', 7, '1.00', '', 0, 1);

INSERT INTO `representante_legais` (`id`, `nome`, `rg`, `cpf`) VALUES
(1, 'Representante 1', '00.000.000-0', '000.000.000-00'),
(2, 'Lorelei Gabriele Castro Lourenço Silva', '41.916.038-3', '320.692.848-67');

INSERT INTO `pessoa_juridicas` (`id`, `razao_social`, `cnpj`, `ccm`, `email`, `representante_legal1_id`, `representante_legal2_id`, `ultima_atualizacao`) VALUES
(1, 'Razão Social TESTE', '00.000.000/0000-00', 'ccmpj', 'empresa@email.com', 1, 2, '2019-04-01 16:35:34');

INSERT INTO `pj_bancos` (`pessoa_juridica_id`, `banco_id`, `agencia`, `conta`, `publicado`) VALUES
(1, 1, '1552-0', '66993-8', 1);


INSERT INTO `pj_enderecos` (`pessoa_juridica_id`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `uf`, `cep`) VALUES
(1, 'Avenida São João', 89, '6º andar', 'Centro', 'São Paulo', 'SP', '01035-000');

INSERT INTO `pj_telefones` (`id`, `pessoa_juridica_id`, `telefone`, `publicado`) VALUES
(1, 1, '(11) 3397-0000', 1),
(2, 1, '(11) 3397-4002', 1);

INSERT INTO `pedidos` (`id`, `origem_tipo_id`, `origem_id`, `pessoa_tipo_id`, `pessoa_juridica_id`, `pessoa_fisica_id`, `numero_processo`, `verba_id`, `numero_parcelas`, `valor_total`, `forma_pagamento`, `data_kit_pagamento`, `justificativa`, `status_pedido_id`, `observacao`, `publicado`) VALUES
(1, 1, 1, 2, 1, NULL, NULL, 6, 1, '50.00', 'forma teste', '2019-07-27', 'justificativa teste', 1, '', 1);

































