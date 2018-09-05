<?php
# Endereços das urls do menu de Eventos
if (isset($_POST['contratacao']))
{
    $contratacao = $_POST['contratacao'];
}
elseif (isset($_POST['carregar']))
{
    $evento = recuperaDados("evento", "id", $_POST['carregar']);
    $contratacao = $evento['contratacao'];
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

if ($contratacao != 3) {
    if ($contratacao == 1) {
        $modalidade = null;
    }
    else {
        $modalidade = "semcache_";
    }
            $urlMenuEvento = array(
        '/igsiscapac/visual/index.php?perfil=evento_'.$modalidade.'edicao',
        '/igsiscapac/visual/index.php?perfil=arquivos_evento',
        '/igsiscapac/visual/index.php?perfil=produtor_novo',
        '/igsiscapac/visual/index.php?perfil=produtor_edicao',
        '/igsiscapac/visual/index.php?perfil=arquivos_com_prod',
        '/igsiscapac/visual/index.php?perfil=proponente'
    );
    for ($i = 0; $i < count($urlMenuEvento); $i++) {
        if ($uri == $urlMenuEvento[$i]) {
              if ($i == 0) {
                $acionar1 = 'active loading';
            } elseif ($i == 1) {
                $acionar2 = 'active loading';
            } elseif ($i == 2 || $i == 3) {
                $acionar3 = 'active loading';
            } elseif ($i == 4) {
                $acionar4 = 'active loading';
            } elseif ($i == 5) {
                $acionar5 = 'active loading';
            }

            ?>
            <!-- SmartWizard html -->
            <div id="smartwizard">
                <ul>
                    <li class="hidden">
                        <a href=""><br/></a>
                    </li>
                    <li class="<?php echo isset($acionar1) ? $acionar1 : 'clickable'; ?>">
                        <a onclick="location.href='index.php?perfil=evento_edicao'" href=""><br/> Informações Gerais do
                            Evento</a>
                    </li>
                    <li class="<?php echo isset($acionar2) ? $acionar2 : 'clickable'; ?>">
                        <a onclick="location.href='index.php?perfil=arquivos_evento'" href=""><br/>Arquivos do
                            Evento</a>
                    </li>
                    <li class="<?php echo isset($acionar3) ? $acionar3 : 'clickable'; ?>">
                        <a onclick="location.href='index.php?perfil=produtor'" href=""><br/>Dados do Produtor</a>
                    </li>
                    <li class="<?php echo isset($acionar4) ? $acionar4 : 'clickable'; ?>">
                        <a onclick="location.href='index.php?perfil=arquivos_com_prod'" href=""><br/>Arquivos Para
                            Comunicação e Produção</a>
                    </li>
                    <li class="<?php echo isset($acionar5) ? $acionar5 : 'clickable'; ?>">
                        <a onclick="location.href='index.php?perfil=proponente'" href=""><br/>Cadastro do Proponente</a>
                    </li>
                    <!-- <li class=""><a href="#step-6"><br /><small>Informações Iniciais</small></a></li>    -->
                </ul>
            </div>
            <?php
        }
    }
}

else
{
    $urlMenuEvento = array(
        '/igsiscapac/visual/index.php?perfil=evento_semcontratacao_edicao',
        '/igsiscapac/visual/index.php?perfil=produtor_novo',
        '/igsiscapac/visual/index.php?perfil=produtor_edicao',
        '/igsiscapac/visual/index.php?perfil=arquivos_com_prod',
        '/igsiscapac/visual/index.php?perfil=finalizar'
    );
    for ($i = 0; $i < count($urlMenuEvento); $i++) {
        if ($uri == $urlMenuEvento[$i]) {
            if ($i == 0) {
                $acionar1 = 'active loading';
            } elseif ($i == 1 || $i == 2) {
                $acionar2 = 'active loading';
            } elseif ($i == 3) {
                $acionar3 = 'active loading';
            } elseif ($i == 4) {
                $acionar4 = 'active loading';
            }

            ?>
            <!-- SmartWizard html -->
            <div id="smartwizard">
                <ul>
                    <li class="hidden">
                        <a href=""><br/></a>
                    </li>
                    <li class="<?php echo isset($acionar1) ? $acionar1 : 'clickable'; ?>">
                        <a onclick="location.href='index.php?perfil=evento_semcontratacao_edicao'" href=""><br/> Informações Gerais do
                            Evento</a>
                    </li>
                    <li class="<?php echo isset($acionar2) ? $acionar2 : 'clickable'; ?>">
                        <a onclick="location.href='index.php?perfil=produtor'" href=""><br/>Dados do Produtor</a>
                    </li>
                    <li class="<?php echo isset($acionar3) ? $acionar3 : 'clickable'; ?>">
                        <a onclick="location.href='index.php?perfil=arquivos_com_prod'" href=""><br/>Arquivos Para
                            Comunicação e Produção</a>
                    </li>
                    <li class="<?php echo isset($acionar4) ? $acionar4 : 'clickable'; ?>">
                        <a onclick="location.href='index.php?perfil=finalizar'" href=""><br/>Finalizar</a>
                    </li>
                    <!-- <li class=""><a href="#step-6"><br /><small>Informações Iniciais</small></a></li>    -->
                </ul>
            </div>
            <?php
        }
    }
}
?>