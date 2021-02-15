<?php

    require_once "/usr/local/lib/php/vendor/autoload.php";
    include ("bd.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    session_start();
    $identificado = false;
    $acceder = new BaseDatos();
    $comentario = array();

    if(isset($_SESSION['nameUsuario'])) {
        $usuario = $acceder->cargarUsuario($_SESSION['nameUsuario']);
    }

   

    if(isset($_SESSION['identificado'])){
        $identificado = true;
    }

    if(isset($_GET['comentario']) && ctype_digit($_GET['comentario'])){
        if(($usuario['moderador'] == 1 || $usuario['gestor'] == 1 || $usuario['superusuario'] == 1) && $identificado){
            $idComentario = $_GET['comentario'];
            $comentario = $acceder->obtenerComentario($idComentario);
            $evento = $acceder->getEvento($idEv);
        }
    }
    //var_dump($idComentario);
    
    if(isset($_GET['ev']) && ctype_digit($_GET['ev'])){
        $idEv = $_GET['ev'];
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(($usuario['moderador'] == 1 || $usuario['gestor'] == 1 || $usuario['superusuario'] == 1) && $identificado){
            if(isset($_POST['comentario']) && !empty($_POST['comentario'])){
                $acceder->modificarComentario($idComentario, htmlspecialchars($_POST['comentario']));
                header("Location:evento.php?ev=" . $idEv);
            }
        }
        exit();
    }
    //var_dump($acceder);
    $censura = $acceder->getCensura();
    echo $twig->render('modComentario.html', ['evento' => $evento, 'comentario' => $comentario, 'usuario' => $usuario, 'identificado' => $identificado, 'censura' => $censura]);
?>
