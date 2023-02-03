<?php
require_once('./Connection.php');
require_once('./DAO/AlunoDaoMysql.php');
require_once('./MODELS/Auth.php');

$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$senha = filter_input(INPUT_POST, 'senha');
$telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS);
$ra = filter_input(INPUT_POST, 'ra', FILTER_SANITIZE_SPECIAL_CHARS);
//TIRAR OS PONTOS DO RA
$dataNasc = filter_input(INPUT_POST, 'dataNasc', FILTER_SANITIZE_SPECIAL_CHARS);
$foto = filter_input(INPUT_POST, 'foto');
$token = md5(time().rand(0, 9999));


$alunoDao = new AlunoDaoMysql($pdo);
$auth = new Auth($pdo, $base);


$photoResult = $auth->updateImage('./storage/usersImages/');

if($email && $senha && $photoResult){
    //VERIFICAR SE O ALUNO JA EXISTE*******
    $aluno = new Aluno();

    $aluno->nome = $nome;
    $aluno->email = $email;
    $aluno->telefone = $telefone;
    $aluno->ra = str_replace(array('-', '.'), '', $ra);
    $aluno->dataNasc = $dataNasc;
    $aluno->foto = $photoResult;
    $aluno->senha = password_hash($senha, PASSWORD_DEFAULT);
    $aluno->token = $token;

    $_SESSION['foto'] = $aluno->foto;

    $result = $alunoDao->insert($aluno);

    if($result){
        $_SESSION['token'] = $aluno->token;
        header('Location:'.$base);
    }else{
        header('Location:'.$base.'/cadastrar.php');
        $_SESSION['flash'] = "
            <div class='isa_error'>
                <i class='fa fa-warning'></i>
                Email ou RA já cadastrados!
            </div>
            ";
    }

}
