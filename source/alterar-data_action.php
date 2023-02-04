<?php

require "./Connection.php";
require "./DAO/LocacaoDaoMysql.php";

$locacaoDao = new LocacaoDaoMysql($pdo);

$codigo = filter_input(INPUT_GET, 'codigo', FILTER_SANITIZE_NUMBER_INT);
$devolucao = filter_input(INPUT_GET, 'devolvido');
$retirada = filter_input(INPUT_GET, 'retirado');

if($devolucao == 'on' && !isset($retirada)){
    $locacaoDao->alterDateStatus('devolucao', $codigo);
    header('Location:'.$base.'/view-locacoes-adm.php?status=historico');
}else if (!isset($devolucao) && $retirada == 'on'){
    $locacaoDao->alterDateStatus('retirada', $codigo);
    header('Location:'.$base.'/view-locacoes-adm.php?status=ativo');
}else if ($devolucao == 'on' && $retirada == 'on'){
    $locacaoDao->alterDateStatus('ambos', $codigo);
    header('Location:'.$base.'/view-locacoes-adm.php?status=historico');
}else{
    header('Location:'.$base.'/view-locacoes-adm.php?status=ativo');
}
