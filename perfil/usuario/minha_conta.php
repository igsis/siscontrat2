<?php
include "includes/menu_interno.php";
$con = bancoMysqli();
$idUser = $_SESSION['usuario_id_s'];
$usuario = recuperaDados('usuarios', 'id', $idUser);

if (isset($_POST['atualizar'])) {
    $nome_completo = $_POST['nome_completo'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['senhaAtual'];
    $novaSenha = $_POST['novaSenha'] ?? NULL;

    if ($novaSenha != $_POST['confirmaSenha']) {
        $mensagem = mensagem("danger", "Senhas não conferem!");
    }

    if (md5($senha) == $usuario['senha']) {
        if ($novaSenha != NULL) {
            $novaSenha = md5($novaSenha);

            $sql = "UPDATE usuarios SET
                              nome_completo = '$nome_completo', 
                              senha = '$novaSenha',
                              email = '$email',
                              telefone = '$telefone'
                              WHERE id = '$idUser'";
        } else {
            $sql = "UPDATE usuarios SET
                              nome_completo = '$nome_completo', 
                              email = '$email',
                              telefone = '$telefone'
                              WHERE id = '$idUser'";
        }

        If (mysqli_query($con, $sql)) {
            $mensagem = mensagem("success", "Atualizado com sucesso!");
            //gravarLog($sql);
        } else {
            $mensagem = mensagem("danger", "Erro ao atualizar! Tente novamente.");
            //gravarLog($sql);
        }
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Senha incorreta!");
    }
}

$usuario = recuperaDados('usuarios', 'id', $idUser);
?>


<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Minha Conta</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Usuário: <?= $usuario['usuario'] ?></h3>
                    </div>
                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>

                    <form method="POST" action="http://<?=$_SERVER['HTTP_HOST']?>/siscontrat/inicio/edita" role="form">
                        <div class="box-body">

                            <div class="row">
                                <div class="form-group col-md-5">
                                    <label for="nome_completo">Nome: </label>
                                    <input type="text" class="form-control" id="nome_completo" name="nome_completo"
                                           maxlength="70" required value="<?= $usuario['nome_completo'] ?>">
                                </div>

                                <div class="form-group col-md-5">
                                    <label for="email">Email: </label>
                                    <input type="email" class="form-control" id="email" name="email" maxlength="60" required value="<?= $usuario['email'] ?>">
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="telefone">Telefone: </label>
                                    <input type="text" class="form-control" id="telefone" name="telefone"  maxlength="15" onkeyup="mascara( this, mtel );" value="<?= $usuario['telefone'] ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="senhaAtual">Senha atual*: </label>
                                    <input type="password" class="form-control" id="senhaAtual" name="senhaAtual" required>
                                </div>
                            </div>

                            <h3>Alteração de senha:</h3>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="novaSenha">Nova senha: </label>
                                    <input type="password" class="form-control" id="novaSenha" name="novaSenha">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="confirmaSenha">Confirmar Senha: </label>
                                    <input type="password" class="form-control" id="confirmaSenha" name="confirmaSenha" onblur="comparaSenhas()" onkeypress="comparaSenhas()">
                                </div>
                            </div>

                            <div class="box-footer">
                                <button type="submit" name="atualizar" id="atualizar" class="btn btn-info pull-right">Atualizar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3"><b>Autorizado como fiscal?</b>
                                <?php
                                if ($usuario['fiscal'] == 1){
                                    echo "Sim";
                                } else{
                                    echo "Não";
                                }
                                ?>
                            </div>
                            <?php
                            $perfil = recuperaDados("perfis","id",$usuario['perfil_id']);
                            ?>
                            <div class="col-md-3"><b>Perfil de acesso:</b> <?= $perfil['descricao'] ?></div>
                            <div class="col-md-3"><b>Código de acesso:</b> <?= $perfil['token'] ?></div>
                            <div class="col-md-3"><b>Data do cadastro:</b> <?= exibirDataHoraBr($usuario['data_cadastro']) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

<script>
    function comparaSenhas() {
        let senha = document.getElementById("novaSenha");
        let confirmaSenha = document.getElementById("confirmaSenha");
        document.getElementById("atualizar").disabled = true;

        if (senha.value == confirmaSenha.value) {
            document.getElementById("atualizar").disabled = false;
        } else {
        }
    }
</script>
