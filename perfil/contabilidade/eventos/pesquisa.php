<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h3>Contabilidade - Busca</h3>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Pesquisar eventos</h3>
            </div>
            <div class="box-body">
                <form action="?perfil=contabilidade&p=eventos&sp=resultado" role="form" method="POST">
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
                        <div class="col-md-6">
                            <label for="usuario">Inserido por: </label>
                            <input class="form-control" type="text" name="usuario" id="usuario">
                        </div>

                        <div class="col-md-6">
                            <label for="tipo_evento">Tipo do Evento: </label>
                            <select class="form-control" name="tipo_evento" id="tipo_evento">
                                <option value="">Selecione um tipo...</option>
                                <?php
                                geraOpcao("tipo_eventos", "");
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <br>
                        <div class="col-md-4">
                            <label for="instituicao">Instituição: </label>
                            <select class="form-control" name="instituicao" id="instituicao">
                                <option value="">Selecione uma instituição...</option>
                                <?php
                                geraOpcao("instituicoes", "");
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="rel_jur">Tipo de Relação Jurídica: </label>
                            <select class="form-control" name="rel_jur" id="rel_jur">
                                <option value="">Selecione uma relacao juridica...</option>
                                <?php
                                geraOpcao("relacao_juridicas", "");
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="status">Status do evento: </label>
                            <select class="form-control" name="status" id="status">
                                <option value="">Selecione um status...</option>
                                <?php
                                geraOpcao("evento_status", "");
                                ?>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="box-footer">
                <a href="?perfil=contabilidade">
                    <button type="button" class="btn btn-default pull-left">Voltar</button>
                </a>
                <button type="submit" class="btn btn-primary pull-right" name="buscar">Pesquisar</button>
                </form>
            </div>
        </div>
    </section>
</div>

