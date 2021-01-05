<?php 

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: *');
	
	if(isset($_GET['idParcela'])){
		$id = $_GET['idParcela'];
        $tipoPessoa = $_GET['tipo'];
        $idPedido = $_GET['idPedido'];
        $server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2/pdf/";
        if ($tipoPessoa == 2) {
            $link1 = $server . "pagamento_integral_pj.php";
        } else{
            $link1 = $server . "pagamento_integral_pf.php";
        }
        $link2 = $server . "recibo_pagamento.php";
        $link3 = $server . "ateste_documentacao.php";
        $link4 = $server . "confirmacao_servico.php";
        $link5 = $server . "declaracao_simples.php";
        $link6 = $server . "ateste_documentacao.php";
        $link7 = $server . "emissao_nf.php";
        $link8 = $server . "email_empresas.php?modelo=empresas";
        $link9 = $server . "minuta.php";

        $listaPf = 
            "<form action='$link1' method='post' target='_blank' role='form'>
                <input type='hidden' name='idParcela' value='$id'>
                <button type='submit' class='btn btn-primary btn-block' name='idPedido' value='$idPedido'>Pedido Pagamento</button>
            </form>
            
            <form action='$link2' method='post' target='_blank' role='form'>
                <input type='hidden' name='idParcela' value='$id'>
                <button type='submit' class='btn btn-primary btn-block' name='idPedido' value='$idPedido'>Recibo</button>
            </form>
            
            <form action='$link3' method='post' target='_blank' role='form'>
                <input type='hidden' name='idParcela' value='$id'>
                <button type='submit' class='btn btn-primary btn-block' name='idPedido' value=' $idPedido '>Ateste</button>
            </form>
            
            <form action='$link4' method='post' target='_blank' role='form'>
                <input type='hidden' name='idParcela' value='$id'>
                <button type='submit' class='btn btn-primary btn-block' name='idPedido' value='$idPedido'>Confirmação serviço</button>
            </form>";
                       
        if($tipoPessoa == 2){
            $listaPj =    
                "<form action='$link5' method='post' target='_blank' role='form'>
                    <input type='hidden' name='idParcela' value='$id'>
                    <button type='submit' class='btn btn-primary btn-block' name='idPedido' value='$idPedido'>Declaração</button>
                 </form>
            
                 <form action='$link6' method='post' target='_blank' role='form'>
                    <input type='hidden' name='idParcela' value='$id'>
                    <button type='submit' class='btn btn-primary btn-block' name='idPedido' value='$idPedido'>Documentação</button>
                 </form>
                    
                 <form action='$link7' method='post' target='_blank' role='form'>
                    <input type='hidden' name='idParcela' value='$id'>
                    <button type='submit' class='btn btn-primary btn-block' name='idPedido' value='$idPedido'>Nota Fiscal</button>
                 </form>
                     
                 <form action='$link8' method='post' target='_blank' role='form'>
                    <input type='hidden' name='idParcela' value='$id'>
                    <button type='submit' class='btn btn-primary btn-block' name='idPedido' value='$idPedido'>Email Kit</button>
                 </form>
                                          
                 <form action='$link9 ' method='post' target='_blank' role='form'>
                    <input type='hidden' name='idParcela' value='$id'>
                    <button type='submit' class='btn btn-primary btn-block' name='idPedido' value='$idPedido'>Email NE</button>
                 </form>";

         $listafull = $listaPf . $listaPj;
        }else{
            $listafull = $listaPf;
        }

         echo $listafull;
    }
