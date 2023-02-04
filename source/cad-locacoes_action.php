<?php
require_once('./Connection.php');
require_once('./DAO/LocacaoDaoMysql.php');
require_once('./MODELS/Auth.php');

$codigoLivro = filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_SPECIAL_CHARS);
$ra = filter_input(INPUT_POST, 'ra', FILTER_SANITIZE_SPECIAL_CHARS);
$dataDe = filter_input(INPUT_POST, 'dataDe');
$dataAte = date('Y-m-d', strtotime($dataDe. ' + 7 days'));


if (!empty($pdo)) {
    $locacaoDao = new LocacaoDaoMysql($pdo);
}

$locacao = new Locacao();

$isAvailable = $locacaoDao->isAvailable($codigoLivro, $dataDe, $dataAte, $ra);

if($dataDe && $dataAte && $isAvailable){
    $locacao->codigoLivro = $codigoLivro;
    $locacao->raAluno = $ra;
    $locacao->dataInicio = $dataDe;
    $locacao->dataTermino = $dataAte;

    
    $insertResult = $locacaoDao->insert($locacao);

    if($insertResult){
        $_SESSION['flash'] = 
        "
        <div class='isa_success'>
            <i class='fa fa-check'></i>
            Locação Realizada com Sucesso!
        </div>
        ";
        header('Location: '.$base.'/view-locacoes.php');
    }

 
}else{
    header('Location: '.$base.'/view-locacoes.php');
}

/*TESTE MYSQL EVERY 1 DAY STARTS CURRENT_TIMESTAMP + INTERVAL '7:55' HOUR_MINUTE


CREATE EVENT verificaAtraso ON SCHEDULE EVERY 1 MINUTE
DO
    UPDATE locacoes SET locacoes.atrasado = 1 WHERE locacoes.ativo = 1 AND
    locacoes.dataDevolucao is null AND locacoes.dataTermino < CURRENT_DATE;*/