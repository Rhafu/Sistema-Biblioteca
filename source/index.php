<?php
require './Connection.php';
require './MODELS/Auth.php';
require './DAO/LivroDaoMysql.php';

$auth = new Auth($pdo, $base);
$info = $auth->checkToken();
$level = $auth->checkLevel();
$livroDao = new LivroDaoMysql($pdo);
$livros = $livroDao->selectAll();
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="./js/validacao-cad.js">
    </script>
    <link rel="stylesheet" href="./assets/css/mensagens.css">
    <link rel="stylesheet" href="./assets/css/geral.css">
    <link rel="stylesheet" href="./assets/css/index.css">
    <link rel="stylesheet" href="./assets/css/cadastros.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <title>Index</title>
</head>
<body>
        <header class="container cabecalho">
                <div class="logo">
                    <img src="./assets/R.png" alt="">
                </div>

                <?php
                    if($level == 'aluno'){
                        require_once('./templates/menu/logged-cliente.php');
                    }else if($level == 'bibliotecario'){
                        require_once('./templates/menu/logged-funcionario.php');
                    }

                ?>
        </header>
        <div class="menu-livros-geral container">
            <nav>
                <ul>
                    <li>Teologia</li>
                    <li>Família</li>
                    <li>Vida Cristã</li>
                    <li>Hermenêutica</li>
                </ul>
            </nav>

            <main>
                <div class="categoria-e-pesquisa">
                    <h1>Livros Populares</h1>
                    <form name="frmBusca" action="">
                        <label for="pesquisa"></label>
                        <input type="text" id="pesquisa" placeholder="Pesquisar">
                        <input type="submit" value="Pesquisar">
                    </form>
                </div>
                <div class="linha"></div>
                <div class="linha"></div>
                <div class="minhas-locacoes" onclick="location.href='./view-locacoes.php'">Minhas Locacoes</div>
                <div class="livros">

                    <?php
                        foreach($livros as $livro){
                    ?>
                        <div class="livro">
                            <img src="./storage/livrosImages/<?=$livro->imagem?>" alt="foto_livro">
                            <h2><?=$livro->nome?></h2>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quibusdam blanditiis autem veniam ea?</p>
                            <a href="./descricao.php?id=<?=$livro->codigo?>">Ver Mais</a>
                        </div>
                    <?php
                    }
                    ?>
                    <!-- <div class="livro">
                        <img src="./assets/molde.jpg" alt="">
                        <h2>Livro1</h2>
                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Cum perspiciatis, laudantium </p>
                        <a href="">Ver Mais</a>
                    </div>
                    <div class="livro">
                        <img src="./assets/molde.jpg" alt="">
                        <h2>Livro1</h2>
                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Cum perspiciatis, laudantium </p>
                        <a href="">Ver Mais</a>
                    </div>
                    <div class="livro">
                        <img src="./assets/molde.jpg" alt="">
                        <h2>Livro1</h2>
                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Cum perspiciatis, laudantium </p>
                        <a href="">Ver Mais</a>
                    </div> -->
                </div>
                <div class="paginas">
                    <a href="" style="color: wheat;">1</a>
                </div>
            </main>

            
        </div>
        <footer class="container">
            <div class="logo-site">
                <h1>Biblioteca</h1>
            </div>
            <div class="contato">
                <h2>Contato</h2>
                email:rararaar@gmail.com
            </div>
        </footer>
</body>
</html>

