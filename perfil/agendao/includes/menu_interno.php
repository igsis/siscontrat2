<?php
$pasta = "?perfil=agendao&p=";
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="http://<?=$_SERVER['HTTP_HOST']?>/siscontrat/inicio"><i class="fa fa-home"></i><span>Home</span></a></li>
            <li class="header">EVENTO</li>
            <?php
            if(isset($_SESSION['idEvento'])){
                echo "<li><a href=\"".$pasta."evento_edita\"><i class='fa fa-circle-o text-green'></i> <span>Evento</span></a></li>";
            }
            else{
                echo "<li><a href=\"".$pasta."evento_cadastro\"><i class='fa fa-circle-o text-green'></i> <span>Evento</span></a></li>";
            }

            if(isset($_SESSION['idEvento'])){
                $agendao = recuperaDados("agendoes","id",$_SESSION['idEvento']);
                $idProdutor = $agendao['produtor_id'];
               if($idProdutor == NULL){
                    echo "<li><a href=\"".$pasta."produtor_cadastro\"><i class='fa fa-circle-o text-olive'></i> <span>Produtor</span></a></li>";
               }
               else{
                   echo "<li><a href=\"".$pasta."produtor_edita\"><i class='fa fa-circle-o text-olive'></i> <span>Produtor</span></a></li>";
               }
            }
            ?>
            <li><a href="<?=$pasta?>ocorrencia_lista"><i class='fa fa-circle-o text-blue'></i> <span>Ocorrência</span></a></li>
            <li><a href="<?=$pasta?>finalizar"><i class='fa fa-circle-o text-light-blue'></i> <span>Finalizar</span></a></li>
            <li><a href="?perfil=agendao"><i class="fa fa-reply"></i> <span>Voltar</span></a></li>
            <?php
            include "../perfil/includes/menu_mais.php";
            ?>
        </ul>
    </section>
</aside>