<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h3>Contabilidade - Busca</h3>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Pesquisar pedidos de formação</h3>
            </div>
            <div class="box-body">
                <form action="?perfil=contabilidade&p=formacao&sp=resultado" role="form" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="protocolo">Protocolo: </label>
                            <input class="form-control" type="text" name="protocolo" id="protocolo">
                        </div>

                        <div class="col-md-6">
                            <label for="num_processo">Numero do Processo: </label>
                            <input class="form-control" type="text" name="num_processo" id="num_processo" data-mask="9999.9999/9999999-9">
                        </div>

                    </div>

                    <div class="row">
                        <br>
                        <div class="col-md-12">
                            <label for="status">Status do Pedido: </label>
                            <select class="form-control" name="status" id="status">
                                <option value="">Selecione um status...</option>
                                <?php
                                    geraOpcao("pedido_status", "");
                                ?>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="box-footer">
                <a href="?perfil=contabilidade">
                    <button type="button" class="btn btn-default pull-left">Voltar</button>
                </a>
                <button type="submit" class="btn btn-primary pull-right" name="carrega">Pesquisar</button>
                </form>
            </div>
        </div>
    </section>
</div>
