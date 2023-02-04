<?php
require_once('./Connection.php');
require_once('./DAO/LocacaoDaoMysql.php');
require_once('./MODELS/Auth.php');

$codigoLocacao = filter_input(INPUT_POST, "codigoLocacao", FILTER_SANITIZE_NUMBER_INT);

if (!empty($pdo)) {
    $locacaoDao = new LocacaoDaoMysql($pdo);
}

$resultOfCheck = $locacaoDao->checkPossibilityOfCancel($codigoLocacao);

if($resultOfCheck){
    $locacaoDao->cancel($codigoLocacao);
    $_SESSION['flash'] = 
    "
    <div class='isa_success'>
        <i class='fa fa-check'></i>
        Locação Cancelada com Sucesso!
    </div>
    ";
    header('Location: '.$base.'/view-locacoes.php');
}else{
    $_SESSION['flash'] = 
    "
    <div class='isa_warning'>
                    <i class='fa fa-warning'></i>
                        Não é possível cancelar.
    </div>
    ";
    header('Location: '.$base.'/view-locacoes.php');
}




