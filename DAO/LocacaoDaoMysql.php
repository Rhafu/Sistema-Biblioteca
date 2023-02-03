<?php
require_once('./MODELS/Locacao.php');

class LocacaoDaoMysql{


    private $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    private function generateLocacao($array){
        $locacao = new Locacao();

        $locacao->codigoLocacao = $array['codigoLocacao'];
        $locacao->codigoLivro = array("codigo"=>$array['codigoLivro'], "nome"=>$array['nomeLivro'], "foto"=>$array['imagemLivro']);
        $locacao->aluno = array("ra"=>$array['raAluno'], "nome"=>$array['nomeAluno']);
        $locacao->dataInicio = $array['dataInicio'];
        $locacao->dataTermino = $array['dataTermino'];
        $locacao->dataRetirada = $array['dataRetirada'];
        $locacao->dataDevolucao = $array['dataDevolucao'];
        $locacao->atrasado = $array['atrasado'];
        $locacao->ativo = $array['ativo'];

        return $locacao;
    }

    public function checkLates(){
        $datasLocadasQuery = "SELECT codigoLocacao, dataInicio, dataTermino, dataRetirada, dataDevolucao
                                FROM locacoes WHERE dataDevolucao IS NULL";
        $datasLocadas = $this->pdo->query($datasLocadasQuery);
        $datasLocadasSelect = $datasLocadas->fetchAll();

        foreach ($datasLocadasSelect as $data){
            $americaTimeZone = new DateTimeZone('AMERICA/SAO_PAULO');

            $dataObjeto = new DateTime($data['dataTermino'], $americaTimeZone);
            $dataAtual = new DateTime("now", $americaTimeZone);

            if($dataAtual > $dataObjeto){
                echo $data['codigoLocacao'];
                echo "ATRASADO!";
            }
        }
    }

    
    public function selectAll($ra){

        try{
            // $sql = $this->pdo->prepare('SELECT * FROM LIVROS');

            //ADICIONAR QUERY DE ALUNO PARA ADICIONA-LO ACIMA **SE FOR NECESSÁRIO
            $sql = $this->pdo->prepare('SELECT * FROM locacoes, livros, alunos WHERE locacoes.codigoLivro = livros.codigoLivro AND locacoes.raAluno = :ra AND locacoes.raAluno = alunos.raAluno');
            $sql->bindValue(':ra', $ra);
            $sql->execute();
        }catch(PDOException $erro){
            echo 'Erro no Selecionar todos as Locacoes: '.$erro->getCode();
            echo 'Mensagem do erro: '.$erro->getMessage();
        }

        if($sql->rowCount() > 0){
            $arrayLocacoes = $sql->fetchAll(PDO::FETCH_ASSOC);
            $locacoes = [];
            foreach($arrayLocacoes as $locacaoDesc){
                $locacao = $this->generateLocacao($locacaoDesc);
                array_push($locacoes, $locacao);
            }

            return $locacoes;
        }

        return false;
    }

