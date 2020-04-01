<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";
    include("bd.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);


    if (isset($_GET['ev']) && ctype_digit($_GET['ev'])) {
        $idEv = $_GET['ev'];
    } else {
        $idEv = -1;
    }

    $acceder = new BaseDatos();

    if($idEv == 1) {
        $imagen = $acceder->getImagenes();
    }

    $evento = $acceder->getEvento($idEv);
    $comentario = $acceder->getComentario($idEv);
    $censura = $acceder->getCensura();
    //var_dump($imagen);

    echo $twig->render('evento.html', ['evento' => $evento, 'comentario' => $comentario, 'censura' => $censura, 'galeria' => $imagen]);
    
?>