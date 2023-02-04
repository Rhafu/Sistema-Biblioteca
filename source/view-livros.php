<?php
    require './Connection.php';
    require './MODELS/Auth.php';
    require './DAO/LivroDaoMysql.php';
    
    $auth = new Auth($pdo, $base);
    $info = $auth->checkToken();
    $level = $auth->checkLevel();
    if($level != 'bibliotecario'){
        header('location:./index.php');
    }
    $livrosDao = new LivroDaoMysql($pdo);
    $livros = $livrosDao->selectAll();
?>


<!DOCTYPE html>
<html lang="en">
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
                <?php require_once('./templates/cadastroLivros/menu-livros.php') ?>
            </nav>

            <main class="main-cads">
                <div class="categoria-e-pesquisa">
                    <h1>Listagem de Livros</h1>
                    <form name="frmBusca" action="">
                        <label for="pesquisa"></label>
                        <input type="text" id="pesquisa" placeholder="Pesquisar">
                        <input type="submit" value="Pesquisar">
                    </form>
                </div>
                <div class="linha"></div>
                <div class="linha"></div>
                    <div class="table-lista">
                    <?php

                    echo $_SESSION['flash'] ?? '';
                    $_SESSION['flash'] = '';

                    ?>
                        <table id="table-detalhes">
                            <tr>
                                <th>Editar</th>
                                <th>Codigo</th>
                                <th>Nome</th>
                                <th>Número de Páginas</th>
                                <th>Autor</th>
                                <th>Genero</th>
                                <th>Deletar</th>
                            </tr>
                            <?php
                            if($livros)
                            foreach($livros as $livro){
                            ?>
                                <tr>
                                    <td><a href="./cad-livros-update.php?id=<?=$livro->codigo?>"><img src="./assets/icones/editar.png" alt="editar.png"></a></td>
                                    <td><?=$livro->codigo?></td>
                                    <td><?=$livro->nome?></td>
                                    <td><?=$livro->nmrPaginas?></td>
                                    <td><?=$livro->autor['NOME']?></td>
                                    <td><?=$livro->genero['NOME']?></td>
                                    <td><a href="./cad-livros-delete.php?id=<?=$livro->codigo?>"><img src="./assets/icones/deletar.png" alt="deletar.png"></a></td>
                                </tr>
                                
                            <?php }
                                else
                                    echo "<div class='isa_warning'>
                                            <i class='fa fa-warning'></i>
                                            Não há livros cadastrados
                                            </div>";
                            ?>
        
                        </table>
                    </div>
                <div class="paginas">
                    <a href="" style="color: wheat;">1</a>
                </div>
            </main>

            
</body>
