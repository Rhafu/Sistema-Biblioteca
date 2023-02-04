<?php
require './Connection.php';
require './DAO/LivroDaoMysql.php';
require './MODELS/Auth.php';


$codigo = filter_input(INPUT_POST, "codigo", FILTER_SANITIZE_SPECIAL_CHARS);
$nome = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_SPECIAL_CHARS);
$nroPag = filter_input(INPUT_POST, "numeroPag", FILTER_SANITIZE_SPECIAL_CHARS);
$dataPublic = filter_input(INPUT_POST, "dataPublic");
$autor = filter_input(INPUT_POST, "autor", FILTER_SANITIZE_NUMBER_INT);
$genero = filter_input(INPUT_POST, "genero", FILTER_SANITIZE_NUMBER_INT);
$editora = filter_input(INPUT_POST, "editora", FILTER_SANITIZE_NUMBER_INT);
$idioma = filter_input(INPUT_POST, "idioma", FILTER_SANITIZE_NUMBER_INT);
$quantidade = filter_input(INPUT_POST, "quantidade", FILTER_SANITIZE_NUMBER_INT);


$auth = new Auth($pdo, $base);
$livroDao = new LivroDaoMysql($pdo);

$photoResult = $auth->updateImage('./storage/livrosImages/');

try{
    $livro = new Livro();

    $livro->codigo = $codigo;
    $livro->nome = $nome;
    $livro->nmrPaginas = $nroPag;
    $livro->dataPublicacao = $dataPublic;
    $livro->autor = $autor;
    $livro->genero = $genero;
    $livro->editora = $editora;
    $livro->idioma = $idioma;
    $livro->quantidade = $quantidade;
    $livro->imagem = $photoResult;

    $result = $livroDao->insert($livro);

    if($result){
        $_SESSION['flash'] = 
        "
        <div class='isa_success'>
            <i class='fa fa-check'></i>
            Livro Cadastrado com Sucesso!
        </div>
        ";
        header('Location:'.$base.'/view-livros.php');
    }else{
        $_SESSION['flash'] = 
        "
        <div class='isa_warning'>
                        <i class='fa fa-warning'></i>
                            Não foi possível cadastrar livro.
        </div>
        ";
        header('Location:'.$base.'/cad-livros.php');
    }
}catch(PDOException $erro){
    echo 'Erro no cadastro de livros: '.$erro->getCode();
    echo 'Mensagem do erro: '.$erro->getMessage();
}

