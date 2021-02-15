<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";
    include("bd.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    session_start();
    $acceder = new BaseDatos();
    $comentario = array();

    

    if(isset($_SESSION['nameUsuario'])) {
        $usuario = $acceder->cargarUsuario($_SESSION['nameUsuario']);
    }
    //var_dump($usuario);

    $identificado = false;

    if(isset($_SESSION['identificado'])) {
        $identificado = true;
    }

    if(isset($_GET['ev']) && ctype_digit($_GET['ev'])) {
        $idEven = $_GET['ev'];
        $evento = $acceder->getEvento($idEven);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if($usuario['gestor'] == 1 || $usuario['superusuario'] == 1) {
            if(isset($_POST['name']) && is_string($_POST['name']) && !empty($_POST['name'])) {
                $acceder->modificarNombreEvento($idEven, htmlspecialchars($_POST['name']));
            }

            if(isset($_POST['lugar']) && is_string($_POST['lugar']) && !empty($_POST['lugar'])) {
                $acceder->modificarLugarEvento($idEven, htmlspecialchars($_POST['lugar']));
            }

            if(isset($_POST['date']) && !empty($_POST['date'])) {
                $fecha = preg_replace("([^0-9/])", "", $_POST['fecha']);
                $acceder->modificarFechaEvento($idEven, $fecha);
            }

            if(isset($_POST['etiqueta']) && !empty($_POST['etiqueta'])) {
                $acceder->addEtiquetas($idEven, htmlspecialchars($_POST['etiquetas']));
            }

            if(isset($_POST['enlace']) && !empty($_POST['enlace'])) {
                $acceder->modificarEnlaceEvento($idEven, htmlspecialchars($_POST['enlace']));
            }

            

            if(isset($_FILES['imagen']) && !empty($_FILES['imagen'])){
                $file_name = $_FILES['imagen']['name'];
                $file_size = $_FILES['imagen']['size'];
                $file_tmp = $_FILES['imagen']['tmp_name'];
                $file_type = $_FILES['imagen']['type'];
                $file_ext = strtolower(end(explode('.',$_FILES['imagen']['name'])));
                
                $extensions= array("jpeg","jpg","png");
                
                if(in_array($file_ext,$extensions) === true && $file_size < 2097152){
                    move_uploaded_file($file_tmp, "images/" . $file_name);

                    $acceder->modificarImagenEvento($idEven, "images/".$file_name);
                }
            }
            //var_dump($file_name);

            if(isset($_POST['historia']) && is_string($_POST['historia']) && !empty($_POST['historia'])) {
                $acceder->modificarHistoriaEvento($idEven, htmlspecialchars($_POST['historia']));
            }
            

            header("Location: gestioneventos.php");
        }
        exit();
      }
      

    echo $twig->render('modEvento.html', ['evento' => $evento, 'identificado' => $identificado, 'usuario' => $usuario]);
?>