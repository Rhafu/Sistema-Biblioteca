<?php
require './Connection.php';
require './MODELS/Auth.php';
require './DAO/LocacaoDaoMysql.php';

$auth = new Auth($pdo, $base);
$info = $auth->checkToken();
$level = $auth->checkLevel();
$locacaoDao = new LocacaoDaoMysql($pdo);
if($level != 'aluno'){
    header('location:./index.php');
}
$locacoes = $locacaoDao->selectAll($info->ra);

?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/mensagens.css">
    <link rel="stylesheet" href="./assets/css/geral.css">
    <link rel="stylesheet" href="./assets/css/index.css">
    <link rel="stylesheet" href="./assets/css/cadastros.css">
    <link rel="stylesheet" href="./assets/css/locacoes.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <title>Biblioteca</title>
</head>
<body>
        <header class="container cabecalho">
                <div class="logo">
                    <img src="./assets/R.png" alt="">
                </div>
                <?php
                    require_once('./templates/menu/logged-cliente.php')
                ?>
        </header>
        <div>
            <div class="voltar-pesquisar">
                <span style="cursor:pointer" onclick="window.history.go(-1)">VOLTAR</span>
                <form name="frmBusca" action="">
                        <label for="pesquisa"></label>
                        <input type="text" id="pesquisa" placeholder="Pesquisar">
                        <input type="submit" value="Pesquisar">
                </form>
            </div>
                <main>
                    <?php
                        echo $_SESSION['flash'] ?? '';
                        $_SESSION['flash'] = '';
                    ?>
                    <?php
                    if($locacoes == null){
                        echo "            
                        <div class='isa_warning'>
                        <i class='fa fa-warning'></i>
                            Nenhuma locação encontrada.
                        </div>";
                    }else
                    {
                        foreach($locacoes as $locacao){
                        ?>
                            <div class="locacao-desc">

                                <div class="image-desc">
                                    <div class="image">
                                        <img src="./storage/livrosImages/<?=$locacao->codigoLivro['foto']?>">
                                    </div>
                                    <div class="descricao">
                                        <div class="titulo">Locação
                                            <?=$locacao->codigoLivro['nome']?>
                                        </div>
                                        <div class="data-desc">
                                            Locação requisitada do dia
                                            <?php
                                                $data = $locacao->dataInicio;
                                                $time = strtotime($data);
                                                echo date('d/m/Y', $time);
                                            ?>
                                            até o dia
                                            <?php
                                                $data = $locacao->dataTermino;
                                                $time = strtotime($data);
                                                echo date('d/m/Y', $time);
                                            ?>
                                        </div>
                                        <div class="situacao">
                                            <div class="dataRetirada">
                                                    <?php
                                                        if($locacao->dataRetirada == null){
                                                            echo "<div class='red'> Não retirado
                                                            </div>";
                                                        }else{
                                                            echo "<div class='green'>Retirado
                                                            <strong>(".$locacao->dataRetirada."
                                                            )</strong>
                                                            </div>";
                                                        }
                                                    ?>
                                            </div>
                                            <div class="dataDevolucao">
                                                    <?php
                                                    if($locacao->dataDevolucao == null){
                                                        echo "<div class='red'> 
                                                        Não devolvido
                                                        </div>";
                                                    }else{
                                                        echo "<div class='green'> Devolvido <strong>(".$locacao->dataDevolucao."
                                                        )</strong></div>";
                                                    }
                                                    ?>
                                            </div>
                                            <?php
                                            if ($locacao->atrasado == 1 && $locacao->ativo == 1){
                                                echo "<div class='isa_warning'>
                                                        <i class='fa fa-warning'></i>
                                                        Livro está em atraso
                                                      </div>";
                                            }

                                            ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="buttons">
                                    <div class="ver-livro" onClick="location.href='descricao.php?id=<?= $locacao->codigoLivro['codigo'] ?>'">Ver Livro</div>
                                    <div class="alterar" >Alterar Data</div>
                                    <div class="comprovante">Imprimir Comprovante</div>
                                    <form action="cad-locacoes-cancel_action.php" method="POST"> 
                                        <input type="hidden" name="codigoLocacao" id="cancel-id" value="<?= $locacao->codigoLocacao ?>">
                                        <input id="cancelar" type="submit" class="cancelar" value="Cancelar">
                                    </form>
                                       
                                </div>
                            </div>

                    <?php
                            }
                    }
                    ?>

                </main>
            </div>
        <footer class="container">
            <div class="logo-site">
                <h1>Biblioteca</h1>
            </div>
            <div class="contato">
                <h2>Contato</h2>
                email:rararaar@gmail.com
            </div>
        </footer>
</body>

</html>