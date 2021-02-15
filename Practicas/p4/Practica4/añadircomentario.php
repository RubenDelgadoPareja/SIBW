<?php
    include("bd.php");

    session_start();
    $acceder = new BaseDatos();
    $identificado = false;

    if(isset($_SESSION['identificado'])){
        $identificado = true;
    }
    if($identificado){
        $usuario = $acceder->cargarUsuario($_SESSION['nameUsuario']);
    }

    if(isset($_GET['ev']) && ctype_digit($_GET['ev'])){
        $idEv = $_GET['ev'];
    }
    else {
        $idEv = -1;
    }

    if($identificado && $_SERVER['REQUEST_METHOD'] === 'POST' && $idEv != -1){
        if(isset($_POST['comentario'])){
            $comentario = htmlspecialchars($_POST['comentario']);
            $acceder->newComentario($idEv, $_SESSION['nameUsuario'], $comentario);

            header("Location: evento.php?ev=" . $idEv);

            exit();
        }
    }

    

?>