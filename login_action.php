<?php
require_once('./Connection.php');
require_once('./DAO/AlunoDaoMysql.php');
require_once('./MODELS/Auth.php');

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$senha = filter_input(INPUT_POST, 'senha');

if($email && $senha){
    $auth = new Auth($pdo, $base);

    if($auth->checkLogin($email, $senha)){
        header('Location: '.$base);
        exit;
    }
}

$_SESSION['flash'] = 
"
<div class='isa_warning'>
                <i class='fa fa-warning'></i>
                    Email ou senha incorretos!.
</div>
";
header('Location: '.$base.'/login.php');
exit;