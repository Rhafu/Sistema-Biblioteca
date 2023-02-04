<?php
require_once('./MODELS/Autor.php');
class AutorDaoMysql{

    private $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    private function generateAutor($array){
        $autor = new Autor();

        $autor->codigo = $array['codigoAutor'];
        $autor->nome = $array['nomeAutor'];
        $autor->dataNascAutor = $array['dataNascAutor'];

        return $autor;
    }

    public function findCode($codigo){
        try{
            // $sql = $this->pdo->prepare('SELECT * FROM LIVROS');
            $sql = $this->pdo->prepare('SELECT * FROM autor WHERE codigoAutor = :codigo');
            $sql->bindValue(':codigo', $codigo);
            $sql->execute();

        }catch (PDOException $erro){
            echo 'Erro no Selecionar autor por código: '.$erro->getCode();
            echo 'Mensagem do erro: '.$erro->getMessage();
        }

        if($sql->rowCount() > 0){
            $autorDesc = $sql->fetch(PDO::FETCH_ASSOC);
            $autor = $this->generateAutor($autorDesc);

            return $autor;
        }

        return false;
    }

    public function selectAll(){

        try{
            // $sql = $this->pdo->prepare('SELECT * FROM LIVROS');
            $sql = $this->pdo->prepare('SELECT * FROM autor');
            $sql->execute();
        }catch(PDOException $erro){
            echo 'Erro no Selecionar todos os autores: '.$erro->getCode();
            echo 'Mensagem do erro: '.$erro->getMessage();
        }

        if($sql->rowCount() > 0){
            $arrayAutores = $sql->fetchAll(PDO::FETCH_ASSOC);
            $autores = [];
            foreach($arrayAutores as $autorDesc){
                $autor = $this->generateAutor($autorDesc);
                array_push($autores, $autor);
            }

            return $autores;
        }

        return false;

    }

    public function insert (Autor $autor){
        if(!empty($autor)){
            try {
                $sql = $this->pdo->prepare("INSERT INTO autor (codigoAutor,
                                                                    nomeAutor,
                                                                    dataNascAutor)
                                                                    VALUES (:codigoAutor,
                                                                    :nomeAutor,
                                                                    :dataNasc)");

                $sql->bindValue(':codigoAutor', $autor->codigo);
                $sql->bindValue(':nomeAutor', $autor->nome);
                $sql->bindValue('dataNasc', $autor->dataNascAutor);

                $sql->execute();
            }catch (PDOException $erro){
                echo 'Erro na inserção de autor: '.$erro->getCode();
                echo 'Mensagem do erro: '.$erro->getMessage();
            }

            if($sql->rowCount() > 0) {
                return true;//veerificar o rowcount
            }

        }

        return false;
    }


    public function update (Autor $autor){
        if(!empty($autor)){
            try {
                $sql = $this->pdo->prepare("UPDATE autor SET  nomeAutor = :nomeAutor,
                                                                    dataNascAutor = :dataNasc
                                                                    WHERE
                                                                    codigoAutor = :codigoAutor");

                $sql->bindValue(':nomeAutor', $autor->nome);
                $sql->bindValue('dataNasc', $autor->dataNascAutor);
                $sql->bindValue(':codigoAutor', $autor->codigo);

                $sql->execute();
            }catch (PDOException $erro){
                echo 'Erro na inserção de autor: '.$erro->getCode();
                echo 'Mensagem do erro: '.$erro->getMessage();
            }

            if($sql->rowCount() > 0) {
                return true;//veerificar o rowcount
            }

        }

        return false;
    }
}