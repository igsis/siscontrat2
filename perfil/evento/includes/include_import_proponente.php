<?php
/** Arquivo utilizado para include na importação do proponente caso não exista cadastro no Siscontrat */

/** @var array $proponentes
 *  variável possui os dados do proponente no banco do Capac
 */
?>

<div class="alert alert-warning">
    <h4><i class="icon fa fa-warning"></i> Atenção!</h4>
    O proponente cadastrado no <strong>CAPAC</strong> já existe no <strong>SisContrat</strong>. Abaixo, clique
    na seta verde para escolher quais dados serão atualizados caso necessário e posteriormente clique no botão
    de gravar.
</div>

<div class="alert alert-info">
    <strong>Proponente:</strong> <?= $proponentes['nome'] ?> <br>
    <label for="">Documento: </label> <?= $proponentes['documento'] ?>
</div>

<div class="col-md-6">
    <div class="box box-success">
        <div class="box-header with-border">
            <h4 class="box-title">CAPAC</h4>
        </div>
        <div class="box-body" id="camposCapac">
            <?php foreach ($proponentes['Cpc'] as $key => $dado) {
                $label = ucwords(preg_replace('/_/', " ", $key));
                ?>
                <div class="form-group">
                    <label for="nomeEvento"><?= $label ?></label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="<?=$key?>Cpc" value="<?= $dado ?>" readonly>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-success" onclick="passaValor('<?=$key?>')">
                                &nbsp;<span class="glyphicon glyphicon-arrow-right"></span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<form method="POST" action="?perfil=evento&p=importar_proponente_capac" role="form">
    <input type="hidden" name="idCapac" value="<?= $idCapac ?>">
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">SisContrat</h3>
            </div>
            <div class="box-body">
                <?php foreach ($proponentes['Sis'] as $key => $dado) {
                    $label = ucwords(preg_replace('/_/', " ", $key));
                    ?>
                    <div class="form-group">
                        <label for="nomeEvento"><?= $label ?></label>
                        <div class="input-group">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-danger" onclick="resetaValor('<?= $key ?>')">
                                    &nbsp;<span class="glyphicon glyphicon-repeat" style="transform: rotateY(180deg)"></span>
                                </button>
                            </div>
                            <input type="text" name="<?=$key?>" class="form-control" id="<?= $key ?>Sis"
                                   data-valor="<?= $dado ?>" value="<?= $dado ?>" readonly>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <div class="pull-left">
            <a href="../downloads/arquivos_capac.php?tipo_pessoa=<?= $tipoPessoa ?>&idProponente=<?= $proponenteCpc_id ?>" class="btn btn-warning btn-block float-right" target="_blank">Baixar Arquivos do Proponente</a><br/>
        </div>
        <input type="hidden" name="idProponenteSis" value="<?= $proponentes['sis_proponente_id'] ?>">
        <?php if (isset($idEvento)): ?>
            <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
        <?php endif; ?>
        <button type="submit" name="importarProponenteCpc" class="btn btn-info pull-right">Gravar
        </button>
    </div>
</form>

<script>
    function passaValor(campo) {
        let campoCpc = '#' + campo + "Cpc";
        let campoSis = '#' + campo + "Sis";

        $(campoSis).val($(campoCpc).val())
    }

    function resetaValor(campo) {
        let campoSis = '#' + campo + "Sis";
        let valorOriginal = $(campoSis).data("valor");

        $(campoSis).val(valorOriginal)
    }
</script>