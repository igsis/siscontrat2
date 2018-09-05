<?php

if (isset($_POST['contratacao']))
{
    $contratacao = $_POST['contratacao'];
}
elseif (isset($_SESSION['idEvento']))
{
    $evento = recuperaDados("evento", "id", $_SESSION['idEvento']);
    $contratacao = $evento['contratacao'];
}
else
{
    $contratacao = null;
}

if ($contratacao != 2) {
    if ($contratacao == 1) {
        $modalidade = null;
    }
    else {
        $modalidade = "semcontratacao_";
    }
    $urlEventoPf = array(
        '/igsiscapac/visual/index.php?perfil=proponente_pf_resultado',
        '/igsiscapac/visual/index.php?perfil=informacoes_iniciais_pf',
        '/igsiscapac/visual/index.php?perfil=arquivos_pf',
        '/igsiscapac/visual/index.php?perfil=endereco_pf',
        '/igsiscapac/visual/index.php?perfil=informacoes_complementares_pf',
        '/igsiscapac/visual/index.php?perfil=dados_bancarios_pf',
        '/igsiscapac/visual/index.php?perfil=anexos_pf',
        '/igsiscapac/visual/index.php?perfil=arquivos_dados_bancarios_pf', // 07
        '/igsiscapac/visual/index.php?perfil=finalizar'
    );
    # Verifica se a pagina contem o endereço correspondente ao de pessoa Física
    for ($i = 0; $i < count($urlEventoPf); $i++) {
        if ($uri == $urlEventoPf[$i]) {
            if ($i == 0 || $i == 1) {
                $ativa1 = 'active loading';
            } elseif ($i == 2) {
                $ativa2 = 'active loading';
            } elseif ($i == 3) {
                $ativa3 = 'active loading';
            } elseif ($i == 4) {
                $ativa4 = 'active loading';
            } elseif ($i == 5) {
                $ativa5 = 'active loading';
            } elseif ($i == 6) {
                $ativa6 = 'active loading';
            } elseif ($i == 7) {
                $ativa7 = 'active loading';
            } elseif ($i == 8) {
                $ativa8 = 'active loading';
            }

            if (isset($_SESSION['idEvento'])) {
                if (isset($_SESSION['idPf'])) {
                    ?>

                    <!-- Pessoa Física      -->
                    <div id="smartwizard">
                        <ul>
                            <li class="hidden">
                                <a href=""><br/></a>
                            </li>
                            <li class="<?php echo 'done'; ?>">
                                <a onclick="location.href='index.php?perfil=evento_<?=$modalidade?>edicao'" href=""><br/>
                                    <small>Voltar para evento</small>
                                </a>
                            </li>
                            <li class="<?php echo $ativa1 ?? 'clickable'; ?>">
                                <a onclick="location.href='index.php?perfil=informacoes_iniciais_pf'" href=""><br/>
                                    <small>Informações Iniciais</small>
                                </a>
                            </li>
                            <li class="<?php echo $ativa2 ?? 'clickable'; ?>">
                                <a onclick="location.href='index.php?perfil=arquivos_pf'" href=""><br/>
                                    <small>Arquivos da Pessoa</small>
                                </a>
                            </li>
                            <li class="<?php echo $ativa3 ?? 'clickable'; ?>">
                                <a onclick="location.href='index.php?perfil=endereco_pf'" href=""><br/>
                                    <small>Endereço</small>
                                </a>
                            </li>
                            <li class="<?php echo $ativa4 ?? 'clickable'; ?>">
                                <a onclick="location.href='index.php?perfil=informacoes_complementares_pf'"
                                   href=""><br/>
                                    <small>Informações Complementares</small>
                                </a>
                            </li>
                            <li class="<?php echo $ativa5 ?? 'clickable'; ?>">
                                <a onclick="location.href='index.php?perfil=dados_bancarios_pf'" href=""><br/>
                                    <small>Dados Bancários</small>
                                </a>
                            </li>
                        </ul>
                        <ul>
                            <li class="<?php echo $ativa7 ?? 'clickable'; ?>">
                                <a onclick="location.href='index.php?perfil=arquivos_dados_bancarios_pf'" href=""><br/>
                                    <small>Arquivos Dados Bancários</small>
                                </a>
                            </li>
                            <li class="<?php echo $ativa6 ?? 'clickable'; ?>">
                                <a onclick="location.href='index.php?perfil=anexos_pf'" href=""><br/>
                                    <small>Demais Anexos</small>
                                </a>
                            </li>
                            <li class="<?php echo $ativa8 ?? 'clickable'; ?>">
                                <a onclick="location.href='index.php?perfil=finalizar'" href=""><br/>
                                    <small>Finalizar</small>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <?php
                }
            }
        }
    }
}
else
{
    $urlEventoPf = array(
        '/igsiscapac/visual/index.php?perfil=proponente_pf_resultado',
        '/igsiscapac/visual/index.php?perfil=informacoes_iniciais_pf',
        '/igsiscapac/visual/index.php?perfil=arquivos_pf',
        '/igsiscapac/visual/index.php?perfil=endereco_pf',
        '/igsiscapac/visual/index.php?perfil=informacoes_complementares_pf',
        '/igsiscapac/visual/index.php?perfil=anexos_pf',
        '/igsiscapac/visual/index.php?perfil=finalizar'
    );
    # Verifica se a pagina contem o endereço correspondente ao de pessoa Física
    for ($i = 0; $i < count($urlEventoPf); $i++) {
        if ($uri == $urlEventoPf[$i]) {
            if ($i == 0 || $i == 1) {
                $ativa1 = 'active loading';
            } elseif ($i == 2) {
                $ativa2 = 'active loading';
            } elseif ($i == 3) {
                $ativa3 = 'active loading';
            } elseif ($i == 4) {
                $ativa4 = 'active loading';
            } elseif ($i == 5) {
                $ativa5 = 'active loading';
            } elseif ($i == 6) {
                $ativa6 = 'active loading';
            }

            if (isset($_SESSION['idEvento'])) {
                if (isset($_SESSION['idPf'])) {
                    ?>

                    <!-- Pessoa Física      -->
                    <div id="smartwizard">
                        <ul>
                            <li class="hidden">
                                <a href=""><br/></a>
                            </li>
                            <li class="<?php echo 'done'; ?>">
                                <a onclick="location.href='index.php?perfil=evento_semcache_edicao'" href=""><br/>
                                    <small>Voltar para evento</small>
                                </a>
                            </li>
                            <li class="<?php echo $ativa1 ?? 'clickable'; ?>">
                                <a onclick="location.href='index.php?perfil=informacoes_iniciais_pf'" href=""><br/>
                                    <small>Informações Iniciais</small>
                                </a>
                            </li>
                            <li class="<?php echo $ativa2 ?? 'clickable'; ?>">
                                <a onclick="location.href='index.php?perfil=arquivos_pf'" href=""><br/>
                                    <small>Arquivos da Pessoa</small>
                                </a>
                            </li>
                            <li class="<?php echo $ativa3 ?? 'clickable'; ?>">
                                <a onclick="location.href='index.php?perfil=endereco_pf'" href=""><br/>
                                    <small>Endereço</small>
                                </a>
                            </li>
                            <li class="<?php echo $ativa4 ?? 'clickable'; ?>">
                                <a onclick="location.href='index.php?perfil=informacoes_complementares_pf'"
                                   href=""><br/>
                                    <small>Informações Complementares</small>
                                </a>
                            </li>
                            <li class="<?php echo $ativa5 ?? 'clickable'; ?>">
                                <a onclick="location.href='index.php?perfil=anexos_pf'" href=""><br/>
                                    <small>Demais Anexos</small>
                                </a>
                            </li>
                            <li class="<?php echo $ativa6 ?? 'clickable'; ?>">
                                <a onclick="location.href='index.php?perfil=finalizar'" href=""><br/>
                                    <small>Finalizar</small>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <?php
                }
            }
        }
    }
}
?>