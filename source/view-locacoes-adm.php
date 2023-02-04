<?php
require './Connection.php';
require './MODELS/Auth.php';
require_once('./DAO/AlunoDaoMysql.php');
require './DAO/LocacaoDaoMysql.php';

$auth = new Auth($pdo, $base);
$info = $auth->checkToken();
$level = $auth->checkLevel();
$locacaoDao = new LocacaoDaoMysql($pdo);
if ($level != 'bibliotecario') {
    header('location:./index.php');
}
$status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING);

$numeroAtivas = $locacaoDao->countRows('ativos');
$numeroAtrasados = $locacaoDao->countRows('atrasados');
$numeroHistorico = $locacaoDao->countRows('historico');

//Verificar se status está preenchido...
if ($status == 'ativo') {
    $locacoes = $locacaoDao->selectAllActive();
    //SE NÂO TIVER ATIVO MSG DE PAG VAZIA
    if ($locacoes == null) {
        $_SESSION['flash'] = "           
        <div class='isa_warning'>
        <i class='fa fa-warning'></i>
            Nenhuma locação ativa encontrada.
        </div>";
    }

} else if ($status == 'atrasado') {
    $locacoes = $locacaoDao->selectAllLate();
    //SE NÂO TIVER ATRASO MSG DE PAG VAZIA
    if ($locacoes == null) {
        $_SESSION['flash'] = "            
        <div class='isa_warning'>
        <i class='fa fa-warning'></i>
            Nenhuma locação atrasada encontrada.
        </div>";
    }

} else if ($status == 'historico') {
    $locacoes = $locacaoDao->selectAllRecord();
    if ($locacoes == null) {
        $_SESSION['flash'] = "            
        <div class='isa_warning'>
        <i class='fa fa-warning'></i>
            Nenhuma locação encontrada.
        </div>";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/mensagens.css">
    <link rel="stylesheet" href="./assets/css/geral.css">
    <link rel="stylesheet" href="./assets/css/locacoesAdm.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
          rel="stylesheet">
    <title>Biblioteca</title>
</head>
<body>
<header class="container cabecalho">
    <div class="logo">
        <img src="./assets/R.png" alt="">
    </div>
    <?php
    require_once('./templates/menu/logged-funcionario.php')
    ?>
</header>
<div>
    <div class="voltar-pesquisar">
        <span style="cursor:pointer" onclick="location.href='./index.php'">VOLTAR</span>
        <form name="frmBusca" action="">
            <label for="pesquisa"></label>
            <input type="text" id="pesquisa" placeholder="Pesquisar">
            <input type="submit" value="Pesquisar">
        </form>
    </div>
    <div class="locacoes-menu">
        <span style="cursor:pointer;" onclick="location.href='./view-locacoes-adm.php?status=ativo'"
              class="<?= ($status == "ativo") ? 'selected-ativo' : 'ativos'; ?> " id="ativos">ATIVOS <span
                    class="countRow"><?= $numeroAtivas; ?></span></span>
        <span style="cursor:pointer" onclick="location.href='./view-locacoes-adm.php?status=atrasado'"
              class="<?= ($status == "atrasado") ? 'selected-atrasado' : 'atrasados'; ?> "
              id="atrasados">ATRASADOS <span class="countRow"><?= $numeroAtrasados; ?></span></span>
        <span style="cursor:pointer" onclick="location.href='./view-locacoes-adm.php?status=historico'"
              class="<?= ($status == "historico") ? 'selected-historico' : 'historico'; ?> "
              id="historico">HISTÓRICO <span class="countRow"><?= $numeroHistorico; ?></span></span>
    </div>
    <main>
        <?php
        echo $_SESSION['flash'] ?? '';
        $_SESSION['flash'] = '';
        ?>
        <?php
        if (!empty($locacoes)) {
            $alunoDao = new AlunoDaoMysql($pdo);
            foreach ($locacoes as $locacao) {
                $aluno = $alunoDao->findByRa($locacao->aluno['ra']);
                ?>
                <div class="locacao-desc">
                    <div class="image-desc">
                        <div class="image">
                            <img src="./storage/livrosImages/<?= $locacao->codigoLivro['foto'] ?>">
                        </div>
                        <div class="descricao">
                            <div class="titulo">Locação
                                <?= $locacao->codigoLivro['nome'] ?>
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
                            <div class="autor">Locação:
                                <strong><?= $locacao->aluno['nome'] ?></strong>

                            </div>
                            <div class="contato">Contato:
                                <strong><?= $aluno->email ?></strong>
                            </div>


                            <div class="situacao">
                                <div class="dataRetirada">
                                    <?php
                                    if ($locacao->dataRetirada == null) {
                                        echo "<div class='red'> Não retirado
                                                            </div>";
                                    } else {
                                        echo "<div class='green'>Retirado
                                                            <strong>(" . $locacao->dataRetirada . ")</strong>
                                                            </div>";
                                    }
                                    ?>
                                </div>
                                <div class="dataDevolucao">
                                    <?php
                                    if ($locacao->dataDevolucao == null) {
                                        echo "<div class='red'> 
                                                        Não devolvido
                                                        </div>";
                                    } else {
                                        echo "<div class='green'> Devolvido <strong>(" . $locacao->dataDevolucao . "
                                                        )</strong></div>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="buttons">
                        <form action="alterar-data_action.php" method="GET">
                            <input type="hidden" name="codigo" value="<?= $locacao->codigoLocacao; ?>">
                            <div class="ver-livro">Ver Livro</div>
                            <div class="retirado">
                                <input type="checkbox" name="retirado">
                                Alterar Retirada
                            </div>
                            <div class="devolucao">
                                <input type="checkbox" name="devolvido">
                                Alterar Devolução
                            </div>
                            <div class="salvar">
                                <input type="submit" value="Alterar">
                            </div>
                        </form>
                    </div>
                </div>

                <?php
            }
        }
        ?>

    </main>
</div>
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