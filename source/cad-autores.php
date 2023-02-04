<?php
    require './Connection.php';
    require './MODELS/Auth.php';
    require './DAO/AutorDaoMysql.php';

    $auth = new Auth($pdo, $base);
    $info = $auth->checkToken();
    $level = $auth->checkLevel();
    $autorDao = new AutorDaoMysql($pdo);
    $autores = $autorDao->selectAll();
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
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
                require_once('./templates/cadastroLivros/menu-livros.php')
            ?>
        </nav>

        <main class="main-cads">
            <div class="titulo-cads">
                <h1>Cadastro de Autor</h1>
            </div>
            <div class="linha"></div>
            <div class="linha"></div>
            <div class="cadastro-geral">
                <div class="panel-body">
                    <form name="cadastro" role="form" action="./cad-autores_action.php" method="POST" enctype="multipart/form-data">

                    <div class="form-group">
                        <div class="form-group">
                            <div class="form-label"><label for="codigoAutor">Codigo do Autor</label>
                            </div>
                            <div class="form-input"><input type="text" id="codigoAutor" name="codigoAutor" placeholder="Codigo do Autor" required autofocus></div>
                        </div>

                        <div class="form-group">
                            <div class="form-label"><label for="nome">Nome</label>
                            </div>
                            <div class="form-input"><input type="text" id="nomeAutor" name="nomeAutor" placeholder="Nome do Autor" required autofocus></div>
                        </div>

                        <div class="form-group">
                            <div class="form-label"><label for="data-nasc">Data de Nascimento<br></label>
                            </div>
                            <div class="form-input"><input type="date" name="dataNasc" id="dataNasc" required></div>
                        </div>

                        <div class="form-group">
                            <div class="cad-buttons">
                                <button type="submit">Cadastrar</button>
                                <button type="reset">Cancelar</button>
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