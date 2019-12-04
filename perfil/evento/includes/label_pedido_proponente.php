<h3 class="h2">2. Cadastro de Proponente</h3>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="form-group col-md-8">
                <div class="jumbotrom">
                    <label for="proponente">Proponente</label>
                    <input type="text" id="proponente" name="proponente"
                           class="form-control" disabled
                           value="<?= $proponente ?>">
                </div>
            </div>
            <div class="form-group col-md-2"><label><br></label>
                <form method="POST" action="<?= $link_edita ?>" role="form">
                    <input type="hidden" name="idProponente" value="<?= $idProponente ?>">
                    <button type="submit" name="editProponente" class="btn btn-primary btn-block">
                        Editar Proponente
                    </button>
                </form>
            </div>
            <div class="form-group col-md-2"><label><br></label>
                <form method="POST" action="?perfil=evento&p=troca_proponente"
                      role="form">
                    <input type="hidden" name="idPedido"
                           value="<?= $idPedido ?>">
                    <input type="hidden" name="idProponente"
                           value="<?= $idProponente ?>">
                    <button type="submit" name="trocaProponente"
                            class="btn btn-primary btn-block">Trocar de
                        Proponente
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<ul class="list-inline pull-right">
    <li>
        <a class="btn btn-default prev-step"><span
                aria-hidden="true">&larr;</span>
            Voltar</a>
    </li>
    <li>
        <a class="btn btn-primary next-step">Próxima etapa <span
                aria-hidden="true">&rarr;</span></a>
    </li>
</ul>