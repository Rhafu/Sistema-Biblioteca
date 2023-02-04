<?php
require_once('./MODELS/Genero.php');
class GeneroDaoMysql{

    private $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    private function generateGenero($array){
        $genero = new Genero();

        $genero->codigo = $array['codigoGenero'];
        $genero->nome = $array['nomeGenero'];

        return $genero;
    }

    public function selectAll(){

        try{
            // $sql = $this->pdo->prepare('SELECT * FROM LIVROS');
            $sql = $this->pdo->prepare('SELECT * FROM genero');
            $sql->execute(); 

            if($sql->rowCount() > 0){
                $arrayGeneros = $sql->fetchAll(PDO::FETCH_ASSOC);
                $generos = [];
                foreach($arrayGeneros as $generoDesc){
                    $genero = $this->generateGenero($generoDesc);
                    array_push($generos, $genero);
                }

                return $generos;
            }

            return false;
        }catch(PDOException $erro){
            echo 'Erro no Selecionar todos os Generos: '.$erro->getCode();
            echo 'Mensagem do erro: '.$erro->getMessage();
        }

    }

    public function insert (Genero $genero){
        //colocar o try
        //colocaro if (empty autor);
        $sql = $this->pdo->prepare("INSERT INTO genero (codigoGenero,
        nomeGenero)
        VALUES (:codigoGenero,
        :nomeGenero)");
                
        $sql->bindValue(':codigoGenero', $genero->codigo);
        $sql->bindValue(':nomeGenero', $genero->nome);

        $sql->execute();
        

        if($sql->rowCount() > 0){
            return true;
        }else{
            return false;
        }


    }
}