<?php
require_once('./Connection.php');
require_once('./DAO/GeneroDaoMysql.php');
require_once('./MODELS/Auth.php');

$codigo = filter_input(INPUT_POST, 'codigoGenero', FILTER_SANITIZE_SPECIAL_CHARS);
$nome = filter_input(INPUT_POST, 'nomeGenero', FILTER_SANITIZE_SPECIAL_CHARS);

$generoDao = new GeneroDaoMysql($pdo);

$genero = new Genero();

if($codigo && $nome){
    $genero->codigo = $codigo;
    $genero->nome = $nome;

    $result = $generoDao->insert($genero);

    if($result){
        header('Location:'.$base.'/view-generos.php');
    }
}