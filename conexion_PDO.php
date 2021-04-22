<?php 
   
 class Conexion extends PDO { 
   private $tipo_de_base = 'mysql';
   private $host = 'localhost;port=3306';
   private $nombre_de_base = '';
   private $usuario = '';
   private $contrasena = '';
   public function __construct() {
      //Sobreescribo el método constructor de la clase PDO.
      try{
         parent::__construct($this->tipo_de_base.':host='.$this->host.';dbname='.$this->nombre_de_base, $this->usuario, $this->contrasena, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"));
      }catch(PDOException $e){
         echo 'Ha surgido un error y no se puede conectar a la base de datos. Detalle: ' . $e->getMessage();
         exit;
      }
   } 
 }
 class Conexion_yourls extends PDO { 
      private $tipo_de_base = 'mysql';
      private $host = 'localhost;port=3306';
      private $nombre_de_base = '';
      private $usuario = '';
      private $contrasena = '';
      public function __construct() {
         //Sobreescribo el método constructor de la clase PDO.
         try{
            parent::__construct($this->tipo_de_base.':host='.$this->host.';dbname='.$this->nombre_de_base, $this->usuario, $this->contrasena, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"));
         }catch(PDOException $e){
            echo 'Ha surgido un error y no se puede conectar a la base de datos. Detalle: ' . $e->getMessage();
            exit;
         }
      } 
   }
?>