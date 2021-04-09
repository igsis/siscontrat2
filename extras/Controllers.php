<?php
require_once "MainModel.php";

class Controllers extends MainModel
{
    public function recuperaEvento($idEvento)
    {
        return DbModel::consultaSimples("
            SELECT  e.protocolo, e.tipo_evento_id, e.nome_evento, e.espaco_publico, e.fomento, f.fomento AS fomento_nome, rj.relacao_juridica, pe.projeto_especial, e.sinopse, uf.nome_completo AS fiscal_nome, uf.rf_rg AS fiscal_rf, us.nome_completo AS suplente_nome, us.rf_rg AS suplente_rf, uf.nome_completo AS user_nome, e.nome_responsavel, e.tel_responsavel
            FROM eventos AS e
            LEFT JOIN evento_fomento ef on e.id = ef.evento_id
            LEFT JOIN fomentos f on ef.fomento_id = f.id
            INNER JOIN relacao_juridicas rj on e.relacao_juridica_id = rj.id
            INNER JOIN projeto_especiais pe on e.projeto_especial_id = pe.id
            INNER JOIN usuarios uf on e.fiscal_id = uf.id
            INNER JOIN usuarios us on e.suplente_id = us.id
            INNER JOIN usuarios ur on e.usuario_id = ur.id
            WHERE e.id = '$idEvento'
        ")->fetchObject();
    }

    public function recuperaPublico($idEvento):string
    {
        $publicos = DbModel::consultaSimples("SELECT p.publico FROM evento_publico AS ep INNER JOIN publicos p on ep.publico_id = p.id WHERE evento_id='$idEvento'")->fetchAll(PDO::FETCH_OBJ);
        $lista = "";
        foreach ($publicos as $publico) {
            $lista .= $publico->publico . ", ";
        }
        return substr($lista,0,-2);
    }

    public function recuperaAtracao($idEvento)
    {
        return DbModel::consultaSimples("
            SELECT * FROM atracoes a 
                INNER JOIN classificacao_indicativas ci on a.classificacao_indicativa_id = ci.id 
                LEFT JOIN produtores p on a.produtor_id = p.id
            WHERE evento_id = '$idEvento' AND publicado = 1")->fetchAll(PDO::FETCH_OBJ);
    }

    public function recuperaAcaoAtracao($idAtracao):string
    {
        $acoes = DbModel::consultaSimples("SELECT a.acao FROM acao_atracao at INNER JOIN acoes a on at.acao_id = a.id WHERE atracao_id = '$idAtracao'")->fetchAll(PDO::FETCH_OBJ);
        $lista = "";
        foreach ($acoes as $acao) {
            $lista .= $acao->acao . ", ";
        }
        return substr($lista,0,-2);
    }

    public function recuperaIntegrante($idAtracao)
    {
        return DbModel::consultaSimples("SELECT * FROM integrantes i INNER JOIN atracao_integrante ai on i.id = ai.integrante_id WHERE atracao_id = '$idAtracao'")->fetchAll(PDO::FETCH_OBJ);
    }

    public function recuperaFilme($idEvento)
    {
        return DbModel::consultaSimples("SELECT * FROM filme_eventos fe INNER JOIN filmes f on fe.filme_id = f.id WHERE evento_id = '$idEvento'")->fetchAll(PDO::FETCH_OBJ);
    }

    public function recuperaOcorrencia($idEvento)
    {
        return DbModel::consultaSimples("
            SELECT o.*, i.sigla,l.local,e.espaco,s.subprefeitura,ri.retirada_ingresso 
            FROM ocorrencias o
            LEFT JOIN instituicoes i on o.instituicao_id = i.id
            LEFT JOIN locais l on o.local_id = l.id
            LEFT JOIN espacos e on o.espaco_id = e.id
            LEFT JOIN subprefeituras s on o.subprefeitura_id = s.id
            LEFT JOIN retirada_ingressos ri on o.retirada_ingresso_id = ri.id
            WHERE origem_ocorrencia_id = '$idEvento' AND o.publicado = 1")->fetchAll(PDO::FETCH_OBJ);
    }

    public function recuperaOcorrenciaOrigem($tipo,$origem)
    {
        if ($tipo == 1){ // atração
            $origem = DbModel::consultaSimples("SELECT nome_atracao FROM atracoes WHERE id = '$origem'")->fetchColumn();
        } elseif ($tipo == 2){ // filme
            $origem = DbModel::consultaSimples("SELECT titulo FROM filmes WHERE id = '$origem'")->fetchColumn();
        }
        return $origem;
    }

    public function recuperaOcorrenciaExcecao($idAtracao):string
    {
        $datas = DbModel::consultaSimples("SELECT data_excecao FROM ocorrencia_excecoes WHERE atracao_id = '$idAtracao'")->fetchAll(PDO::FETCH_OBJ);
        $lista = "";
        foreach ($datas as $data) {
            $lista .= date('d/m/Y', strtotime($data->data_excecao)) . ", ";
        }
        return substr($lista,0,-2);
    }

    public function diadasemanaocorrencia($idOcorrencia){
        $array = [];
        $ocorrencia = DbModel::consultaSimples("SELECT segunda,terca,quarta,quinta,sexta,sabado,domingo FROM ocorrencias WHERE id = '$idOcorrencia'")->fetch(PDO::FETCH_ASSOC);

        if($ocorrencia['domingo'] == 1){
            array_push($array, "domingo");
        }
        if($ocorrencia['segunda'] == 1){
            array_push($array,"segunda");
        }
        if($ocorrencia['terca'] == 1){
            array_push($array, "terça");
        }
        if($ocorrencia['quarta'] == 1){
            array_push($array, "quarta");
        }
        if($ocorrencia['quinta'] == 1){
            array_push($array,"quinta");
        }
        if($ocorrencia['sexta'] == 1){
            array_push($array, "sexta");
        }
        if($ocorrencia['sabado'] == 1){
            array_push($array, "sábado");
        }
        return implode(", ",$array);
    }

    public function recuperaArquivoComProd($idEvento)
    {
        return DbModel::consultaSimples("SELECT * FROM arquivos as arq INNER JOIN lista_documentos AS ld ON arq.lista_documento_id = ld.id  WHERE arq.origem_id = '$idEvento' AND arq.publicado = '1' ORDER BY arq.id")->fetchAll(PDO::FETCH_OBJ);
    }

    public function recuperaPedido($idEvento)
    {
        return DbModel::consultaSimples("
            SELECT p.* , v.verba, ps.status
            FROM pedidos AS p 
                LEFT JOIN verbas v on p.verba_id = v.id
                LEFT JOIN pedido_status ps on ps.id = p.status_pedido_id
            WHERE p.publicado = 1 AND p.origem_id = '$idEvento'")->fetchObject();
    }

    public function recuperaPessoaFisica($id) {
        //$id = MainModel::decryption($id);
        $pf = DbModel::consultaSimples(
            "SELECT pf.*, pe.*, pb.*, d.*, n.*, n2.nacionalidade, b.banco, b.codigo, pd.*, e.descricao, gi.grau_instrucao
            FROM pessoa_fisicas AS pf
            LEFT JOIN pf_enderecos pe on pf.id = pe.pessoa_fisica_id
            LEFT JOIN pf_bancos pb on pf.id = pb.pessoa_fisica_id
            LEFT JOIN drts d on pf.id = d.pessoa_fisica_id
            LEFT JOIN nits n on pf.id = n.pessoa_fisica_id
            LEFT JOIN nacionalidades n2 on pf.nacionalidade_id = n2.id
            LEFT JOIN bancos b on pb.banco_id = b.id
            LEFT JOIN pf_detalhes pd on pf.id = pd.pessoa_fisica_id
            LEFT JOIN etnias e on pd.etnia_id = e.id
            LEFT JOIN grau_instrucoes gi on pd.grau_instrucao_id = gi.id
            WHERE pf.id = '$id'");

        $pf = $pf->fetch(PDO::FETCH_ASSOC);
        $telefones = DbModel::consultaSimples("SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$id'")->fetchAll(PDO::FETCH_ASSOC);

        foreach ($telefones as $key => $telefone) {
            $pf['telefones']['tel_'.$key] = $telefone['telefone'];
        }
        return $pf;
    }

    public function recuperaPessoaJuridica($id)
    {
        //$id = MainModel::decryption($id);
        $pj = DbModel::consultaSimples(
            "SELECT * FROM pessoa_juridicas AS pj
            LEFT JOIN pj_enderecos pe on pj.id = pe.pessoa_juridica_id
            LEFT JOIN pj_bancos pb on pj.id = pb.pessoa_juridica_id
            LEFT JOIN bancos bc on pb.banco_id = bc.id
            WHERE pj.id = '$id'
        ");
        $pj = $pj->fetch(PDO::FETCH_ASSOC);
        $telefones = DbModel::consultaSimples("SELECT * FROM pj_telefones WHERE pessoa_juridica_id = '$id'")->fetchAll(PDO::FETCH_ASSOC);

        foreach ($telefones as $key => $telefone) {
            $pj['telefones']['tel_' . $key] = $telefone['telefone'];
        }

        return $pj;
    }

    public function recuperaRepresentante($id) {
        //$id = MainModel::decryption($id);
        return DbModel::getInfo('representante_legais',$id);
    }

    public function recuperaArquivoPedido($idPedido)
    {
        return DbModel::consultaSimples("SELECT * FROM lista_documentos as list
            INNER JOIN arquivos as arq ON arq.lista_documento_id = list.id
            WHERE arq.origem_id = '$idPedido' AND list.tipo_documento_id = 3
            AND arq.publicado = '1' ORDER BY arq.id")->fetchAll(PDO::FETCH_OBJ);
    }

    public function recuperaValorLocal($idPedido)
    {
        return DbModel::consultaSimples("SELECT l.local, ve.valor FROM valor_equipamentos AS ve
            INNER JOIN locais l on ve.local_id = l.id
            WHERE pedido_id = '$idPedido'")->fetchAll(PDO::FETCH_OBJ);
    }

    public function recuperaParecer($idPedido)
    {
        return DbModel::consultaSimples("SELECT * FROM parecer_artisticos WHERE pedido_id = '$idPedido'")->fetchObject();
    }

    public function recuperaPedidoEtapas($idPedido)
    {
        return DbModel::consultaSimples("SELECT * FROM pedido_etapas WHERE pedido_id = '$idPedido'")->fetchAll(PDO::FETCH_OBJ);
    }

}