<?php
require './Connection.php';
require './MODELS/Auth.php';
require './DAO/LivroDaoMysql.php';

$auth = new Auth($pdo, $base);
$info = $auth->checkToken();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script type="text/javascript" src="http://netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"
          type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"
          integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/geral.css">
    <link rel="stylesheet" href="./assets/css/perfil.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <title>Perfil</title>
</head>

<body>
<header>
    <h1>Meu Perfil</h1>
    <h2><?=$info->nome?></h2>
</header>
<main>
    <div class="foto-usuario">
        <img src="./storage/usersImages/<?=$info->foto?>" alt="">
    </div>
    <div class="dados-usuario">
        <h3>Meus Dados</h3>
        <form name="cadastro" class="form-horizontal" role="form" action="./cad-alunos-update_action.php" method="POST">
            <form>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Nome</label>
                    <input type="email" class="form-control" id="exampleFormControlInput1" value="<?=$info->nome?>">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Email</label>
                    <input type="email" class="form-control" id="exampleFormControlInput1" value="<?=$info->email?>">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Nova Senha</label>
                    <input type="email" class="form-control" id="exampleFormControlInput1">
                </div>
                <div class="form-group">
                    <label for="alterar-foto">
                        Alterar Foto
                    </label>
                    <input type="file" name="foto" id="alterar_foto">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Alterar</button>
                </div>

            </form>
    </div>
</main>
</body>

</html>