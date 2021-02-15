<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";
    include("bd.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    session_start();
    $acceder = new BaseDatos();

    $identificado = false;
    $usuario = array();

    if(isset($_SESSION['identificado'])) {
       $identificado = true;
       $usuario = $acceder->cargarUsuario($_SESSION['nameUsuario']);
    }

    $eventos = $acceder->getAllEventos();

    echo $twig->render('principal.html', ['identificado' => $identificado, 'usuario' => $usuario, 'eventos' => $eventos]);

    
?>