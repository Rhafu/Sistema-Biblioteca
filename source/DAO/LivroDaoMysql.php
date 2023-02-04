<?php
require_once('./MODELS/Livro.php');

class LivroDaoMysql{


    private $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    private function generateLivro($array){
        $livro = new Livro();

        $livro->codigo = $array['codigoLivro'];
        $livro->nome = $array['nomeLivro'];
        $livro->nmrPaginas = $array['nroPagLivro'];
        $livro->dataPublicacao = $array['dataPublicacaoLivro'];
        $livro->autor = array("CODIGO"=>$array['codigoAutor'], "NOME"=>$array['nomeAutor']);
        $livro->editora = array("CODIGO"=>$array['codigoEditora'], "NOME"=>$array['nomeEditora']);
        $livro->idioma = array("CODIGO"=>$array['codigoIdioma'], "NOME"=>$array['nomeIdioma']);
        $livro->genero = array("CODIGO"=>$array['codigoGenero'], "NOME"=>$array['nomeGenero']);
        $livro->quantidade = $array['quantidadeLivro'];
        $livro->imagem = $array['imagemLivro'];



        return $livro;
    }

    public function selectAll(){

        try{
            // $sql = $this->pdo->prepare('SELECT * FROM LIVROS');
            $sql = $this->pdo->prepare('SELECT * FROM LIVROS, AUTOR, OBRAAUTOR, EDITORA, GENERO, IDIOMA WHERE Livros.codigoLivro = ObraAutor.codigoLivro 
                                                                                                        AND Autor.codigoAutor = ObraAutor.codigoAutor
                                                                                                        AND Livros.editoraLivro = Editora.codigoEditora
                                                                                                        AND Livros.generoLivro = Genero.codigoGenero
                                                                                                        AND Livros.idiomaLivro = Idioma.codigoIdioma'
            );
            $sql->execute(); 

            if($sql->rowCount() > 0){
                $arrayLivros = $sql->fetchAll(PDO::FETCH_ASSOC);
                $livros = [];
                foreach($arrayLivros as $livroDesc){
                    $livro = $this->generateLivro($livroDesc);
                    array_push($livros, $livro);
                }

                return $livros;
            }

            return false;
        }catch(PDOException $erro){
            echo 'Erro no Selecionar todos os livros: '.$erro->getCode();
            echo 'Mensagem do erro: '.$erro->getMessage();
        }

    }

