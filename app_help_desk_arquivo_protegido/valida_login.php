<?php

    session_start();

    $_SESSION['x'] = 'Oi, sou um valor de sessão !'; //criando variavel dentro da session e atribuindo valor

    //$_GET -> super-Global GET, esse method deixa o email e a senha no url
    //$_POST -> super-Global POST, esse method impedir de aparecer o email e a senha na url do browser
    /*
    print_r($_GET); //para imprimir o $_GET, porque esse method são array
    echo '<br/>';
 
    echo $_GET['email']; //recuperando o email
    echo '<br/>';
    echo $_GET['senha'] //recuperando a senha
    */

    //------------------------------------------------------

    //variavel que vai verificar se a autenticação foi realizada
    $usuario_autenticado = false;
    $usuario_id = null;
    $usuario_perfil_id = null;

    $perfis = [1 => 'Administrativo', 2 => 'Usuário'];

    //usuarios do sistema
    $usuarios_app = [ //criando ususario dentro do array só para teste já que não domino os bancos de dados ainda
        array('id' => 1, 'email' => 'adm@teste.com.br', 'senha' => '123456', 'perfil_id' => 1), //array associativo dentro de outro array
        array('id' => 2, 'email' => 'user@teste.com.br', 'senha' => '123456', 'perfil_id' => 1), //array associativo dentro de outro array
        array('id' => 3, 'email' => 'jose@teste.com.br', 'senha' => '123456', 'perfil_id' => 2), //array associativo dentro de outro array
        array('id' => 4, 'email' => 'maria@teste.com.br', 'senha' => '123456', 'perfil_id' => 2), //array associativo dentro de outro array
        
        //isso é apenas um teste, depois não vou utilizar mais esse method
    ];


    foreach($usuarios_app as $user) { //forech percorre cada elemento do array $usuarios_app, e 'as' nos dar acesso a cada um dos valores do array $user(é uma variavel criada pelo foreach para controle apenas) de forma individual

        /*
        echo 'Usuário app: ' . $user['email'] . ' / ' . $user['senha']; //esse é o usuario já salvo criado pelo bancos de dados
        echo '<br/>';
        echo 'Usuário form: ' . $_POST['email'] . ' / ' . $_POST['senha']; //esse usuario é aquele que está tentando acessar pelo HTML
        */

        if($user['email'] == $_POST['email'] && $user['senha'] == $_POST['senha']) { //verificando se o email que está sendo requerido via html é o mesmo do banco de dados 
            $usuario_autenticado = true; //recuperando a variavel usuario_autenticado e verificando se é verdadeiro
            $usuario_id = $user['id']; //recuperando a variavel usuario_id e colocando o 'id' no user
            $usuario_perfil_id = $user['perfil_id']; //recuperando a varivel usu... e colocando o perfil_id no user
        }
    }

    if($usuario_autenticado) { //if/else para verificar a autenticação do user
        echo 'Usuário autenticado';
        $_SESSION['autenticado'] = 'SIM'; //criando nova sessão a variavel autenticado com a valor SIM
        $_SESSION['id'] = $usuario_id; //criando nova sessão com a variavel id e colocando o usuario_id nela
        $_SESSION['perfil_id'] = $usuario_perfil_id; //nova sessão para variavel perfil_id, para o id do user ficar visivel na aplicação globa
        header('Location: home.php'); //se a autenticação for positiva o header(), nos levara para essa pagina : home.php
    }else {
        $_SESSION['autenticado'] = 'NÃO'; //caso não seja autenticado iria aparecer a mesma sessão porem com o valor da variavel de NÃO
        header('Location: index.php?login=erro'); //caso der erro na autenticação ira carregar a pagina index.php e login erro
    }

    /*
    print_r($_POST); //para imprimir o $_POST, porque esse method são array
    echo '<br/>';

    echo $_POST['email'];
    echo '<br/>';

    echo $_POST['senha'];
    */
?>