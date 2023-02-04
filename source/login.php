<html>
<?php
session_start();
if(isset($_SESSION['token'])){
    if(!$_SESSION['token'] == ''){
        header('Location: ./index.php');
    }
}

?>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/mensagens.css">
    <link rel="stylesheet" href="./assets/css/cadastros.css">
    <link rel="stylesheet" href="./assets/css/geral.css">
    <title>Login</title>
</head>

<body style="background: linear-gradient(to top, #012E6A, #011D40);" >
    <header class="cabecalho-login-cad">
                <div class="logo">
                    BIBLIOTECA
                </div>
    </header>
    <div class="pagina-login">
        <div class="cadastro-geral">
            <div class="panel-body">
                <h1>Login</h1>
                <?php

                echo $_SESSION['flash'] ?? '';
                $_SESSION['flash'] = '';

                ?>
                <a href="./cadastrar.php">Não possui cadastro ? Clique aqui.</a>
                <form class="form-horizontal" role="form" action="./login_action.php" method="POST">
                    <div class="form-group">
                        <!-- <div class="form-label">
                            <label for="email" class="control-label">Email</label>
                        </div> -->
                        <div class="form-input">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" />
                        </div>
                    </div>
                    <div class="form-group">
                        <!-- <div class="form-label">
                            <label for="senha" class="control-label">Senha</label>
                        </div> -->
                        <div class="form-input">
                            <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="cad-buttons">
                            <button type="submit" class="btn btn-success">
                                Login
                            </button>
                            <button type="reset" class="btn btn-default" onclick="history.go(-1)">
                                Cancelar
                            </button>
                        </div>
                    </div>

                </form>

                

            </div>
        </div>
    </div>
</body>

</html>