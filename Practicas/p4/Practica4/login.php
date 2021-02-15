<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";
    include("bd.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $acceder = new BaseDatos();
        $nombre = $_POST['nombre'];
        $contraseña = $_POST['contraseña']; 

        if($acceder->checkLogin($nombre,$contraseña)) {
            session_start();

            $_SESSION['nameUsuario'] = $nombre;
            $_SESSION['identificado'] = true;

            header("refresh:3;url=index.php");
            echo 'Identificado. Redirigiéndote a la página principal...';
        }
        else{
            header("Location: login.php");
        }
        exit();
    }

    echo $twig->render('login.html', []);
?>