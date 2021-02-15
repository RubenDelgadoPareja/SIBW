<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";
    include ("bd.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    session_start();
    $acceder = new BaseDatos();
    $identificado = false;

    if(isset($_SESSION['identificado'])) {
        $identificado = true;
        $usuario = $acceder->cargarUsuario($_SESSION['nameUsuario']);
    }

    echo $twig->render('admin.html', ['usuario' => $usuario, 'identificado' => $identificado]);
    
?>