    public function findByCodigo($codigo){
        try{
            $sql = $this->pdo->prepare('SELECT * FROM LIVROS, AUTOR, OBRAAUTOR, EDITORA, GENERO, IDIOMA WHERE Livros.codigoLivro = :codigo 
                                                                                                        AND Autor.codigoAutor = ObraAutor.codigoAutor
                                                                                                        AND Livros.editoraLivro = Editora.codigoEditora
                                                                                                        AND Livros.generoLivro = Genero.codigoGenero
                                                                                                        AND Livros.idiomaLivro = Idioma.codigoIdioma
                                                                                                        AND ObraAutor.codigoLivro = :codigo');
            $sql->bindValue(':codigo', $codigo);
            $sql->execute();


            if($sql->rowCount() > 0){
                $arrayLivro = $sql->fetch(PDO::FETCH_ASSOC);

                // print_r($arrayLivro);

                $livro = $this->generateLivro($arrayLivro);
                
                return $livro;
            }

            return false;
        }catch(PDOException $erro){
            echo 'Erro no Selecionar livro por codigo: '.$erro->getCode();
            echo 'Mensagem do erro: '.$erro->getMessage();
        }
    }

    public function insert(Livro $livro){
        if(!empty($livro)){
            try{
                $sql = $this->pdo->prepare("INSERT INTO LIVROS (
                    codigoLivro,
                    nomeLivro,
                    nroPagLivro,
                    dataPublicacaoLivro,
                    editoraLivro,
                    generoLivro,
                    idiomaLivro,
                    quantidadeLivro,
                    imagemLivro)
                    VALUES
                    (:codigo,
                     :nome,
                     :nroPag,
                     :dataPublic,
                     :editora,
                     :genero,
                     :idioma,
                     :quantidade,
                     :imagem)"
                     );
                
                $sql->bindValue(':codigo', $livro->codigo);
                $sql->bindValue(':nome', $livro->nome);
                $sql->bindValue(':nroPag', $livro->nmrPaginas);
                $sql->bindValue(':dataPublic', $livro->dataPublicacao);
                $sql->bindValue(':editora', $livro->editora);
                $sql->bindValue(':genero', $livro->genero);
                $sql->bindValue(':idioma', $livro->idioma);
                $sql->bindValue(':quantidade', $livro->quantidade);
                $sql->bindValue(':imagem', $livro->imagem);
                $sql->execute();    
                
                
                if($sql->rowCount() > 0){
                    $sql = $this->pdo->prepare("INSERT INTO OBRAAUTOR 
                       (codigoLivro,
                        codigoAutor)
                        VALUES
                        (:livro,
                         :autor)");

                    $sql->bindValue(':livro', $livro->codigo);
                    $sql->bindValue(':autor', $livro->autor);
                    $sql->execute();                    

                    if($sql->rowCount() > 0){
                        return true;
                    }else{
                        $_SESSION['flash'] = 
                        "
                        <div class='isa_warning'>
                            <i class='fa fa-warning'></i>
                            Erro ao Cadastrar Autor do livro
                        </div>
                        ";
                        return false;
                    }
                    
                }else{
                    $_SESSION['flash'] = 
                    "
                    <div class='isa_warning'>
                        <i class='fa fa-warning'></i>
                        Erro ao Cadastrar livro
                    </div>
                    ";
                    return false;
                }

            }catch(PDOException $erro){
                echo 'Erro na inserção Livro: '.$erro->getCode();
                echo 'Mensagem do erro: '.$erro->getMessage();
            }
        }
    }

    public function update(Livro $livro, $codigoAutorAntigo){
        if(!empty($livro)){
            try{
                $sql = $this->pdo->prepare("UPDATE LIVROS SET
                    nomeLivro = :nome,
                    nroPagLivro = :nroPag,
                    dataPublicacaoLivro = :dataPublic,
                    editoraLivro = :editora,
                    generoLivro = :genero,
                    idiomaLivro = :idioma,
                    quantidadeLivro = :quantidade,
                    imagemLivro = :imagem
                    WHERE codigoLivro = :codigoLivro

                     "
                );


                $sql->bindValue(':nome', $livro->nome);
                $sql->bindValue(':nroPag', $livro->nmrPaginas);
                $sql->bindValue(':dataPublic', $livro->dataPublicacao);
                $sql->bindValue(':editora', $livro->editora);
                $sql->bindValue(':genero', $livro->genero);
                $sql->bindValue(':idioma', $livro->idioma);
                $sql->bindValue(':quantidade', $livro->quantidade);
                $sql->bindValue(':imagem', $livro->imagem);
                $sql->bindValue(':codigoLivro', $livro->codigo);
                $sql->execute();


            }catch(PDOException $erro){
                echo 'Erro na atualização Livro: '.$erro->getCode();
                echo 'Mensagem do erro: '.$erro->getMessage();
                return false;
            }

            $sql = $this->pdo->prepare("UPDATE OBRAAUTOR SET 
                        codigoAutor = :autorNovo
                        WHERE codigoAutor = :codigoAutorAntigo AND codigoLivro = :codigoLivro;
                         ");

            try {
                $sql->bindValue(':autorNovo', $livro->autor);
                $sql->bindValue(':codigoAutorAntigo', $codigoAutorAntigo);
                $sql->bindValue(':codigoLivro', $livro->codigo);
                $sql->execute();

            }catch(PDOException $erro){
                echo 'Erro na atualização Livro: '.$erro->getCode();
                echo 'Mensagem do erro: '.$erro->getMessage();

                return false;
            }

            return true;

        }
    }

}