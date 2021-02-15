<?php

    require_once "/usr/local/lib/php/vendor/autoload.php";
    include ("bd.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    session_start();
    $identificado = false;
    $acceder = new BaseDatos();
    $usuario = array();


    if(isset($_SESSION['identificado'])){
        $identificado = true;
        $usuario = $acceder->cargarUsuario($_SESSION['nameUsuario']);
    }

    if (isset($_GET['ev']) && ctype_digit($_GET['ev'])) {
        $idEv = $_GET['ev'];
    } else {
        $idEv = -1;
    }

    $evento = $acceder->getEvento($idEv);

    if($usuario['moderador'] == 1 || $usuario['gestor'] || $usuario['superusuario'] == 1 ){
        $comentario = $acceder->obtenerTodosComentarios();
    }
    //var_dump($evento);

    echo $twig->render('moderacion.html', ['identificado' => $identificado, 'usuario' => $usuario, 'comentario' => $comentario, 'evento' => $evento]);



?>