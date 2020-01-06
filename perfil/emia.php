<?php

include "../perfil/emia/includes/menu.php";

if (isset($_GET['p'])) {
    if (isset($_GET['sp'])) {
        $p = $_GET['p'];
        $sp = $_GET['sp'];


        if ($p == 'administrativo') {

            include "../perfil/emia/includes/menu_adm.php";
        }

        if (isset($_GET['spp'])) {
            $spp = $_GET['spp'];
            include "emia/" . $p . "/" . $sp . "/" . $spp . ".php";
        } else {
            include "emia/" . $p . "/" . $sp . ".php";
        }
    }
} else {
    $p = "index";
    include "emia/" . $p . ".php";
}

function modalUploadArquivoUnico($idModal, $pagina, $nomeArquivo, $sigla, $idPessoa, $tipoPessoa)
{
    if($idModal == "modal-foto"){
        echo "
    <div class=\"modal fade\" id=\"$idModal\">
        <div class=\"modal-dialog\">
            <div class=\"modal-content\">
                <div class=\"modal-header\">
                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                        <span aria-hidden=\"true\">&times;</span></button>
                    <h4 class=\"modal-title\">Upload $nomeArquivo</h4>
                </div>
                <div class=\"modal-body\">
               
                    <p align='center'><strong>Arquivo somente em JPG/PNG e até 05 MB.</strong></p>
                    <form method=\"POST\" action=\"$pagina\" enctype=\"multipart/form-data\">
                        <br/><div align='center'><input type='file' name='arquivo[$sigla]'></div> 
                        <br/>
                        <input type=\"hidden\" name=\"idPessoa\" value=\"$idPessoa\"  />
                        <input type=\"hidden\" name=\"tipoPessoa\" value=\"$tipoPessoa\"  />
                </div>
                <div class=\"modal-footer\">                
                    <button type=\"submit\" name=\"enviar\" class=\"btn btn-success\">Enviar</button>
                    <button type=\"button\" class=\"btn btn-default pull-left\" data-dismiss=\"modal\">Fechar</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    ";
    }else {
        echo "
    <div class=\"modal fade\" id=\"$idModal\">
        <div class=\"modal-dialog\">
            <div class=\"modal-content\">
                <div class=\"modal-header\">
                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                        <span aria-hidden=\"true\">&times;</span></button>
                    <h4 class=\"modal-title\">Upload $nomeArquivo</h4>
                </div>
                <div class=\"modal-body\">
                    <p align='center'><strong>Arquivo somente em PDF e até 05 MB.</strong></p>
                    <form method=\"POST\" action=\"$pagina\" enctype=\"multipart/form-data\">
                        <br/><div align='center'><input type='file' name='arquivo[$sigla]'></div> 
                        <br/>
                        <input type=\"hidden\" name=\"idPessoa\" value=\"$idPessoa\"  />
                        <input type=\"hidden\" name=\"tipoPessoa\" value=\"$tipoPessoa\"  />
                </div>
                <div class=\"modal-footer\">                
                    <button type=\"submit\" name=\"enviar\" class=\"btn btn-success\">Enviar</button>
                    <button type=\"button\" class=\"btn btn-default pull-left\" data-dismiss=\"modal\">Fechar</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    ";
    }
}
