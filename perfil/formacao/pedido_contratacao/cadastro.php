<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Cadastro de Pedido de Contratação</h2>
        <div class="box box-primary">
            <div class="box-header">
                <h4 class="box-title">Pedido de Contratação</h4>
            </div>
            <form action="?perfil=formacao&p=pedido_contratacao&sp=edita" role="form">
                <div class="box-body">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="ano">Ano *</label>
                            <?php
                            $years = range(1900, strftime("%Y", time()));
                            ?>
                            <select>
                                <option>Selecione o Ano</option>
                                <?php foreach ($years as $year) : ?>
                                    <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="chamado">Chamado *</label>
                            <input type="text" id="chamado" name="chamado" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="classificacao">Classificação Indicativa *</label>
                            <select name="classificacao" id="classificacao" required>
                                <?php
                                geraOpcao("classificacao_indicativas");
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="territorio">Território *</label>
                            <select name="territorio" id="territorio">
                                <option>Selecione o território</option>
                                <?php
                                geraOpcao("territorios");
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="coordenadoria">Coordenadoria *</label>
                            <select name="coordenadoria" id="coordenadoria">
                                <option>Selecione a Coordenadoria </option>
                                <?php
                                geraOpcao("coordenadorias");
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="subprefeitura">Subprefeitura *</label>
                            <select name="subprefeitura" id="subprefeitura">
                                <option>Selecione a Subprefeitura </option>
                                <?php
                                geraOpcao("subprefeituras");
                                ?>
                            </select>
                        </div>
                    </div>

            </form>
        </div>
</div>
</section>
</div>