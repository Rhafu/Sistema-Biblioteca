<?php
require_once('./Connection.php');
require_once('./DAO/AutorDaoMysql.php');
require_once('./MODELS/Auth.php');

$codigo = filter_input(INPUT_POST, 'codigoAutor', FILTER_SANITIZE_SPECIAL_CHARS);
$nome = filter_input(INPUT_POST, 'nomeAutor', FILTER_SANITIZE_SPECIAL_CHARS);
$dataNasc = filter_input(INPUT_POST, 'dataNasc');

$autorDao = new AutorDaoMysql($pdo);

$autor = new Autor();

if($codigo && $nome && $dataNasc){
    echo $autor->codigo = $codigo;
    echo $autor->nome = $nome;
    $autor->dataNascAutor = $dataNasc;

    $result = $autorDao->update($autor);

    if($result){
        $_SESSION['flash'] = "
            <div class='isa_success'>
                <i class='fa fa-check'></i>
                Autor Atualizado com Sucessor.
            </div>
            ";
        header('Location:'.$base.'/view-autores.php');
    }else{
        $_SESSION['flash'] = "
            <div class='isa_success'>
                <i class='fa fa-check'></i>
                Erro na atualização de autor
            </div>
            ";
        header('Location:'.$base.'/view-autores.php');
    }
}