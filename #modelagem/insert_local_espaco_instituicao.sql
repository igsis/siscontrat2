use siscontrat;

insert into instituicoes VALUES (1, 'sla', 'isso ai'),
					(2, 'asd', 'é nois');
									
INSERT INTO `locais` (`id`, `instituicao_id`, `local`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `uf`, `cep`, `rider`) VALUES (NULL, '1', 'asd', 'asd', '122', '123', 'asdw', 'sp', 'sp', '123456789', 'aasd');
INSERT INTO `locais` (`id`, `instituicao_id`, `local`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `uf`, `cep`, `rider`) VALUES (NULL, '2', 'asdasdasd', 'asdasdasdweqq', '122', '123', 'asdw', 'sp', 'sp', '123456789', 'aasd');

INSERT INTO `espacos` (`id`, `local_id`, `espaco`) VALUES (NULL, '1', '100'), (NULL, '2', '200');

INSERT INTO ocorrencias (tipo_ocorrencia_id, origem_ocorrencia_id, instituicao_id, local_id, espaco_id, data_inicio, data_fim, segunda, terca, quarta, quinta, sexta, sabado, domingo, horario_inicio, horario_fim, retirada_ingresso_id, valor_ingresso, observacao) VALUES ('1', '1', '2', '1', '1', '2018-10-23', '', '1', '0', '0', '0', '0', '0', '0', '12:00', '17:00', '13', '100', 'saadsasd');

