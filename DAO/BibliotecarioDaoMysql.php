<?php
require_once('./MODELS/Bibliotecario.php');

class BibliotecarioDaoMysql{
    private $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    private function generateBibliotecario($array): Bibliotecario
    {
        $bibliotecario = new Bibliotecario();

        $bibliotecario->nome = $array['nomeBibliotecario'];
        $bibliotecario->email = $array['emailBibliotecario'];
        $bibliotecario->telefone = $array['telefoneBibliotecario'];
        $bibliotecario->cpf = $array['cpfBibliotecario'];
        $bibliotecario->dataNasc = $array['dataNascBibliotecario'];
        $bibliotecario->foto = $array['fotoBibliotecario'];
        $bibliotecario->senha = $array['senhaBibliotecario'];
        $bibliotecario->token = $array['tokenBibliotecario'];

        return $bibliotecario;
    }

    public function findByToken($token){
        if(!empty($token)){
            try{
                $sql = $this->pdo->prepare("SELECT * FROM bibliotecarios WHERE tokenBibliotecario = :token");
                $sql->bindValue(":token", $token);
                $sql->execute();

            }catch(PDOException $erro){
                echo 'Erro ao encontrar Bibliotecário por Token: '.$erro->getCode();
                echo 'Mensagem do erro: '.$erro->getMessage();
            }
            
            if($sql->rowCount() > 0){
                $arrayBibliotecario = $sql->fetch(PDO::FETCH_ASSOC);

                $bibliotecario = $this->generateBibliotecario($arrayBibliotecario);

                return $bibliotecario;
            }
        }

        return false;
    }

    public function findByEmail($email){
        if(!empty($email)){

            try{
                $sql = $this->pdo->prepare("SELECT * FROM bibliotecarios WHERE emailBibliotecario = :email");
                $sql->bindValue(":email", $email);
                $sql->execute();

            }catch(PDOException $erro){
                echo 'Erro no Encontrar Bibliotecário por Email: '.$erro->getCode();
                echo 'Mensagem do erro: '.$erro->getMessage();
            }

            if($sql->rowCount() > 0){
                $arrayBibliotecario = $sql->fetch(PDO::FETCH_ASSOC);

                $bibliotecario = $this->generateBibliotecario($arrayBibliotecario);

                // print_r($bibliotecario);

                return $bibliotecario;
            }
            
        }

        return false;
    }

    public function findByCpf($cpf){
        if(!empty($cpf)){

            try{
                $sql = $this->pdo->prepare("SELECT * FROM bibliotecarios WHERE cpfBibliotecario = :cpf");
                $sql->bindValue(":cpf", $cpf);
                $sql->execute();

            }catch(PDOException $erro){
                echo 'Erro no Encontrar Bibliotecário por CPF: '.$erro->getCode();
                echo 'Mensagem do erro: '.$erro->getMessage();
            }

            if($sql->rowCount() > 0){
                $arrayBibliotecario = $sql->fetch(PDO::FETCH_ASSOC);

                $bibliotecario = $this->generateBibliotecario($arrayBibliotecario);

                // print_r($bibliotecario);

                return $bibliotecario;
            }

        }

        return false;
    }

    public function update(Bibliotecario $bibliotecario){
        if(!empty($bibliotecario)){
            try{
                $sql = $this->pdo->prepare("UPDATE bibliotecarios SET 
                nomeBibliotecario = :nome,
                emailBibliotecario = :email,
                senhaBibliotecario = :senha,
                telefoneBibliotecario = :telefone,
                fotoBibliotecario = :foto,
                dataNascBibliotecario = :dataNasc,
                tokenBibliotecario = :token WHERE cpfBibliotecario = :cpf");


                $sql->bindValue(':nome', $bibliotecario->nome);
                $sql->bindValue(':email', $bibliotecario->email);
                $sql->bindValue(':senha', $bibliotecario->senha);
                $sql->bindValue(':telefone', $bibliotecario->telefone);
                $sql->bindValue(':foto', $bibliotecario->foto);
                $sql->bindValue(':dataNasc', $bibliotecario->dataNasc);
                $sql->bindValue(":token", $bibliotecario->token);
                $sql->bindValue(':cpf', $bibliotecario->cpf);
                $sql->execute();


            }catch(PDOException $erro){
                echo 'Erro na atualização de bibliotecário: '.$erro->getCode();
                echo 'Mensagem do erro: '.$erro->getMessage();
            }
            if($sql->rowCount() > 0){
                return true;
            }
        }

        return false;
    }

    public function insert(Bibliotecario $Bibliotecario){
        if(!empty($Bibliotecario)){
            try{
                $sql = $this->pdo->prepare("INSERT INTO bibliotecarios (nomeBibliotecario,
                emailBibliotecario,
                senhaBibliotecario,
                telefoneBibliotecario,
                fotoBibliotecario,
                dataNascBibliotecario,
                tokenBibliotecario,
                CpfBibliotecario)
                VALUES (:nome,
                :email,
                :senha,
                :telefone,
                :foto,
                :dataNasc,
                :token,
                :cpf)");

                $sql->bindValue(':nome', $Bibliotecario->nome);
                $sql->bindValue(':email', $Bibliotecario->email);
                $sql->bindValue(':senha', $Bibliotecario->senha);
                $sql->bindValue(':telefone', $Bibliotecario->telefone);
                $sql->bindValue(':foto', $Bibliotecario->foto);
                $sql->bindValue(':dataNasc', $Bibliotecario->dataNasc);
                $sql->bindValue(':token', $Bibliotecario->token);
                $sql->bindValue(':cpf', $Bibliotecario->cpf);
                $sql->execute();

            }catch(PDOException $erro){
                echo 'Erro na inserção de bibliotecário: '.$erro->getCode();
                echo 'Mensagem do erro: '.$erro->getMessage();
            }

            if($sql->rowCount() > 0){
                return true;//veerificar o rowcount
            }
        }

        return false;
    }

    public function selectAll(){
        try{
            $sql = $this->pdo->prepare("SELECT * FROM bibliotecarios");
            $sql->execute();
        }catch(PDOException $erro){
            echo 'Erro no Selecionar todos os bibliotecarios: '.$erro->getCode();
            echo 'Mensagem do erro: '.$erro->getMessage();
        }

        if($sql->rowCount() > 0){
            $arrayBibliotecarios = $sql->fetchAll(PDO::FETCH_ASSOC);
            $bibliotecarios = [];
            foreach ($arrayBibliotecarios as $bibliotecarioDesc){
                $bibliotecario = $this->generateBibliotecario($bibliotecarioDesc);
                array_push($bibliotecarios, $bibliotecario);
            }

            return $bibliotecarios;
        }
        return false;
    }

}