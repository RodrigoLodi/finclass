<?php
    
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        #fundo-login { background-image: linear-gradient(to bottom, rgb(229 150 0), rgb(245 195 99)); width: 70vw; max-width: 400px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display: flex; align-items: center; justify-content: center; height: 70vh; max-height: 500px; text-align: center; border-radius: 20px; box-shadow: 6px 7px 10px #0000003d; }

        .login { margin-top: 50px; }

        .login, .senha { color: white; font-weight: bold; font-size: 1.2em; }

        .erro { font-weight: bold; color: red }

        .login > img, .senha > img { width: 22px }

        .login > input, .senha > input { border: 1px solid #0000008a; border-radius : 5px; width: 80%; }

        #botao { background-color: white; color: black; font-weight: bold; border: none; margin-top: 10px; }

        #botao:hover { background-color: black; color: white }
    </style>
    <link rel="stylesheet" href="/site-imperio/estilo/bootstrap/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="row gx-0" id="fundo-login">

        <div class="row" style="top: 0; position: absolute; margin-top: 30px">
            <img src="../estilo/imagens/header/logo.png" alt="" style="max-width: 70%; margin: auto">
        </div>

        <div class="row">
                <p class="login">
                    <img src="../estilo/imagens/login/login.png" alt="">
                    <input type="text" name="login" placeholder="Usuario">
                </p>

                <p class="senha">
                <img src="../estilo/imagens/login/senha.png" alt="">
                    <input type="password" name="senha" placeholder="Senha">
                </p>

                <p class="erro"></p>

                <p>
                    <button type="submit" class="btn btn-primary" id="botao">Entrar</button>
                </p>
        </div>

    </div>
    <script src="/site-imperio/estilo/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>