    public function selectAllActive(){

        try{
            // $sql = $this->pdo->prepare('SELECT * FROM LIVROS');
            $sql = $this->pdo->prepare('SELECT * FROM locacoes, livros, alunos 
                                                WHERE locacoes.codigoLivro = livros.codigoLivro 
                                                 AND locacoes.raAluno = alunos.raAluno 
                                                 AND atrasado = 0
                                                 AND dataDevolucao IS NULL
                                                 ');
            $sql->execute(); 

            if($sql->rowCount() > 0){
                $arrayLocacoes = $sql->fetchAll(PDO::FETCH_ASSOC);
                $locacoes = [];
                foreach($arrayLocacoes as $locacaoDesc){
                    $locacao = $this->generateLocacao($locacaoDesc);
                    array_push($locacoes, $locacao);
                }
                
                return $locacoes;
            }

            return false;
        }catch(PDOException $erro){
            echo 'Erro no Selecionar todos as Locacoes: '.$erro->getCode();
            echo 'Mensagem do erro: '.$erro->getMessage();
        }

    }

    public function selectAllLate(){

        try{
            // $sql = $this->pdo->prepare('SELECT * FROM LIVROS');
            $sql = $this->pdo->prepare('SELECT * FROM locacoes, livros, alunos 
                                                WHERE locacoes.codigoLivro = livros.codigoLivro 
                                                 AND locacoes.raAluno = alunos.raAluno 
                                                 AND atrasado = 1
                                                 AND dataDevolucao IS NULL');
            $sql->execute();
        }catch(PDOException $erro){
            echo 'Erro no Selecionar todos as Locacoes: '.$erro->getCode();
            echo 'Mensagem do erro: '.$erro->getMessage();
        }

        if($sql->rowCount() > 0){
            $arrayLocacoes = $sql->fetchAll(PDO::FETCH_ASSOC);
            $locacoes = [];
            foreach($arrayLocacoes as $locacaoDesc){
                $locacao = $this->generateLocacao($locacaoDesc);
                array_push($locacoes, $locacao);
            }

            return $locacoes;
        }

        return false;

    }

    public function selectAllRecord(){
        try{
            // $sql = $this->pdo->prepare('SELECT * FROM LIVROS');
            $sql = $this->pdo->prepare('SELECT * FROM locacoes, livros, alunos 
                                                WHERE locacoes.codigoLivro = livros.codigoLivro 
                                                 AND locacoes.raAluno = alunos.raAluno 
                                                 AND dataDevolucao IS NOT NULL
                                                 AND dataRetirada IS NOT NULL');
            $sql->execute();
        }catch(PDOException $erro){
            echo 'Erro no Selecionar todos as Locacoes: '.$erro->getCode();
            echo 'Mensagem do erro: '.$erro->getMessage();
        }

        if($sql->rowCount() > 0){
            $arrayLocacoes = $sql->fetchAll(PDO::FETCH_ASSOC);
            $locacoes = [];
            foreach($arrayLocacoes as $locacaoDesc){
                $locacao = $this->generateLocacao($locacaoDesc);
                array_push($locacoes, $locacao);
            }

            return $locacoes;
        }

        return false;

    }

    public function insert(Locacao $locacao){
        if(!empty($locacao)){
            try{
                $sql = $this->pdo->prepare("INSERT INTO locacoes 
                (codigoLivro,
                raAluno,
                dataInicio,
                dataTermino)
                VALUES
                (:codigo,
                :ra,
                :dataDe,
                :dataAte)
                ");

                $sql->bindValue(':codigo', $locacao->codigoLivro);
                $sql->bindValue(':ra', $locacao->raAluno);
                $sql->bindValue(':dataDe', $locacao->dataInicio);
                $sql->bindValue(':dataAte', $locacao->dataTermino);

                $sql->execute();

                if($sql->rowCount() > 0){
                    return true;
                }else{
                    return false;
                }

                //veerificar o rowcount
            }catch(PDOException $erro){
                echo 'Erro na inserção Locação: '.$erro->getCode();
                echo 'Mensagem do erro: '.$erro->getMessage();
            }
        }
    }

    public function checkDateStatus($codigo){
        try{
            //checar o status das duas datas e retornar array de respostas
            $sql = $this->pdo->prepare("SELECT dataRetirada, dataDevolucao FROM locacoes 
                                                WHERE codigoLocacao = :codigo");
            $sql->bindValue(':codigo', $codigo);
            $sql->execute();

            if($sql->rowCount() > 0){
                $resultadoData = $sql->fetch(PDO::FETCH_ASSOC);
            }else {
                return false;
            }

        }catch (PDOException $erro){
            echo 'Erro na inserção Locação: '.$erro->getCode();
            echo 'Mensagem do erro: '.$erro->getMessage();
        }

        return $resultadoData;
    }

    //Adicionar a retirada de status de atraso
    public function alterDateStatus($tipo, $codigo)
    {
        if(!empty($tipo) && !empty($codigo)){
            $dataAtual = date('Y/m/d');
            try {

                //ESTE DEU CERTO
                if($tipo == "retirada"){
                    $resultadoData = $this->checkDateStatus($codigo);

                    if($resultadoData['dataRetirada'] == null){
                        $sql = $this->pdo->prepare("UPDATE locacoes SET
                            dataRetirada = :dataAtual WHERE codigoLocacao = :codigo
                            ");
                        $sql->bindValue(':dataAtual', $dataAtual);
                        $sql->bindValue(':codigo', $codigo);
                        $sql->execute();
                    }else{
                        $sql = $this->pdo->prepare("UPDATE locacoes SET
                                    dataRetirada = null WHERE codigoLocacao = :codigo
                                    ");
                        $sql->bindValue(':codigo', $codigo);
                        $sql->execute();
                    }
                }else if($tipo == "devolucao"){
                    $resultadoData = $this->checkDateStatus($codigo);

                    if($resultadoData['dataDevolucao'] == null){
                        $sql = $this->pdo->prepare("UPDATE locacoes SET
                        dataDevolucao = :dataAtual, dataRetirada = :dataAtual, ativo = 0 WHERE codigoLocacao = :codigo
                        ");
                        $sql->bindValue(':dataAtual', $dataAtual);
                        $sql->bindValue(':codigo', $codigo);
                        $sql->execute();
                    }else{
                        $sql = $this->pdo->prepare("UPDATE locacoes SET
                                    dataDevolucao = null, ativo = 1 WHERE codigoLocacao = :codigo
                                    ");
                        $sql->bindValue(':codigo', $codigo);
                        $sql->execute();
                    }
                }else if($tipo == "ambos"){
                    $resultadoData = $this->checkDateStatus($codigo);

                    if($resultadoData['dataDevolucao'] == null && $resultadoData['dataRetirada'] == null){
                        $sql = $this->pdo->prepare("UPDATE locacoes SET
                        dataRetirada = :dataAtual, dataDevolucao = :dataAtual, ativo = 0 WHERE codigoLocacao = :codigo
                        ");
                        $sql->bindValue(':dataAtual', $dataAtual);
                        $sql->bindValue(':codigo', $codigo);
                        $sql->execute();
                    }else if ($resultadoData['dataDevolucao'] == null && $resultadoData['dataRetirada'] != null){
                        $sql = $this->pdo->prepare("UPDATE locacoes SET
                        dataDevolucao = :dataAtual, ativo = 0 WHERE codigoLocacao = :codigo
                        ");
                        $sql->bindValue(':dataAtual', $dataAtual);
                        $sql->bindValue(':codigo', $codigo);
                        $sql->execute();
                    }/*else if ($resultadoData['dataDevolucao'] != null && $resultadoData['dataRetirada'] == null){
                        $sql = $this->pdo->prepare("UPDATE locacoes SET
                        dataDevolucao = null, dataRetirada = :dataAtual WHERE codigoLocacao = :codigo
                        ");
                        $sql->bindValue(':dataAtual', $dataAtual);
                        $sql->bindValue(':codigo', $codigo);
                        $sql->execute();
                    }*/else{
                        $sql = $this->pdo->prepare("UPDATE locacoes SET
                        dataRetirada = null, dataDevolucao = null, ativo = 1 WHERE codigoLocacao = :codigo
                        ");
                        $sql->bindValue(':codigo', $codigo);
                        $sql->execute();
                    }
                }
            }catch (PDOException $erro){
                echo 'Erro na alteração da data: '.$erro->getCode();
                echo 'Mensagem do erro: '.$erro->getMessage();
            }

        }

        if($sql->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function isAvailable($codigo, $dataDe, $dataAte, $ra){

        try{
            $flag = 1; //Flag serve para identificar um erro, quando zerada mostra que um erro já foi encontrado e uma mensagem já foi guardada

            $timeDe = strtotime($dataDe);
            $timeAte = strtotime($dataAte);
            $timeHoje = new DateTime('now');

            $livrosLocadosQuery = "SELECT COUNT(codigoLivro) FROM locacoes WHERE dataDevolucao IS NULL AND codigoLivro = $codigo";

            $livrosDisponiveisQuery = "SELECT quantidadeLivro FROM livros WHERE codigoLivro = $codigo";

            $locacaoAtrasadaQuery = "SELECT atrasado FROM locacoes WHERE ativo = 1 and raAluno = $ra";
        
            //Livros que estão locados e que não tem data de devolução preenchida
            $livrosLocados = $this->pdo->query($livrosLocadosQuery);
        
            //Quantidade de Livros disponíveis para serem locados
            $livrosDisponiveis = $this->pdo->query($livrosDisponiveisQuery);

            //Se possui locacao atrasada
            $locacaoAtrasada = $this->pdo->query($locacaoAtrasadaQuery);
        
            $livrosLocadosSelect = $livrosLocados->fetch();
            $livrosDisponiveisSelect = $livrosDisponiveis->fetch();
            $locacaoAtrasadaSelect = $locacaoAtrasada->fetch();

            // Verificando datas das locações para ver se a data  está disponível
            $datasLocadasQuery = "SELECT dataInicio, dataTermino FROM locacoes WHERE dataDevolucao IS NULL AND codigoLivro = $codigo";
                
            $datasLocadas = $this->pdo->query($datasLocadasQuery);

            $datasLocadasSelect = $datasLocadas->fetchAll();



            //Verificando quantidade de livros e locações feitas
            if($livrosLocadosSelect[0] >= $livrosDisponiveisSelect[0]){

                //Percorrendo cada locação para comparação de data
                foreach($datasLocadasSelect as $data){
                    $timeInicio = strtotime($data['dataInicio']);
                    $timeTermino = strtotime($data['dataTermino']);

                    //Verificando se data é invalida DataAte < DataDe
        
                        //Verificando disponibilidade de Data, tornando toda a condição negativa 
                        if($timeDe >= $timeInicio && $timeDe <= $timeTermino || $timeAte <= $timeTermino && $timeAte >= $timeInicio){
                            $_SESSION['flash'] =     "
                                <div class='isa_warning'>
                                <i class='fa fa-warning'></i>
                                    Data Indisponível, escolha outra.
                                </div>
                            ";
                            $flag = 0;
                            
                            return $flag;
                        }
                    
                    
                }
            }
            //Verificando posição das datas
            if($timeAte < $timeDe || $timeHoje > $timeDe && $flag = 1){

                $_SESSION['flash'] =     "
                    <div class='isa_warning'>
                    <i class='fa fa-warning'></i>
                        Data Inválida.
                    </div>
                ";
                $flag = 0;
                return $flag;
                
            }

            //Verificando se o livro já foi locado pelo cliente atual
            $locacoesCliente = $this->pdo->prepare("SELECT * FROM locacoes WHERE dataDevolucao IS NULL AND codigoLivro = $codigo AND raAluno = ".$ra);

            $locacoesCliente->execute();

            if($locacoesCliente->rowCount() > 0 && $flag = 1){

                $_SESSION['flash'] =     "
                    <div class='isa_warning'>
                    <i class='fa fa-warning'></i>
                        Livro já está sendo locado por você.
                    </div>
                ";
                $flag = 0;
                return $flag;
                
            }

            if($locacaoAtrasadaSelect[0] == 1 && $flag = 1){

                $_SESSION['flash'] =     "
                    <div class='isa_warning'>
                    <i class='fa fa-warning'></i>
                        Você possui locação atrasada, e não pode locar até devolver o livro atrasado.
                    </div>
                ";
                $flag = 0;
                return $flag;
            }

            if($flag == 1){
                return true;
            }else{
                return false;
            }

        }catch(PDOException $erro){
            echo 'Erro no verificar Disponibilidade locação: '.$erro->getCode();
            echo 'Mensagem do erro: '.$erro->getMessage();
        }


    }

    public function countRows($typeLocacao){
        if($typeLocacao == 'ativos'){
            try{
                $sql = $this->pdo->prepare("SELECT COUNT(*) FROM locacoes WHERE dataDevolucao is NULL AND atrasado = 0");
                $sql->execute();
                $contagem = $sql->fetch();

                return $contagem[0];
            }catch(PDOException $erro){
                echo 'Erro no contar locação: '.$erro->getCode();
                echo 'Mensagem do erro: '.$erro->getMessage();
            }

            return false;
        }elseif ($typeLocacao == 'atrasados'){
            try{
                $sql = $this->pdo->prepare("SELECT COUNT(*) FROM locacoes WHERE atrasado = 1 and dataDevolucao is null");
                $sql->execute();
                $contagem = $sql->fetch();

                return $contagem[0];
            }catch(PDOException $erro){
                echo 'Erro no contar locação: '.$erro->getCode();
                echo 'Mensagem do erro: '.$erro->getMessage();
            }

            return false;
        }elseif ($typeLocacao == 'historico'){
            try{
                $sql = $this->pdo->prepare("SELECT COUNT(*) FROM locacoes WHERE dataDevolucao is not null");
                $sql->execute();
                $contagem = $sql->fetch();

                return $contagem[0];
            }catch(PDOException $erro){
                echo 'Erro no contar locação: '.$erro->getCode();
                echo 'Mensagem do erro: '.$erro->getMessage();
            }


            return false;
        }

    }

    public function checkPossibilityOfCancel($codigoLocacao){
        if(isset($codigoLocacao)){
            try{
                $sql = $this->pdo->prepare("SELECT * FROM locacoes WHERE codigoLocacao = :codigoLocacao");
                $sql->bindValue(":codigoLocacao", $codigoLocacao);
                $sql->execute();

                $locacao = $sql->fetch(PDO::FETCH_ASSOC);
                // echo "<pre>";
                // var_dump($locacao);
                // echo "</pre>";
                
            }catch(PDOException $erro){
                echo 'Erro no checagem de cancelamento: '.$erro->getCode();
                echo 'Mensagem do erro: '.$erro->getMessage();
            }

            if($locacao['ativo'] == 1 && $locacao['dataRetirada'] == NULL && $locacao['atrasado'] == 0){
                return true;
            }else{
                return false;
            }
        }
    }

    public function cancel($codigoLocacao){
        if(isset($codigoLocacao)){
            try{
                $sql = $this->pdo->prepare("DELETE FROM locacoes WHERE codigoLocacao = :codigoLocacao");
                $sql->bindValue(":codigoLocacao", $codigoLocacao);
                
                $sql->execute();

                
                // echo "<pre>";
                // var_dump($locacao);
                // echo "</pre>";
                
            }catch(PDOException $erro){
                echo 'Erro no checagem de cancelamento: '.$erro->getCode();
                echo 'Mensagem do erro: '.$erro->getMessage();
            }

            if($sql->rowCount()){
                return true;
            }else{
                return false;
            }


        }
    }


}