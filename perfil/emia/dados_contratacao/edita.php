<?php
$con = bancoMysqli();

if(isset($_POST['editar'])){
    $idEC = $_POST['idEC'];
    $idPf = $_POST['pf'];
    $ano = $_POST['ano'];
    $local = $_POST['local'];
    $cargo = $_POST['cargo'];
    $vigencia = $_POST['vigencia'];
    $cronograma = $_POST['cronograma'];
    $obs = $_POST['observacao'];
    $status = "1";
    $fiscal = $_POST['fiscal'];
    $suplente = $_POST['suplente'];
    $usuario = $_SESSION['idUser'];

    $sqlUpdate = "UPDATE emia_contratacao SET
                    pessoa_fisica_id = '$idPf',
                                 ano = '$ano',
                                 emia_cargo_id = '$cargo',
                                 emia_vigencia_id = '$vigencia',
                                 observacao = '$obs',
                                 fiscal_id = '$fiscal',
                                 suplente_id = '$suplente'
                                WHERE id = '$idEC'";

    if(mysqli_query($con,$sqlUpdate)){
        $mensagem = mensagem("success", "Salvo com sucesso!");
    }else{
        $mensagem = mensagem("danger", "Erro aos salvar! Tente novamente.");
    }
    $ec = recuperaDados('emia_contratacao', 'id', $idEC);
}

if (isset($_POST['edit'])) {
    $idEC = $_POST['idECEdit'];
    $_SESSION['idEC'] = $idEC;
    $ec = recuperaDados('emia_contratacao', 'id', $idEC);
}


?>
<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2>EMIA - Dados para contratação</h2>
        </div>
        <div class="box box-primary">
            <div class="row" align="center">
                <?php if (isset($mensagem)) {
                    echo $mensagem;
                }; ?>
            </div>
            <div class="box-header">
                <h4 class="box-title">Edição de dados para Contratação</h4>
            </div>
            <div class="box-body">
                <form action="?perfil=emia&p=dados_contratacao&sp=edita" method="POST" role="form">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="pf">Pessoa Física: *</label>
                            <select name="pf" id="pf" class="form-control" required>
                                <option value="">Selecione uma pessoa física...</option>
                                <?php
                                    geraOpcao('pessoa_fisicas', $ec['pessoa_fisica_id']);
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="ano">Ano: *</label>
                            <input name="ano" id="ano" type="number" min="2018" required class="form-control" value="<?=$ec['ano']?>">
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-4">
                            <label for="local">Local: *</label>
                            <select name="local" id="local" required class="form-control">
                                <option value="">Selecione um local...</option>
                                <?php
                                geraOpcao('locais', $ec['local_id']);
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="cargo">Cargo: *</label>
                            <select name="cargo" id="cargo" class="form-control" required>
                                <option value="">Selecione um cargo...</option>
                                <?php
                                geraOpcao('emia_cargos', $ec['emia_cargo_id']);
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="vigencia">Vigência: *</label>
                            <select name="vigencia" id="vigencia" class="form-control" required>
                                <option value="">Selecione a vigência...</option>
                                <?php
                                geraOpcao('emia_vigencias', $ec['emia_vigencia_id']);
                                ?>
                            </select>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <label for="cronograma">Cronograma: </label>
                            <textarea name="cronograma" id="cronograma"  rows="3" type="text" class="form-control"><?=$ec['cronograma']?></textarea>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <label for="observacao">Observação: </label>
                            <textarea name="observacao" id="observacao"  rows="3" type="text" class="form-control"><?=$ec['observacao']?></textarea>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="fiscal">Fiscal: </label>
                            <select name="fiscal" id="fiscal" class="form-control">
                                <option value="">Selecione um fiscal...</option>
                                <?php
                                     geraOpcaoUsuario("usuarios", 1, $ec['fiscal_id']);
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="suplente">Suplente: </label>
                            <select name="suplente" id="suplente" class="form-control">
                                <option value="">Selecione um suplente...</option>
                                <?php
                                     geraOpcaoUsuario("usuarios", 1, $ec['suplente_id']);
                                ?>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="box-footer">
                <a href="?perfil=emia&p=dados_contratacao&sp=listagem">
                    <button type="button" class="btn btn-default">Voltar</button>
                </a>
                <input type="hidden" name="idEC" value="<?=$idEC?>" id="idEC">
                <button type="submit" class="btn btn-primary pull-right" name="editar" id="editar">Salvar</button>
                <a href="?perfil=emia&p=pedido_contratacao&sp=cadastra">
                    <button type="button" class="btn btn-default pull-right">Gerar pedido de contratação</button>
                </a>
            </div>
            </form>
        </div>
    </section>
</div>