<?php
require_once('./Connection.php');
require_once('./DAO/IdiomaDaoMysql.php');
require_once('./MODELS/Auth.php');

$codigo = filter_input(INPUT_POST, 'codigoIdioma', FILTER_SANITIZE_SPECIAL_CHARS);
$nome = filter_input(INPUT_POST, 'nomeIdioma', FILTER_SANITIZE_SPECIAL_CHARS);

$idiomaDao = new IdiomaDaoMysql($pdo);

$idioma = new Idioma();

if($codigo && $nome){
    $idioma->codigo = $codigo;
    $idioma->nome = $nome;

    $result = $idiomaDao->insert($idioma);

    if($result){
        header('Location:'.$base.'/view-idiomas.php');
    }
}