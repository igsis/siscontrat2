use siscontrat;

INSERT INTO `perfis` (`id`, `descricao`) VALUES
  (1, 'root');
  
INSERT INTO `usuarios` (`id`, `nome_completo`, `usuario`, `senha`, `email`, `telefone`, `perfil_id`, `fiscal`, `data_cadastro`, `ultimo_acesso`) VALUES
  (1, 'Qwerty da Silva', 'igsis', 'e10adc3949ba59abbe56e057f20f883e', 'henrique714tinem@gmail.com', '(11) 1111-1111', 1, 1, '2018-10-16 00:00:00', '2018-10-16 00:00:00');