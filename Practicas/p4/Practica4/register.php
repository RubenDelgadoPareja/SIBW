<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";
    include("bd.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $acceder = new BaseDatos();
        $nombre = $_POST['nombre'];
        $contraseña = $_POST['contraseña']; 

        if($acceder->register($nombre, $contraseña) === 1) {
            header("refresh:3;url=login.php");
            echo 'Registrado con éxito. Por favor, iniciar sesión';
        }
        else{
            header("Location:register.php");
        }
        exit();
    }

    echo $twig->render('register.html', []);
?>