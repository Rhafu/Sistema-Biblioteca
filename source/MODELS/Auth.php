<?php 

require_once('./DAO/AlunoDaoMysql.php');
require_once('./DAO/BibliotecarioDaoMysql.php');

class Auth{
    private $pdo;
    private $base;

    public function __construct(PDO $driver, $base){
        $this->pdo = $driver;
        $this->base = $base;
    }

    public function checkToken(){
        if(!empty($_SESSION['token'])){
            $token = $_SESSION['token'];

            $alunoDao = new AlunoDaoMysql($this->pdo);
            $aluno = $alunoDao->findByToken($token);

            $bibliotecarioDao = new BibliotecarioDaoMysql($this->pdo);
            $bibliotecario = $bibliotecarioDao->findByToken($token);

            if($aluno){
                return $aluno;
            }else if($bibliotecario){
                return $bibliotecario;
            }

        }

        header('Location: '.$this->base.'/login.php');
        exit;
    }

    public function checkLevel(){
        if(!empty($_SESSION['token'])){
            $token = $_SESSION['token'];

            $alunoDao = new AlunoDaoMysql($this->pdo);
            $aluno = $alunoDao->findByToken($token);

            $bibliotecarioDao = new BibliotecarioDaoMysql($this->pdo);
            $bibliotecario = $bibliotecarioDao->findByToken($token);
            
            
            if($aluno){
                $level = 'aluno';
                return $level;
            }else if($bibliotecario){
                $level = 'bibliotecario';
                return $level;
            }

        }

        header('Location: '.$this->base.'/login.php');
        exit;
    }

    public function checkLogin($email, $senha){
        $alunoDao = new AlunoDaoMysql($this->pdo);
        $bibliotecarioDao = new BibliotecarioDaoMysql($this->pdo);
        $aluno = $alunoDao->findByEmail($email);
        $bibliotecario = $bibliotecarioDao->findByEmail($email);

        if($aluno){

            $hash = $aluno->senha;

            if(password_verify($senha, $hash)){
                $token = md5(time().rand(0, 9999));
                $aluno->token = $token;
                $_SESSION['token'] = $token;
                $_SESSION['foto'] = $aluno->foto;

                $alunoDao->update($aluno);

                return true;

            }
        }else if($bibliotecario){

            $hash = $bibliotecario->senha;
            if(password_verify($senha, $hash)){
                $token = md5(time().rand(0, 9999));
                $bibliotecario->token = $token;
                $_SESSION['token'] = $token;
                $bibliotecarioDao->update($bibliotecario);
                
                return true;
            }

        }

        return false;
    }

    public function updateImage($dir){ //DEIXAR ISTO MAIS DINÂMICO, REMOVER $_FILES
        $token = md5(uniqid(rand(), true));

        $photo_name = str_replace(" ", "_", basename($_FILES['foto']['name'])); //Reservando somente o nome na variável por causa do basename

        $save_name = $dir.$token.$photo_name; //Nome final da imagem para não haver repetição

        $flag = 1;

        //Verificando se foi enviado pelo post-submit e verificando se o arquivo temporário existe
        if(isset($_POST["submit"])){
            if(getimagesize($_FILES['foto']['tmp_name'])){
                $flag = 1;
            }else{
                $flag = 0;
            }
        }

        //Verificando se imagem ultrapassa 2gb
        if($_FILES['foto']['size'] > 2000000){
            $flag = 0;
        }

        //Pegando extensao
        $extensao = strtolower(pathinfo($save_name, PATHINFO_EXTENSION));

        if(($extensao != "jpg") && ($extensao != "png") && ($extensao != "jpeg") && ($extensao != "gif")){
            $flag = 0;
        }

        if($flag){
            move_uploaded_file($_FILES['foto']['tmp_name'], $save_name);
            $foto = $token.$photo_name;
            return $foto;
        }else{
            $foto = "";
            return false;
        }
    }

    public function deleteImagem($dir, $archiveName){
        unlink($dir.$archiveName);
    }
    
}