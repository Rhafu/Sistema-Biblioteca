<?php
require_once('./MODELS/Editora.php');
class EditoraDaoMysql{

    private $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    private function generateEditora($array){
        $editora = new Editora();

        $editora->codigo = $array['codigoEditora'];
        $editora->nome = $array['nomeEditora'];

        return $editora;
    }

    public function selectAll(){

        try{
            // $sql = $this->pdo->prepare('SELECT * FROM LIVROS');
            $sql = $this->pdo->prepare('SELECT * FROM editora');
            $sql->execute();
        }catch(PDOException $erro){
            echo 'Erro no Selecionar todos os Editoras: '.$erro->getCode();
            echo 'Mensagem do erro: '.$erro->getMessage();
        }

        if($sql->rowCount() > 0){
            $arrayEditoras = $sql->fetchAll(PDO::FETCH_ASSOC);
            $editoras = [];
            foreach($arrayEditoras as $editoraDesc){
                $editora = $this->generateEditora($editoraDesc);
                array_push($editoras, $editora);
            }

            return $editoras;
        }

        return false;

    }

    public function insert (Editora $editora){
        //colocar o try
        //colocaro if (empty autor);
        if(!empty($editora)){
            try {

                $sql = $this->pdo->prepare("INSERT INTO editora (codigoEditora,
                                                    nomeEditora)
                                                    VALUES (:codigoEditora,
                                                    :nomeEditora)");

                $sql->bindValue(':codigoEditora', $editora->codigo);
                $sql->bindValue(':nomeEditora', $editora->nome);

                $sql->execute();

            }catch (PDOException $erro){
                echo 'Erro na inserção de Editora: '.$erro->getCode();
                echo 'Mensagem do erro: '.$erro->getMessage();
            }

            if($sql->rowCount() > 0) {
                return true;//veerificar o rowcount
            }
        }

        

    }
}