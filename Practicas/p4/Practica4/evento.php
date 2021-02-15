<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";
    include("bd.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    session_start();
    $identificado = false;
    $acceder = new BaseDatos();

    if(isset($_SESSION['identificado'])) {
        $identificado = true;
    }

    if (isset($_GET['ev']) && ctype_digit($_GET['ev'])) {
        $idEv = $_GET['ev'];
    } else {
        $idEv = -1;
    }

    if($idEv == 1) {
        $imagen = $acceder->getImagenes();
    }

    if($identificado){
        $usuario = $acceder->cargarUsuario($_SESSION['nameUsuario']);
    }

    if($identificado && ($usuario['moderador'] == 1 || $usuario['gestor'] == 1 || $usuario['superusuario'] == 1) && $idEv != -1 ){
        if(isset($_GET['comentario']) &&  ctype_digit($_GET['comentario']) && isset($_GET['delete']) && $_GET['delete'] == true){
            $acceder->borrarComentario($_GET['comentario']);
            //var_dump($acceder);
            //echo 'PASA POR AQUI';
        }


    }
    //var_dump($usuario);
    $evento = $acceder->getEvento($idEv);
    $comentario = $acceder->getComentario($idEv);
    $censura = $acceder->getCensura();
    

    echo $twig->render('evento.html', ['evento' => $evento, 'comentario' => $comentario, 'censura' => $censura, 'galeria' => $imagen, 'usuario' => $usuario, 'identificado' => $identificado]);
    
?>