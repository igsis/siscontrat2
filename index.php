<?php

include "funcoes/funcoesGerais.php";
require "funcoes/funcoesConecta.php";

//autentica login e cria inicia uma session
if(isset($_POST['login']))
{
	$login = $_POST['login'];
	$senha = $_POST['senha'];
	$sql = "SELECT * FROM usuario AS usr
	WHERE usr.email = '$login' LIMIT 0,1";
	$con = bancoMysqli();
	$query = mysqli_query($con,$sql);
	//query que seleciona os campos que voltarão para na matriz
	if($query)
	{
		//verifica erro no banco de dados
		if(mysqli_num_rows($query) > 0)
		{
			// verifica se retorna usuário válido
			$user = mysqli_fetch_array($query);
			if($user['senha'] == md5($_POST['senha']))
			{
				// compara as senhas
				session_start();
				$_SESSION['login'] = $user['email'];
				$_SESSION['nome'] = $user['nome'];
				$_SESSION['idUser'] = $user['id'];
				$log = "Fez login.";
				//gravarLog($log);
				header("Location: visual/index.php");
				gravarLog($sql);
			}
			else

			{
			$mensagem = "<font color='#FF0000'><strong>A senha está incorreta!</strong></font>";

			}
		}
		else
		{
			$mensagem = "<font color='#FF0000'><strong>O usuário não existe.</strong></font>";
		}
	}
	else
	{
		$mensagem = "<font color='#FF0000'><strong>Erro no banco de dados!</strong></font>";
	}
}

?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>IGSIS</title>
		<link href="visual/css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="visual/css/style.css" rel="stylesheet" media="screen">
		<link href="visual/color/default.css" rel="stylesheet" media="screen">
		<link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
		<script src="visual/js/modernizr.custom.js"></script>
	</head>
	<body>
    <div id="bar">
        <div class="col-xs-2" style="padding: 10px">
            <img src="visual/images/logo_cultura_h.png">
        </div>
        <div class="col-md-2" style="padding: 13px">
            <span style="color: #fff">IGSIS</span>
        </div>
        <div class="col-md-offset-4 col-md-4" style="padding: 5px">
            <span style="color: #fff">Suporte para o sistema:<br><i class="fab fa-whatsapp"></i> sistema.igsis@gmail.com</span>
        </div>
    </div>

		<section id="contact" class="home-section bg-white">
			<div class="container">
				<div class="row">
					<div class="col-md-offset-1 col-md-10">
						<p align="justify">Este sistema tem por objetivo criar um ambiente para contratação artística.</p>


						<p align="justify">Dúvidas entre em contato com o nosso suporte através do endereço <strong>sistema.igsis@gmail.com</strong></p>

						<hr/>

						<h5><?php if(isset($mensagem)){ echo $mensagem; } ?></h5>

						<form method="POST" action="index.php" class="form-horizontal" role="form">
							<div class="form-group">
								<div class="col-md-offset-2 col-md-6">
									<label>Usuário</label>
									<input type="text" name="login" class="form-control" placeholder="E-mail" maxlength="120">
								</div>
								<div class=" col-md-6">
									<label>Senha</label>
									<input type="password" name="senha" class="form-control" placeholder="Senha" maxlength="60">
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-offset-2 col-md-8">
									<button type="submit" class="btn btn-theme btn-lg btn-block">Entrar</button>
								</div>
							</div>
						</form>
						<br />

						<div class="form-group">
							<div class="col-md-offset-2 col-md-6">
								<p>Não possui cadastro? <a href="verifica.php">Clique aqui.</a></p>
								<br />
							</div>
							<div class="col-md-6">
								<p>Esqueceu a senha? <a href="recuperar_senha.php">Clique aqui.</a></p>
								<br />
							</div>
						</div>
					</div>
				</div>
			</div>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<footer>
				<div class="container">
                    <div class="row">
                        <div class="col-md-2">
                            <img src="visual/images/logo_cultura_q.png">
                        </div>
                        <div class="col-md-offset-2 col-md-4" style="padding: 10px">
                            <span style="color: #ccc; "><?= date("Y") ?> @ IGSIS<br>Secretaria Municipal de Cultura<br>Prefeitura de São Paulo</span>
                        </div>
                        <div class="col-md-offset-2 col-md-2">
                            <img src="visual/images/logo_igsis_azul.png">
                        </div>
                    </div>
				</div>
			</footer>
		</section>
    </body>
</html>