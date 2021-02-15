<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";
    include("bd.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    session_start();
    $acceder = new BaseDatos();
    $identificado = false;

    if(isset($_SESSION['identificado'])) {
        $identificado = true;
    }

    if($identificado) {
        $usuario = $acceder->cargarUsuario($_SESSION['nameUsuario']);
    }

    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        if($usuario['superusuario'] == 1) {
            if(isset($_GET['idUser']) && ctype_digit($_GET['idUser']) && isset($_GET['moderator']) && is_string($_GET['moderator']) && ($_GET['moderator'] == true || $_GET['moderator'] == false)) {
                $opcion = (htmlspecialchars($_GET['moderator']) == 'false') ? 0 : 1;
                $rol = 'moderador';
                $acceder->cambiarPermisos($_GET['idUser'], $opcion, $rol);
                header("Location: permisos.php");
            }

            if(isset($_GET['idUser']) && ctype_digit($_GET['idUser']) && isset($_GET['gestor']) && is_string($_GET['gestor']) && ($_GET['gestor'] == true || $_GET['gestor'] == false)) {
                $opcion = (htmlspecialchars($_GET['gestor']) == 'false') ? 0 : 1;
                $rol = 'gestor';
                $acceder->cambiarPermisos($_GET['idUser'], $opcion, $rol);
                header("Location: permisos.php");
            }

            if(isset($_GET['idUser']) && ctype_digit($_GET['idUser']) && isset($_GET['super']) && is_string($_GET['super']) && ($_GET['super'] == true || $_GET['super'] == false)) {
                $opcion = (htmlspecialchars($_GET['super']) == 'false') ? 0 : 1;
                $rol = 'superusuario';
                $acceder->cambiarPermisos($_GET['idUser'], $opcion, $rol);
                header("Location: permisos.php");
            }
        }
    }

    $usuarios = array();

    if($usuario['superusuario'] == 1) {
        $usuarios = $acceder->getAllUsuarios();
    }

    echo $twig->render('permisos.html', ['identificado' => $identificado, 'usuario' => $usuario, 'usuarios' => $usuarios]);
?>