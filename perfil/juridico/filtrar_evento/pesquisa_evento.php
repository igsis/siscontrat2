<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Buscar por evento</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3> Dados Eventos</h3>
                    </div>
                    <form>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for=codigo_pedido"">Código do Pedido</label>
                                    <input type="text" class="form-control" name="codigo_pedido" id="codigo_pedido">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="num_processo">Número do Processo </label>
                                    <input type="text" class="form-control" name="num_processo" id="num_processo"
                                           data-mask="9999.9999/9999999-9" minlength="19">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="objeto_evento">Objeto/Evento </label>
                                    <input type="text" class="form-control" name="objeto_evento" id="objeto_evento">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="">Fical,suplente ou usuário que cadastrou o evento</label>
                                    <select name="usuario" id="usuario" class="form-control">

                                        <option value="0">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao('usuarios');
                                        ?>
                                    </select>
                                </div>
                            </div>

                        </div>


                    </form>
                </div>
            </div>

        </div>

    </section>

</div>