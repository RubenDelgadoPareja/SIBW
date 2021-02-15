<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);
    include("bd.php");

    session_start();
    $identificado = false;
    $acceder = new BaseDatos();

    if(isset($_SESSION['identificado'])){
        $identificado = true;
    }

    if($identificado){
        $usuario = $acceder->cargarUsuario($_SESSION['nameUsuario']);
    }
    

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if(isset($_POST['nombre']) && isset($_POST['lugar']) && isset($_POST['fecha']) && isset($_POST['historia']) && isset($_POST['enlace'])){
        $nombre = htmlspecialchars($_POST['nombre']);
        $lugar = htmlspecialchars($_POST['lugar']);
        $fecha = preg_replace("([^0-9/])","",$_POST['fecha']);
        $historia = htmlspecialchars($_POST['historia']);
        $enlace = htmlspecialchars($_POST['enlace']); 

        
        if(isset($_FILES['imagen'])){
          //$errors= array();
          $file_name = $_FILES['imagen']['name'];
          $file_size = $_FILES['imagen']['size'];
          $file_tmp = $_FILES['imagen']['tmp_name'];
          $file_type = $_FILES['imagen']['type'];
          $file_ext = strtolower(end(explode('.',$_FILES['imagen']['name'])));

          $extensions= array("jpeg","jpg","png");

          if ($file_size < 2097152 && in_array($file_ext,$extensions) === true){
            //$errors[] = "Extensión no permitida, elige una imagen JPEG o PNG.";
            move_uploaded_file($file_tmp, 'images/' . $file_name);

            $idEv = $acceder->newEvento($nombre, $lugar, $fecha, $historia, "images/".$file_name, $enlace);


            if($idEv != -1){
              header("refresh:2;url=evento.php?ev=" . $idEv);
              echo 'Creando Evento';
            }
          }
      }
    }
    
    
    exit();
  }
  

    echo $twig->render('añadirevento.html', ['identificado' => $identificado, 'usuario' => $usuario]);




?>