<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Pesquisar</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Buscar</h3>
                    </div>
                    <form method="POST" action="?perfil=pesquisa&p=resultado" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="protocolo">Protocolo:</label>
                                    <input type="text" name="protocolo" id="protocolo" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label for="protocolo">Número do Processo:</label>
                                    <input type="text" name="numProcesso" id="numProcesso" class="form-control">
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="evento">Nome do Evento:</label>
                                    <input type="text" class="form-control" name="nomeEvento" id="evento">
                                </div>

                                <div class="col-md-4">
                                    <label for="usuario">Fiscal, suplente ou usuário que cadastrou o evento</label>
                                    <select name="usuario" id="usuario" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao('usuarios');
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="projeto">Projeto Especial</label>
                                    <select name="projeto" id="projeto" class="form-control">
                                        <option value="">Selecione um projeto...</option>
                                        <?php
                                        geraOpcao('projeto_especiais');
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="valor_inicial">Valor Inicial: </label>
                                    <input type="text" name="valor_inicial" id="valor_inicial" class="form-control" onKeyPress="return(moeda(this,'.',',',event))">
                                </div>

                                <div class="col-md-6">
                                    <label for="valor_final">Valor Final: </label>
                                    <input type="text" name="valor_final" id="valor_final" class="form-control" onKeyPress="return(moeda(this,'.',',',event))">
                                </div>
                            </div>

                        </div>
                        <div class="box-footer">
                            <button type="submit" name="busca" class="btn btn-primary pull-right">Buscar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
