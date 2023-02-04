<?php
require_once('./MODELS/Idioma.php');
class IdiomaDaoMysql{

    private $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    private function generateIdioma($array){
        $idioma = new Idioma();

        $idioma->codigo = $array['codigoIdioma'];
        $idioma->nome = $array['nomeIdioma'];

        return $idioma;
    }

    public function selectAll(){

        try{
            // $sql = $this->pdo->prepare('SELECT * FROM LIVROS');
            $sql = $this->pdo->prepare('SELECT * FROM idioma');
            $sql->execute(); 

            if($sql->rowCount() > 0){
                $arrayIdiomas = $sql->fetchAll(PDO::FETCH_ASSOC);
                $idiomas = [];
                foreach($arrayIdiomas as $idiomaDesc){
                    $idioma = $this->generateIdioma($idiomaDesc);
                    array_push($idiomas, $idioma);
                }

                return $idiomas;
            }

            return false;
        }catch(PDOException $erro){
            echo 'Erro no Selecionar todos os Idiomas: '.$erro->getCode();
            echo 'Mensagem do erro: '.$erro->getMessage();
        }

    }

    public function insert (Idioma $idioma){
        //colocar o try
        //colocaro if (empty autor);
        $sql = $this->pdo->prepare("INSERT INTO idioma (codigoIdioma,
        nomeIdioma)
        VALUES (:codigoIdioma,
        :nomeIdioma)");
                
        $sql->bindValue(':codigoIdioma', $idioma->codigo);
        $sql->bindValue(':nomeIdioma', $idioma->nome);

        $sql->execute();
        
        return true;//veerificar o rowcount

    }
}