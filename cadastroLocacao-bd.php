<?php

require_once('./connection.php');

$codigo = filter_input(INPUT_POST, "codigo", FILTER_SANITIZE_NUMBER_INT);
$cpf = filter_input(INPUT_POST, "cpf", FILTER_SANITIZE_STRING);
$dataDe = filter_input(INPUT_POST, "dataDe");
$dataAte = filter_input(INPUT_POST, "dataAte");
$flag = 1; //Flag serve para identificar um erro, quando zerada mostra que um erro já foi encontrado e uma mensagem já foi guardada
$timeDe = strtotime($dataDe);
$timeAte = strtotime($dataAte);

try{
    
    $comandoSQLInsert = $connection->prepare("INSERT INTO locacoes 
    (codigoLivro, cpfCliente, dataInicio, dataTermino)
    VALUES
    (:codigo, :cpf, :dataDe, :dataAte)
    ");

    $livrosLocadosQuery = "SELECT COUNT(codigoLivro) FROM locacoes WHERE dataDevolucao IS NULL AND codigoLivro = $codigo";

    $livrosDisponiveisQuery = "SELECT quantidadeLivro FROM livros WHERE codigoLivro = $codigo";

    //Livros que estão locados e que não tem data de devolução preenchida
    $livrosLocados = $connection->query($livrosLocadosQuery);

    //Quantidade de Livros disponíveis para serem locados
    $livrosDisponiveis = $connection->query($livrosDisponiveisQuery);

    $livrosLocadosSelect = $livrosLocados->fetch();
    $livrosDisponiveisSelect = $livrosDisponiveis->fetch();

    // Verificando datas das locações para ver se a data  está disponível
    $datasLocadasQuery = "SELECT dataInicio, dataTermino FROM locacoes WHERE dataDevolucao IS NULL AND codigoLivro = $codigo";
    
    $datasLocadas = $connection->query($datasLocadasQuery);

    $datasLocadasSelect = $datasLocadas->fetchAll();

    //Verificando quantidade de livros e locações feitas
    if($livrosLocadosSelect[0] >= $livrosDisponiveisSelect[0]){
        //Percorrendo cada locação para comparação de data
        foreach($datasLocadasSelect as $data){
            $timeInicio = strtotime($data['dataInicio']);
            $timeTermino = strtotime($data['dataTermino']);

            //Verificando se data é invalida DataAte < DataDe

                //Verificando disponibilidade de Data, tornando toda a condição negativa 
                if($timeDe >= $timeInicio && $timeDe <= $timeTermino && $timeAte <= $timeTermino && $timeAte >= $timeInicio){
                    session_start();
        
                    $_SESSION['locarLivroErro'] =     "
                        <div class='isa_warning'>
                        <i class='fa fa-warning'></i>
                            Data Indisponível, escolha outra.
                        </div>
                    ";
                    $flag = 0;
                    header('location:../view-locacoes.php');
                }
            
            
        }
    }

    if($timeAte < $timeDe && $flag = 1){
        session_start();

        $_SESSION['locarLivroErro'] =     "
            <div class='isa_warning'>
            <i class='fa fa-warning'></i>
                Data Inválida.
            </div>
        ";
        $flag = 0;
        header('location:../view-locacoes.php');
    }

    //Verificando se o livro já foi locado pelo cliente atual
    $locacoesCliente = $connection->prepare("SELECT * FROM locacoes WHERE dataDevolucao IS NULL AND codigoLivro = $codigo AND cpfCliente = ".$cpf);

    $locacoesCliente->execute();

    if($locacoesCliente->rowCount() > 0 && $flag = 1){
        session_start();

        $_SESSION['locarLivroErro'] =     "
            <div class='isa_warning'>
            <i class='fa fa-warning'></i>
                Livro já está sendo locado por você.
            </div>
        ";
        $flag = 0;
        header('location:../view-locacoes.php');
    }


    if($flag == 1){
            session_start();

            $comandoSQLInsert->execute(array(
                ':codigo' => $codigo,
                ':cpf' => $cpf,
                ':dataDe' => $dataDe,
                ':dataAte' => $dataAte
            )); 

            $_SESSION['locarLivroSucesso'] = 
            "
            <div class='isa_success'>
                <i class='fa fa-check'></i>
                Locação Realizada com Sucesso!
            </div>
            ";
            
            header('location:../view-locacoes.php');
        }
    
    $connection = null;


}catch(PDOException $erro){
    echo "ERRO: ".$erro->getCode()."<br/>";
    echo "Messagem: <br>".$erro->getMessage();
}