<?php
require_once('./Connection.php');
require_once('./DAO/BibliotecarioDaoMysql.php');
require_once('./MODELS/Auth.php');

//VERIFICAR FILTROs
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$senhaNova = filter_input(INPUT_POST, 'senha');
$senhaAntiga = filter_input(INPUT_POST, 'senhaAntiga');
$telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS);
$cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_SPECIAL_CHARS);
//TIRAR OS PONTOS DO RA
$dataNasc = filter_input(INPUT_POST, 'dataNasc', FILTER_SANITIZE_SPECIAL_CHARS);
$tokenAntigo = filter_input(INPUT_POST, 'tokenAntigo', FILTER_SANITIZE_SPECIAL_CHARS);
$foto = filter_input(INPUT_POST, 'foto');
$fotoAntiga = filter_input(INPUT_POST, 'fotoAntiga');

$bibliotecarioDao = new BibliotecarioDaoMysql($pdo);
$auth = new Auth($pdo, $base);

if($_FILES['foto']['error'] != 4){
    $auth->deleteImagem('./storage/usersImages/', $fotoAntiga);
    $photoResult = $auth->updateImage('./storage/usersImages/');
}else{
    $photoResult = $fotoAntiga;
}

if($senhaNova == ''){
    $senha = $senhaAntiga;
}else{
    $senha = $senhaNova;
}


if($email && $senha && $photoResult){
    //VERIFICAR SE O ALUNO JA EXISTE*******
    $bibliotecario = new Bibliotecario();

    $bibliotecario->nome = $nome;
    $bibliotecario->email = $email;
    $bibliotecario->telefone = $telefone;
    echo $bibliotecario->cpf = str_replace(array('-', '.'), '', $cpf);
    $bibliotecario->dataNasc = $dataNasc;
    $bibliotecario->foto = $photoResult;
    if ($senhaNova == ''){
        $bibliotecario->senha = $senha;
    }else{
        $bibliotecario->senha = password_hash($senha, PASSWORD_DEFAULT);
    }

    if($tokenAntigo == $_SESSION['token']){
        $bibliotecario->token = $tokenAntigo;
    }else {
        $bibliotecario->token = '';
    }

    $result = $bibliotecarioDao->update($bibliotecario);

    if($result){
        header('Location:'.$base.'/view-bibliotecarios.php');
        $_SESSION['flash'] = "
            <div class='isa_success'>
                <i class='fa fa-check'></i>
                Atualização Realizada com Sucesso
            </div>
            ";
    }else{
        header('Location:'.$base.'/cad-bibliotecarios-update.php?cpf='.$bibliotecario->cpf);
        $_SESSION['flash'] = "
            <div class='isa_error'>
                <i class='fa fa-warning'></i>
                Email ou CPF já cadastrados!
            </div>
            ";
    }


}