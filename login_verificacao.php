<?php

    //login_verificacao.php
    session_start();
    if(isset($_SESSION['user']))
    {
        include 'cabecalho.php';
        echo '
            <div class="mensagem">
                Já se encontra ligado no site.<br><br>
                <a href="forum.php">Avançar</a>
            </div>
        ';
        include 'rodape.php';
        exit;
    }

    include 'cabecalho.php';

    $utilizador = "";
    $password_utilizador = "";

    if(isset($_POST['text_utilizador']))
    {
        $utilizador = $_POST['text_utilizador'];
        $password_utilizador = $_POST['text_password'];
    }

    //Verificar se os campos foram preenchidos
    if($utilizador == "" || $password_utilizador == "")
    {
        echo '<div class="erro">
                Não foram preenchidos os campos necessários.<br><br>
                <a href="index.php">Tente novamente</a>
            </div>
        ';
        include 'rodape.php';
        exit;
    }

    $passwordEncriptada = md5($password_utilizador);

    include 'config.php';
    $ligacao = new PDO("mysql:dbname=$base_dados;host=$host", $user, $password);
    $motor = $ligacao->prepare("SELECT * FROM users WHERE username = ? AND pass = ?");
    $motor->bindParam(1, $utilizador, PDO::PARAM_STR);
    $motor->bindParam(2, $passwordEncriptada, PDO::PARAM_STR);
    $motor->execute();

    $ligacao = null;

    if($motor->rowCount() == 0)
    {
        echo '<div class="erro">
                Dados de login inválidos.<br><br>
                <a href="index.php">Tente novamente</a>
            </div>
        ';
        include 'rodape.php';
        exit;
    }
    else
    {
        $dados_user = $motor->fetch(PDO::FETCH_ASSOC);
        $_SESSION['id_user'] = $dados_user['id_user'];
        $_SESSION['user'] = $dados_user['username'];
        $_SESSION['avatar'] = $dados_user['avatar'];

        echo '<div class="login_sucesso">
                Bem-vindo ao forum, <strong>'.$_SESSION['user'].'</strong><br><br>
                <a href="forum.php">Continuar</a>
            </div>
        ';
    }

    include 'rodape.php';
?>