<?php

    require_once "/usr/local/lib/php/vendor/autoload.php";
    include ("bd.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    session_start();
    $identificado = false;
    $acceder = new BaseDatos();
    $eventos = array();

    if(isset($_SESSION['identificado'])){
        $identificado = true;
    }

    if($identificado){
        $usuario = $acceder->cargarUsuario($_SESSION['nameUsuario']);

    }

    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        if($usuario['gestor'] == 1 || $usuario['superusuario'] == 1) {
            if(isset($_GET['ev']) && ctype_digit($_GET['ev']) == true){
                $acceder->borrarEvento($_GET['ev']);

                if(isset($_GET['evento']) && $_GET['evento'] == true){
                    header("Location:index.php");
                } 
                else{
                    header("Location:gestioneventos.php");
                }

            }

        }
    }

    if($usuario['gestor'] == 1 || $usuario['superusuario'] == 1){
        $eventos = $acceder->getAllEventos();
    }
    //var_dump($identificado);

    echo $twig->render('gestionareventos.html', ['identificado' => $identificado, 'usuario' => $usuario, 'eventos' => $eventos]);
?>