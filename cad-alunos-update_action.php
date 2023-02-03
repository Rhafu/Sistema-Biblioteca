<?php

require_once('./Connection.php');
require_once('./DAO/AlunoDaoMysql.php');
require_once('./MODELS/Auth.php');

$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$senhaNova = filter_input(INPUT_POST, 'senha');
$senhaAntiga = filter_input(INPUT_POST, 'senhaAntiga');
$telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS);
$ra = filter_input(INPUT_POST, 'ra', FILTER_SANITIZE_SPECIAL_CHARS);
//TIRAR OS PONTOS DO RA
$dataNasc = filter_input(INPUT_POST, 'dataNasc', FILTER_SANITIZE_SPECIAL_CHARS);
$foto = filter_input(INPUT_POST, 'foto');
$fotoAntiga = filter_input(INPUT_POST, 'fotoAntiga');

$alunoDao = new AlunoDaoMysql($pdo);
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
    $aluno = new Aluno();

    $aluno->nome = $nome;
    $aluno->email = $email;
    $aluno->telefone = $telefone;
    $aluno->ra = str_replace(array('-', '.'), '', $ra);
    $aluno->dataNasc = $dataNasc;
    $aluno->foto = $photoResult;
    if($senhaNova == ''){
        $aluno->senha = $senha;
    }else{
        $aluno->senha = password_hash($senha, PASSWORD_DEFAULT);
    }
    $aluno->token = '';

    $result = $alunoDao->update($aluno);

    if($result){
        header('Location:'.$base.'/view-alunos.php');
        $_SESSION['flash'] = "
            <div class='isa_success'>
                <i class='fa fa-check'></i>
                Aluno Atualizado com Sucessor.
            </div>
            ";
    }else{
        header('Location:'.$base.'/cad-alunos.php');
        $_SESSION['flash'] = "
            <div class='isa_error'>
                <i class='fa fa-warning'></i>
                Email ou RA já cadastrados!
            </div>
            ";
    }

}

