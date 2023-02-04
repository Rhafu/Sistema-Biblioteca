<?php
require './Connection.php';
require './MODELS/Auth.php';
require_once './DAO/LivroDaoMysql.php';
require_once './DAO/BibliotecarioDaoMysql.php';
require_once './DAO/AlunoDaoMysql.php';

$auth = new Auth($pdo, $base);
$info = $auth->checkToken();

$codigo = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$livroDao = new LivroDaoMysql($pdo);
$alunoDao = new AlunoDaoMysql($pdo);
$bibliotecarioDao = new BibliotecarioDaoMysql($pdo);


$livro = $livroDao->findByCodigo($codigo);
if ($alunoDao->findByToken($_SESSION['token'])) {
    $aluno = $alunoDao->findByToken($_SESSION['token']);
}else{
    $bibliotecario = $bibliotecarioDao->findByToken($_SESSION['token']);
}

// print_r($livro)
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
    <link rel="stylesheet" href="./assets/css/view-tables.css">
    <link rel="stylesheet" href="./assets/css/cadastros.css">
    <link rel="stylesheet" href="./assets/css/descricao.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="./js/data-minima.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Arsenal:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <title>Biblioteca</title>
</head>
<body>
    <header class="container cabecalho">
        <div class="logo">
            <img src="./assets/R.png" alt="">
        </div>

        <?php
                    if($auth->checkLevel() == 'aluno'){
                        require_once('./templates/menu/logged-cliente.php');
                    }else if($auth->checkLevel() == 'bibliotecario'){
                        require_once('./templates/menu/logged-funcionario.php');
                    }

                ?>

    </header>
    <div class="voltar">
        <span style="cursor:pointer" onclick="window.history.go(-1)">VOLTAR</span>
    </div>
    <?php
    echo $_SESSION['flash'] ?? '';
    $_SESSION['flash'] = '';
    ?>
    <main>
            <div class="livro-descricao-geral">
            <div class="livro-sinopse-feedback">
                <div class="livro-sinopse">
                    <img src="./storage/livrosImages/<?=$livro->imagem?>" alt="livro-img">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptatum, ratione. Voluptatem cupiditate provident nesciunt quasi saepe eius. Illo doloribus commodi repellendus repellat incidunt consequatur, deserunt harum, magni et ab dolor?
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Vero porro ab esse ad quod facilis ut eligendi, expedita rem nam dolores, illo vitae nisi adipisci perspiciatis velit excepturi! Facilis, eum! Lorem ipsum dolor sit, amet consectetur adipisicing elit. Enim quae quibusdam, quia odit consectetur cumque quas deserunt explicabo nesciunt culpa nostrum eos? Quisquam amet voluptates voluptatum qui at, unde molestiae? Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa similique, ducimus non quisquam id voluptatum sapiente distinctio commodi explicabo eaque autem in neque assumenda eligendi optio voluptatem impedit officia? In.
                    </p>
                </div>
                <div class="livro-feedback">
                    <table id="table-detalhes">
                        <tr>
                          <th>Número de Páginas</th>
                          <th>Autor</th>
                          <th>Genero</th>
                        </tr>
                        <tr>
                          <td><?=$livro->nmrPaginas?></td>
                          <td><?=$livro->autor['NOME']?></td>
                          <td><?=$livro->genero['NOME']?></td>
                        </tr>
    
                      </table>
                </div>
            </div>
            <div class="livro-caracteristicas">

            </div>
        </div>
        <div class="livro-locacao">
            <h2>Requisitar Locação</h2>
                           <!-- VERIFICANDO SE É UM ALUNO OU UM  BIBLIOTECARIO -->
            <form action="<?=(isset($aluno)) ? ('./cad-locacoes_action.php?') : ('./cad-locacoes-adm_action.php?')?>" method="POST">
                <input type="hidden" value="<?=$livro->codigo?>" name="codigo">
                <input type="hidden" value="<?=$aluno->ra ?? '';?>" name="ra">
                <label for="dataDe">Data de Locação</label>
                <input type="date" name="dataDe" id="dataDe" required>
                <?php
                if(isset($bibliotecario)){
                    echo "<label for='dataDe'>RA ALUNO</label>
                          <input type='text' name='raAluno' id='raAluno' required>";
                }
                ?>
                <h3>Disponibilidade do Pedido</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Similique, ducimus animi ipsam doloribus quo voluptatem quaerat et vitae quisquam minus est, architecto hic officia fugiat facilis in consequatur? Illum, consequatur?</p>
                <input type="submit" value="Efetuar Locação">
            </form>
        </div>
    </main>

    <footer>
            <div class="logo-site">
                <h1>Biblioteca</h1>
            </div>
            <div class="contato">
                <h2>Contato</h2>
                email:rararaar@gmail.com
            </div>
    </footer>
    </div>
</body>
</html>