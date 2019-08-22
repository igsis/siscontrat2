<?php
$con = bancoMysqli();
$idFormacao = $_POST['idFormacao'];

?>
<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Notas de empenho</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Notas de empenho</h3>
                    </div>
                    <form method="POST" action="?perfil=formacao&p=pagamento&sp=empenho_edita"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="protocolo">Número da nota de empenho</label>
                                    <input type="text" name="numEmpenho" id="numEmpenho" class="form-control" required>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="">Data de emissão da nota de empenho</label>
                                    <input type="date" name="data_emissao" id="datepicker10" required
                                           class="form-control" placeholder="DD/MM/AAAA">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="">Data de entrega da nota de empenho</label>
                                    <input type="date" name="data_entrega" id="datepicker11" required
                                           class="form-control" placeholder="DD/MM/AAAA">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" id="idFormacao" name="idFormacao" value="<?= $idFormacao ?>">
                            <button type="submit" name="cadastra" id="cadastra" class="btn btn-primary pull-right">
                                Cadastrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
