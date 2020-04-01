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
        
    
        $res = $this->acceder->query("SELECT nombre, lugar, fecha, historia, imagen, enlace FROM eventos WHERE id=" . $idEv);
    
        $evento = array('nombre' => 'Nombre por defecto', 'lugar' => 'Lugar por defecto', 'fecha' => date("Y-m-d"), 'historia' => 'Descripcion generica', 'imagen' => 'images/logo.png', 'enlace' => 'https://play.na.leagueoflegends.com/en_US');
        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();

            $evento = array('nombre' => $row['nombre'], 'lugar' => $row['lugar'], 'fecha' => $row['fecha'], 'historia' => $row['historia'], 'imagen' => $row['imagen'], 'enlace' => $row['enlace']);

        }
        return $evento;
    }

    function getComentario($idEv){
        $res = $this->acceder->query("SELECT autor, fecha, texto FROM comentarios WHERE idEv=" . $idEv);

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



    
    }
?>