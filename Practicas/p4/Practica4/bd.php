<?php
    class BaseDatos{
    private static $acceder;

    public function __construct(){
        $this->acceder = new mysqli("mysql", "rubendelgado", "usuario", "SIBW");
        if ($acceder->connect_errno) {
            echo ("Fallo al conectar: " . $mysqli->connect_error);
        }
    }

    function getEvento($idEv){
        
    
        $res = $this->acceder->query("SELECT id, nombre, lugar, fecha, historia, imagen, enlace FROM eventos WHERE id=" . $idEv);
    
        $evento = array('nombre' => 'Nombre por defecto', 'lugar' => 'Lugar por defecto', 'fecha' => date("Y-m-d"), 'historia' => 'Descripcion generica', 'imagen' => 'images/logo.png', 'enlace' => 'https://play.na.leagueoflegends.com/en_US');
        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();

            $evento = array('id' => $row['id'], 'nombre' => $row['nombre'], 'lugar' => $row['lugar'], 'fecha' => $row['fecha'], 'historia' => $row['historia'], 'imagen' => $row['imagen'], 'enlace' => $row['enlace']);

        }
        return $evento;
    }

    function getComentario($idEv){
        $res = $this->acceder->query("SELECT id, autor, fecha, texto, editado FROM comentarios WHERE idEv=" . $idEv);

        //$comentario = array('autor' => 'Rubén Delgado Pareja', 'fecha' => date("Y-m-d"), 'texto' => 'Comentario genérico');
        $comentario = array();

        while($row = mysqli_fetch_row($res)){
            $comentario[] = $row;
        }

        return $comentario;

    }

    function getCensura(){
        $res = $this->acceder->query("SELECT palabra FROM censura");

        $censura = array();

        if($res->num_rows > 0) {
            while($row = $res->fetch_assoc()) {
                array_push($censura, $row['palabra']);
            }
        }
        return $censura;
    }

    function getImagenes(){
        $res = $this->acceder->query("SELECT imagen FROM galeria");

        $imagen = array();

        if($res->num_rows > 0) {
            while($row = $res->fetch_assoc()) {
                array_push($imagen, $row['imagen']);
            }
        }
        return $imagen;
    }

    function checkLogin($nombre, $contraseña){
        $res = $this->acceder->query("SELECT * from usuarios WHERE nombre='" . $nombre . "'");

        if($res->num_rows > 0) {
            $row = $res->fetch_assoc();
        }
        
        if(password_verify($contraseña, $row['contrasenia'])) {
            return true;
        }

        return false;
    }

    function register($nombre, $contraseña){
        $res = $this->acceder->query("SELECT * from usuarios WHERE nombre='" . $nombre . "'");

        if($res->num_rows > 0){
            return 0;
        }
        else if(is_string($nombre) && is_string($contraseña)){
            $contraseña = password_hash($contraseña, PASSWORD_DEFAULT);
            $moderador = 0;
            $gestor = 0;
            $superusuario = 0;
            $register = $this->acceder->query("INSERT INTO usuarios (nombre, contrasenia, moderador, gestor, superusuario) VALUES ('$nombre', '$contraseña', '$moderador', '$gestor', '$superusuario')");
            return 1;
        }
    }

    function cargarUsuario($nombre){
        $res = $this->acceder->query("SELECT id, nombre, moderador, gestor, superusuario from usuarios WHERE nombre='$nombre'");

        $usuario = array();

        if($res->num_rows > 0){
            $row = $res->fetch_assoc();
            $usuario = array('id' => $row['id'],'nombre' => $row['nombre'], 'moderador' => $row['moderador'], 'gestor' => $row['gestor'], 'superusuario' => $row['superusuario']);

        }
        return $usuario;
    }

    function newEvento($nombre, $lugar, $fecha, $historia, $imagen, $enlace){
        $insertar = $this->acceder->query("INSERT INTO eventos (nombre, lugar, fecha, historia, imagen, enlace) VALUES ('$nombre', '$lugar', '$fecha', '$historia', '$imagen', '$enlace')");
        
        $res = $this->acceder->query("SELECT id FROM eventos WHERE nombre='$nombre'");
        
        if ($res->num_rows > 0) {

            $row = $res->fetch_assoc();
            $idEvento = array('id' => $row['id']);
            if($idEvento['id'] > 0) {
                return $idEvento['id'];
            }
        }
        //var_dump($insertar);

        return -1;
    }

    function getAllEventos(){
        $res = $this->acceder->query("SELECT id, nombre, lugar, fecha, historia, imagen, enlace from eventos");

        $eventos = array();
        while($row = mysqli_fetch_row($res)) {
            $eventos[] = $row;
        }

        return $eventos;
    }

    function borrarEvento($idEv) {
        $res = $this->acceder->query("DELETE FROM eventos WHERE id='$idEv'");
    }

    function getAllUsuarios(){
        $res = $this->acceder->query("SELECT id, nombre, contrasenia, moderador, gestor, superusuario from usuarios");

        $usuarios = array();

        while($row = mysqli_fetch_row($res)){
            $usuarios[] = $row;
        }

        return $usuarios;
    }

    function cambiarPermisos($idUser, $opcion, $rol) {
        $cambio = $opcion ? 1 : 0;
        switch($rol) {
            case 'moderador':
                if($cambio == 1){
                    $this->acceder->query("UPDATE usuarios SET moderador='$cambio' WHERE id='$idUser'");
                }
                else {
                    $this->acceder->query("UPDATE usuarios SET moderador='$cambio', gestor='$cambio', superusuario='$cambio' WHERE id='$idUser'");
                }
            break;
            case 'gestor':
                if($cambio == 1){
                    $this->acceder->query("UPDATE usuarios SET gestor='$cambio' WHERE id='$idUser'");
                }
                else{
                    $this->acceder->query("UPDATE usuarios SET gestor='$cambio', superusuario='$cambio' WHERE id='$idUser'");
                }
            break;
            case 'superusuario':
                if($cambio == 1){
                    $res = $this->acceder->query("UPDATE usuarios SET superusuario='$cambio' WHERE id='$idUser'"); 
                }
                else{
                    $res = $this->acceder->query("UPDATE usuarios SET superusuario='$cambio' WHERE id='$idUser'");
                }
            break;
        }
    }
    function newComentario($idEv,$autor,$text){
        $fecha = date("Y-m-d");
        $this->acceder->query("INSERT INTO comentarios (idEv, autor, fecha, texto, editado) VALUES ('$idEv', '$autor', '$fecha', '$texto', '0'");
    }
    function obtenerComentario($idComentario){
        $res = $this->acceder->query("SELECT id, idEv, autor, fecha, texto FROM comentarios where id='$idComentario'");

        if($res->num_rows > 0){
            $row = $res->fetch_assoc();
            $comentario = array('id' => $row['id'], 'idEv' => $row['idEv'], 'autor' => $row['autor'], 'fecha' => $row['fecha'], 'texto' => $row['texto']);
        }

        return $comentario;
    }

    function obtenerTodosComentarios(){
        $res = $this->acceder->query("SELECT id, idEv, autor, fecha, texto, editado FROM comentarios ");

        $comentarios = array();

        while($row = mysqli_fetch_row($res)) {
            $comentarios[] = $row;
        }
        return $comentarios;
    }

    function cambiarNombre($antiguo, $nuevo){
        $res = $this->acceder->query("UPDATE usuarios SET nombre='$nuevo' WHERE nombre='$antiguo'");
    }
    function  cambiarPass($nombre, $contraseña) {
        $nuevo = password_hash($contraseña, PASSWORD_DEFAULT);
        $res = $this->acceder->query("UPDATE usuarios SET contrasenia='$nuevo' WHERE nombre='$nombre'");
    }
    function modificarComentario($idComentario, $texto){
        $res = $this->acceder->query("UPDATE comentarios   SET texto='$texto' WHERE id='$idComentario'");
        $res = $this->acceder->query("UPDATE comentarios   SET editado='1' WHERE id='$idComentario'");
        //var_dump($idComentario, $texto);
    }
    function borrarComentario($idComentario) {
        $res = $this->acceder->query("DELETE FROM comentarios where id='$idComentario'");
    }

    function modificarNombreEvento($idEv, $nombre){
        $res = $this->acceder->query("UPDATE eventos SET nombre='$nombre' where id='$idEv'");

    }
    function modificarLugarEvento($idEv, $lugar){
        $res = $this->acceder->query("UPDATE eventos SET lugar='$lugar' where id='$idEv'");

    }
    function modificarFechaEvento($idEv, $fecha){
        $res = $this->acceder->query("UPDATE eventos SET fecha='$fecha' where id='$idEv'");

    }
    function modificarImagenEvento($idEv, $imagen){
        $res = $this->acceder->query("UPDATE eventos SET imagen='$imagen' where id='$idEv'");

    }
    function modificarHistoriaEvento($idEv, $historia){
        $res = $this->acceder->query("UPDATE eventos SET historia='$historia' where id='$idEv'");

    }
    function modificarEnlaceEvento($idEv, $enlace){
        $res = $this->acceder->query("UPDATE eventos SET enlace='$enlace' where id='$idEv'");

    }

    function addImagenGaleria($idEv, $imagen){
        $this->acceder->query("INSERT INTO galeria (idEvento, src) VALUES ('$idEv', '$imagen')");
    }
    
    function addEtiquetas($idEv, $etiquetas){
        if(is_array($etiquetas)) {
            foreach($etiquetas as $etiqueta) {
                $this->acceder->query("INSERT INTO etiquetas (idEv, nombre) VALUES ('$idEv', '$etiqueta')");
            }
        }
        else {
            $this->acceder->query("INSERT INTO etiquetas (idEv, nombre) VALUES ('$idEv', '$etiquetas')");
        }
    }
    function getEtiquetas($idEv){
        $res = $this->acceder->query("SELECT nombre FROM etiquetas WHERE idEv = '$idEv'");

        $etiquetas = array();

        if($res->num_rows > 0){
            while($row = $res->fetch_assoc()){
                array_push($etiquetas, mb_strtoupper($row['nombre']));
            }
        }
    }
    



}
?>