drop database siscontrat;

use siscontrat;

insert into instituicoes VALUES (1, 'sla', 'isso ai'),
					(2, 'asd', 'é nois');
									
INSERT INTO `locais` (`id`, `instituicao_id`, `local`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `uf`, `cep`, `rider`) VALUES (NULL, '1', 'asd', 'asd', '122', '123', 'asdw', 'sp', 'sp', '123456789', 'aasd');
INSERT INTO `locais` (`id`, `instituicao_id`, `local`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `uf`, `cep`, `rider`) VALUES (NULL, '2', 'asdasdasd', 'asdasdasdweqq', '122', '123', 'asdw', 'sp', 'sp', '123456789', 'aasd');

INSERT INTO `espacos` (`id`, `local_id`, `espaco`) VALUES (NULL, '1', '100'), (NULL, '2', '200');

