<div class="content-wrapper">
    <section class="content">
        <div class="box box-primary">
            <h2 align="center">Escolha um modelo</h2>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-offset-4 col-md-4" align="center">
                        <form action="?perfil=juridico&p=tipo_modelo&sp=resultado" method="POST">
                            <button name="mdlPadrao" type="submit" class="btn btn-primary btn-lg btn-block">PADRÃO
                            </button>
                            <input type="hidden" value="1" name="idPadrao">
                        </form>

                        <form action="?perfil=juridico&p=tipo_modelo&sp=resultado" method="POST">
                            <button name="mdlVoca" type="submit" class="btn btn-primary btn-lg btn-block">VOCACIONA
                            </button>
                            <input type="hidden" value="2" name="idVoca">
                        </form>

                        <form action="?perfil=juridico&p=tipo_modelo&sp=resultado" method="POST">
                            <button name="mdlPia" type="submit" class="btn btn-primary btn-lg btn-block">PIÁ</button>
                            <input type="hidden" value="3" name="idPia">
                        </form>

                        <form action="?perfil=juridico&p=tipo_modelo&sp=resultado" method="POST">
                            <button name="mdlOficina" type="submit" class="btn btn-primary btn-lg btn-block">Oficinas
                            </button>
                            <input type="hidden" value="4" name="idOficina">
                        </form>
                    </div>
                </div>
            </div>
    </section>
</div>