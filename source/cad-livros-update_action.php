<?php

require './Connection.php';
require './DAO/LivroDaoMysql.php';
require './MODELS/Auth.php';


echo $codigoLivro = filter_input(INPUT_POST, "codigoLivro", FILTER_SANITIZE_NUMBER_INT);
echo $nome = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_SPECIAL_CHARS);
$nroPag = filter_input(INPUT_POST, "numeroPag", FILTER_SANITIZE_SPECIAL_CHARS);
$dataPublic = filter_input(INPUT_POST, "dataPublic");
$codigoAutorNovo = filter_input(INPUT_POST, "autor", FILTER_SANITIZE_NUMBER_INT);
$codigoAutorAntigo = filter_input(INPUT_POST, "autorAntigo", FILTER_SANITIZE_NUMBER_INT);
$genero = filter_input(INPUT_POST, "genero", FILTER_SANITIZE_NUMBER_INT);
$editora = filter_input(INPUT_POST, "editora", FILTER_SANITIZE_NUMBER_INT);
$idioma = filter_input(INPUT_POST, "idioma", FILTER_SANITIZE_NUMBER_INT);
$quantidade = filter_input(INPUT_POST, "quantidade", FILTER_SANITIZE_NUMBER_INT);
$fotoAntiga = filter_input(INPUT_POST, "fotoAntiga", FILTER_SANITIZE_STRING);


$auth = new Auth($pdo, $base);
$livroDao = new LivroDaoMysql($pdo);


if($_FILES['foto']['error'] != 4){
    $auth->deleteImagem('./storage/livrosImages/', $fotoAntiga);
    $photoResult = $auth->updateImage('./storage/livrosImages/');
}else{
    $photoResult = $fotoAntiga;
}


try {
    $livro = new Livro();

    $livro->codigo = $codigoLivro;
    $livro->nome = $nome;
    $livro->nmrPaginas = $nroPag;
    $livro->dataPublicacao = $dataPublic;
    $livro->autor = $codigoAutorNovo;
    $livro->genero = $genero;
    $livro->editora = $editora;
    $livro->idioma = $idioma;
    $livro->quantidade = $quantidade;
    $livro->imagem = $photoResult;

    $result = $livroDao->update($livro, $codigoAutorAntigo);

    if ($result) {
        $_SESSION['flash'] = "
            <div class='isa_success'>
                <i class='fa fa-check'></i>
                Atualização Realizada com Sucesso!
            </div>
            ";
        header('Location:' . $base . '/view-livros.php');
    } else {

        $_SESSION['flash'] = "
        <div class='isa_error'>
        <i class='fa fa-error'></i>
            Erro na atualização
        </div>
    ";

        header('Location:' . $base . '/cad-livros.php?id='.$livro->codigo);
    }
} catch (PDOException $erro) {
    echo 'Erro na atualização de livros: ' . $erro->getCode();
    echo 'Mensagem do erro: ' . $erro->getMessage();
}

