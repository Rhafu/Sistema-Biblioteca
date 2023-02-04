<?php
require_once('./MODELS/Aluno.php');

class AlunoDaoMysql extends Aluno
{

    private $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    private function generateAluno($array): Aluno
    {
        $aluno = new Aluno();

        $aluno->nome = $array['nomeAluno'];
        $aluno->email = $array['emailAluno'];
        $aluno->telefone = $array['telefoneAluno'];
        $aluno->ra = $array['raAluno'];
        $aluno->dataNasc = $array['dataNascAluno'];
        $aluno->foto = $array['fotoAluno'];
        $aluno->senha = $array['senhaAluno'];
        $aluno->token = $array['tokenAluno'];

        return $aluno;
    }

    public function selectAll(){
            try{
                $sql = $this->pdo->prepare("SELECT * FROM alunos");
                $sql->execute();
            }catch(PDOException $erro){
                echo 'Erro no Selecionar todos os alunos: '.$erro->getCode();
                echo 'Mensagem do erro: '.$erro->getMessage();
            }

            if($sql->rowCount() > 0){
                $arrayAlunos = $sql->fetchAll(PDO::FETCH_ASSOC);
                $alunos = [];
                foreach ($arrayAlunos as $alunoDesc){
                    $aluno = $this->generateAluno($alunoDesc);
                    array_push($alunos, $aluno);
                }

                return $alunos;
            }
            return false;
    }


    public function findByToken($token){
        if(!empty($token)){
            try{
                $sql = $this->pdo->prepare("SELECT * FROM alunos WHERE tokenAluno = :token");
                $sql->bindValue(":token", $token);
                $sql->execute();

            }catch(PDOException $erro){
                echo 'Erro no encontrar aluno por token: '.$erro->getCode();
                echo 'Mensagem do erro: '.$erro->getMessage();
            }
            
            if($sql->rowCount() > 0){
                $arrayAluno = $sql->fetch(PDO::FETCH_ASSOC);

                $aluno = $this->generateAluno($arrayAluno);

                return $aluno;
            }
        }

        return false;
    }

    public function findByEmail($email){
        if(!empty($email)){

            try{
                $sql = $this->pdo->prepare("SELECT * FROM alunos WHERE emailAluno = :email");
                $sql->bindValue(":email", $email);
                $sql->execute();
            }catch(PDOException $erro){
                echo 'Erro no Encontrar Aluno por Email: '.$erro->getCode();
                echo 'Mensagem do erro: '.$erro->getMessage();
            }

            if($sql->rowCount() > 0){
                $arrayAluno = $sql->fetch(PDO::FETCH_ASSOC);

                $aluno = $this->generateAluno($arrayAluno);

                return $aluno;
            }
            
        }

        return false;
    }

    public function findByRa($ra){
        if(!empty($ra)){

            try{
                $sql = $this->pdo->prepare("SELECT * FROM alunos WHERE raAluno = :ra");
                $sql->bindValue(":ra", $ra);
                $sql->execute();
            }catch(PDOException $erro){
                echo 'Erro no Encontrar Aluno por Email: '.$erro->getCode();
                echo 'Mensagem do erro: '.$erro->getMessage();
            }

            if($sql->rowCount() > 0){
                $arrayAluno = $sql->fetch(PDO::FETCH_ASSOC);

                $aluno = $this->generateAluno($arrayAluno);

                return $aluno;
            }

        }

        return false;
    }

    public function update(Aluno $aluno){
        if(!empty($aluno)){
            try{
                $sql = $this->pdo->prepare("UPDATE alunos SET 
                nomeAluno = :nome,
                emailAluno = :email,
                senhaAluno = :senha,
                telefoneAluno = :telefone,
                fotoAluno = :foto,
                dataNascAluno = :dataNasc,
                tokenAluno = :token WHERE raAluno = :ra");


                $sql->bindValue(':nome', $aluno->nome);
                $sql->bindValue(':email', $aluno->email);
                $sql->bindValue(':senha', $aluno->senha);
                $sql->bindValue(':telefone', $aluno->telefone);
                $sql->bindValue(':foto', $aluno->foto);
                $sql->bindValue(':dataNasc', $aluno->dataNasc);
                $sql->bindValue(":token", $aluno->token);
                $sql->bindValue(':ra', $aluno->ra);
                $sql->execute();

            }catch(PDOException $erro){
                echo 'Erro na atualização de Aluno: '.$erro->getCode();
                echo 'Mensagem do erro: '.$erro->getMessage();
            }

            if($sql->rowCount() > 0){
                return true;
            }
        }

        return false;
    }

    public function insert(Aluno $aluno){
        if(!empty($aluno)){
            try{
                $sql = $this->pdo->prepare("INSERT INTO alunos (nomeAluno,
                emailAluno,
                senhaAluno,
                telefoneAluno,
                fotoAluno,
                dataNascAluno,
                tokenAluno,
                raAluno)
                VALUES (:nome,
                :email,
                :senha,
                :telefone,
                :foto,
                :dataNasc,
                :token,
                :ra)");

                $sql->bindValue(':nome', $aluno->nome);
                $sql->bindValue(':email', $aluno->email);
                $sql->bindValue(':senha', $aluno->senha);
                $sql->bindValue(':telefone', $aluno->telefone);
                $sql->bindValue(':foto', $aluno->foto);
                $sql->bindValue(':dataNasc', $aluno->dataNasc);
                $sql->bindValue(':token', $aluno->token);
                $sql->bindValue(':ra', $aluno->ra);
                $sql->execute();

            }catch(PDOException $erro){
                echo 'Erro na inserção aluno: '.$erro->getCode();
                echo 'Mensagem do erro: '.$erro->getMessage();
            }

            if($sql->rowCount() > 0){
                return true;
            }
        }

        return false;
    }
}