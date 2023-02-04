<?php
require_once('./Connection.php');
require_once('./DAO/BibliotecarioDaoMysql.php');
require_once('./MODELS/Auth.php');

$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$senha = filter_input(INPUT_POST, 'senha');
$telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS);
$cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_SPECIAL_CHARS);
//TIRAR OS PONTOS DO RA
$dataNasc = filter_input(INPUT_POST, 'dataNasc', FILTER_SANITIZE_SPECIAL_CHARS);
$foto = filter_input(INPUT_POST, 'foto');

$bibliotecarioDao = new BibliotecarioDaoMysql($pdo);
$auth = new Auth($pdo, $base);


$photoResult = $auth->updateImage('./storage/usersImages/');

if($email && $senha && $photoResult){
    //VERIFICAR SE O ALUNO JA EXISTE*******
    $bibliotecario = new Bibliotecario();

    $bibliotecario->nome = $nome;
    $bibliotecario->email = $email;
    $bibliotecario->telefone = $telefone;
    $bibliotecario->cpf = str_replace(array('-', '.'), '', $cpf);
    $bibliotecario->dataNasc = $dataNasc;
    $bibliotecario->foto = $photoResult;
    $bibliotecario->senha = password_hash($senha, PASSWORD_DEFAULT);
    $bibliotecario->token = '';

    $result = $bibliotecarioDao->insert($bibliotecario);

    if($result){
        header('Location:'.$base.'/view-bibliotecarios.php');
        $_SESSION['flash'] = "
            <div class='isa_success'>
                <i class='fa fa-check'></i>
                Cadastro Realizado com Sucesso
            </div>
            ";
    }else{
        header('Location:'.$base.'/cad-bibliotecarios.php');
        $_SESSION['flash'] = "
            <div class='isa_error'>
                <i class='fa fa-warning'></i>
                Email ou CPF já cadastrados!
            </div>
            ";
    }

}