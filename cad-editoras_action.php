<?php
require_once('./Connection.php');
require_once('./DAO/EditoraDaoMysql.php');
require_once('./MODELS/Auth.php');

$codigo = filter_input(INPUT_POST, 'codigoEditora', FILTER_SANITIZE_SPECIAL_CHARS);
$nome = filter_input(INPUT_POST, 'nomeEditora', FILTER_SANITIZE_SPECIAL_CHARS);

$editoraDao = new EditoraDaoMysql($pdo);

$editora = new Editora();

if($codigo && $nome){
    $editora->codigo = $codigo;
    $editora->nome = $nome;

    $result = $editoraDao->insert($editora);

    if($result){
        header('Location:'.$base.'/view-editoras.php');
    }
}