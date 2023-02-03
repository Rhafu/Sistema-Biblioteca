<?php
require './Connection.php';
require './MODELS/Auth.php';

$auth = new Auth($pdo, $base);
$info = $auth->checkToken();
$level = $auth->checkLevel();
if($level != 'bibliotecario'){
    header('location:./index.php');
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/mensagens.css">
    <link rel="stylesheet" href="./assets/css/geral.css">
    <link rel="stylesheet" href="./assets/css/index.css">
    <link rel="stylesheet" href="./assets/css/menu-cads.css">
    <link rel="stylesheet" href="./assets/css/cadastros.css">
    <link rel="stylesheet" href="./assets/css/view-tables.css">
    <script type="text/javascript" src="./js/validacao-cad.js">
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <title>Biblioteca</title>
</head>

<body>
    <header class="container cabecalho">
        <div class="logo">
            <img src="./assets/R.png" alt="">
        </div>
        <?php
            require_once('./templates/menu/logged-funcionario.php')
        ?>
    </header>
    <div class="cads-geral container">
        <nav>
            <?php
                require_once('./templates/cadastroLivros/menu-alunos.php')
            ?>
        </nav>

        <main class="main-cads">
            <div class="titulo-cads">
                <h1>Cadastro de Alunos</h1>
            </div>
            <div class="linha"></div>
            <div class="linha"></div>
            <?php

            echo $_SESSION['flash'] ?? '';
            $_SESSION['flash'] = '';

            ?>
            <div class="cadastro-geral">
                <div class="panel-body">
                    <form name="cadastro" class="form-horizontal" role="form" action="./cad-alunos_action.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <div class="form-label"><label for="nome" class="control-label">Nome</label>
                            </div>
                            <div class="form-input"><input type="text" id="nome" name="nome" placeholder="Nome" required autofocus></div>
                        </div>

                        <div class="form-group">
                            <div class="form-label"><label for="email" class="control-label">Email</label>
                            </div>
                            <div class="form-input"><input type="email" class="form-control" id="email" name="email" placeholder="Email" required></div>
                        </div>

                        <div class="form-group">
                            <div class="form-label"><label for="senha" class="control-label">Senha<br></label>
                            </div>
                            <div class="form-input"><input type="password"  id="senha" name="senha" placeholder="Senha" required onkeyup="validaSenhaInput()"></div>
                        </div>

                        <div class="form-group">
                            <div class="form-label"><label for="senha-confirm">Confirmar
                                    Senha<br></label></div>
                            <div class="form-input">
                                <input type="password" class="form-control" placeholder="Confirmar Senha" required id="senha-confirm" onkeyup="validaSenhaInput()">
                                <span id="CheckPasswordMatch" ></span>
                            </div>
                        </div>

                        <script>
                            $(document).ready(function() {
                                $("#senha-confirm").on('keyup', function() {
                                    var password = $("#senha").val();
                                    var confirmPassword = $("#senha-confirm").val();
                                    if (password != confirmPassword)
                                        $("#CheckPasswordMatch").html("Senhas diferentes!").css("color", "red");
                                    else
                                        $("#CheckPasswordMatch").html("Senhas iguais!").css("color", "green");
                                });
                            });
                        </script>

                        <div class="form-group">
                            <div class="form-label"><label for="ra">RA</label></div>
                            <div class="form-input"><input type="text" required name="ra" id="ra" onkeypress="$(this).mask('000.000.000-00');" placeholder="000.000.000-00"></div>
                        </div>

                        <div class="form-group">
                            <div class="form-label"><label for="telefone">Telefone<br></label></div>
                            <div class="form-input"><input type="text" required name="telefone" id="telefone" onkeypress="$(this).mask('(00) 00000-0000')" placeholder="(00) 00000-0000"></div>
                        </div>

                        <div class="form-group">
                            <div class="form-label"><label for="dataNasc">Data de Nascimento<br></label>
                            </div>
                            <div class="form-input"><input type="date" name="dataNasc" id="dataNasc" required></div>
                        </div>

                        <div class="form-group">
                            <div class="form-label"><label for="alterar-foto">Alterar Foto (*.jpg, *.png, *jpeg, *gif)</label>
                            </div>
                            <div class="form-input"><input type="file" name="foto" id="alterar_foto"></div>
                        </div>

                        <div class="form-group">
                            <span id="validacao-negada-submit" style="color: red;"></span><br>
                            <div class="cad-buttons">
                                <button type="submit" class="btn btn-success" onclick="return validaSenhaSubmit()">Cadastrar</button>
                                <button type="reset" class="btn btn-default" onclick="history.go(-1)">Cancelar</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <div class="paginas">
                <a href="" style="color: wheat;">1</a>
            </div>
        </main>


</body>