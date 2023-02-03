<?php
require './Connection.php';
require './MODELS/Auth.php';
require_once ('./DAO/LivroDaoMysql.php');
require_once ('./DAO/AutorDaoMysql.php');
require_once ('./DAO/GeneroDaoMysql.php');
require_once ('./DAO/EditoraDaoMysql.php');
require_once ('./DAO/IdiomaDaoMysql.php');
require_once ('./DAO/LivroDaoMysql.php');

$auth = new Auth($pdo, $base);
$info = $auth->checkToken();
$level = $auth->checkLevel();
if($level != 'bibliotecario'){
    header('location:./index.php');
}

$autorDao = new AutorDaoMysql($pdo);
$autores = $autorDao->selectAll();

$generoDao = new GeneroDaoMysql($pdo);
$generos = $generoDao->selectAll();

$editoraDao = new EditoraDaoMysql($pdo);
$editoras = $editoraDao->selectAll();

$idiomaDao = new IdiomaDaoMysql($pdo);
$idiomas = $idiomaDao->selectAll();

$codigo = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$livrosDao = new LivroDaoMysql($pdo);
$livro = $livrosDao->findByCodigo($codigo);
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
                <h1>Cadastro de Livros</h1>
            </div>
            <div class="linha"></div>
            <div class="linha"></div>
            <?php

            echo $_SESSION['flash'] ?? '';
            $_SESSION['flash'] = '';

            ?>
            <div class="cadastro-geral">
                <div class="panel-body">
                    <form name="cadastro" role="form" action="./cad-livros-update_action.php" method="POST" enctype="multipart/form-data">

                    <div class="form-group">
                            <div class="form-label">
                                <label for="autor">Autor</label>
                            </div>
                            <div class="form-input">
                                <select name="autor" id="autor">
                                    <?php
                                        foreach($autores as $autor){
                                    ?>
                                        <option value="<?=$autor->codigo?>"
                                            <?php
                                                if($autor->codigo == $livro->autor['CODIGO']){
                                                    echo "selected='selected'";
                                                }
                                            ?>
                                        >
                                            <?=$autor->nome?>
                                        </option>
                                    <?php
                                        }
                                    ?>
                                </select>
                                <input type="hidden" value="<?=$livro->autor['CODIGO']?>" name="autorAntigo">

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-label">
                                <label for="genero">Gênero</label>
                            </div>
                            <div class="form-input">
                                <select name="genero" id="genero">
                                    <?php
                                        foreach($generos as $genero){
                                    ?>
                                        <option value="<?=$genero->codigo?>"
                                            <?php
                                            if($genero->codigo == $livro->genero['CODIGO']){
                                                echo "selected='selected'";
                                            }
                                            ?>
                                        >
                                            <?=$genero->nome?>
                                        </option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-label">
                                <label for="editora">Editora</label>
                            </div>
                            <div class="form-input">
                                <select name="editora" id="editora">
                                    <?php
                                        foreach($editoras as $editora){
                                    ?>
                                        <option value="<?=$editora->codigo?>"
                                            <?php
                                            if($editora->codigo == $livro->editora['CODIGO']){
                                                echo "selected='selected'";
                                            }
                                            ?>
                                        >
                                            <?=$editora->nome?>
                                        </option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-label">
                                <label for="idioma">Idioma</label>
                            </div>
                            <div class="form-input">
                                <select name="idioma" id="idioma">
                                    <?php
                                        foreach($idiomas as $idioma){
                                    ?>
                                        <option value="<?=$idioma->codigo?>"
                                            <?php
                                            if($idioma->codigo == $livro->idioma['CODIGO']){
                                                echo "selected='selected'";
                                            }
                                            ?>
                                        >
                                            <?=$idioma->nome?>
                                        </option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-label"><label for="foto">Foto do Livro (*.jpg, *.png, *jpeg)</label>
                            </div>
                            <div class="form-input"><input type="file" name="foto" id="foto"></div>
                            <input type="hidden" value="<?= $livro->imagem ?>" name="fotoAntiga">
                        </div>

                        <div class="form-group">
                            <div class="form-label"><label for="nome">Nome</label>
                            </div>
                            <div class="form-input"><input type="text" id="nome" name="nome" placeholder="Nome do Livro" required value="<?=$livro->nome?>"></div>
                            <input type="hidden" id="codigoLivro" name="codigoLivro" placeholder="Codigo do Livro" required autofocus value="<?=$livro->codigo?>">
                        </div>


                        <div class="form-group">
                            <div class="form-label"><label for="numeroPag">Numero de Paginas</label>
                            </div>
                            <div class="form-input"><input type="text" id="numeroPag" name="numeroPag" placeholder="Numero de Páginas" required value="<?=$livro->nmrPaginas?>"></div>
                        </div>

                        <div class="form-group">
                            <div class="form-label"><label for="quantidade">Quantidade</label>
                            </div>
                            <div class="form-input"><input type="text" id="quantidade" name="quantidade" placeholder="Quantidade de Cópias" required value="<?=$livro->quantidade?>"></div>
                        </div>

                        <div class="form-group">
                            <div class="form-label"><label for="dataPublic">Data de Publicação<br></label>
                            </div>
                            <div class="form-input"><input type="date" name="dataPublic" id="dataPublic" required value="<?=$livro->dataPublicacao?>"></div>
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