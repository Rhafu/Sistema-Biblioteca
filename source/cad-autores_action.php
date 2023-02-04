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
    $autor->codigo = $codigo;
    $autor->nome = $nome;
    $autor->dataNascAutor = $dataNasc;

    $result = $autorDao->insert($autor);

    if($result){
        $_SESSION['flash'] = 
        "
        <div class='isa_success'>
            <i class='fa fa-check'></i>
            Autor Cadastrado com Sucesso!
        </div>
        ";
        header('Location: '.$base.'/view-autores.php');
    }else{
        $_SESSION['flash'] = 
        "
        <div class='isa_warning'>
                        <i class='fa fa-warning'></i>
                            Não foi possível cadastrar autor.
        </div>
        ";
        header('Location: '.$base.'/view-autores.php');
    }
}