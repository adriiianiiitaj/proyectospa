<?php

	require_once('datos.inc');

	class BaseDatos {
		private $coneccion;
		public static $instancia; 
		private $servidor = SERVIDOR;
		private $usuario = USUARIO;
		private $contrasena = CONTRASENA;
		private $base_datos = BASE_DATOS;
		private $_query;
		private $resultado = array();
		private $contador = 0;

		public static function obtenerInstancia() {
			if(!self::$instancia) { 
				self::$instancia = new BaseDatos();
			}
			return self::$instancia;
		}

		private function __construct() {
			$this -> conectar();
		}

		private function conectar() {
			$this->conexion = new mysqli($this->servidor, $this->usuario, $this->contrasena, $this->base_datos);
			if($this -> conexion -> connect_error) {
				die($this -> conexion -> connect_error);
			}
		}

		private function desconectar() {

		}

		private function clonar() {
			trigger_error('La clonación de este objeto no está permitida', E_USER_ERROR);
		}

		public function obtenerConexion() {
			return $this->conexion;
		}

		public function ejecutar($instruccion) {
			$this->_query = $this->conexion ->query($instruccion);

			if($this->_query){
				if(is_object($this->_query)) {
					while($fila = $this->_query -> fetch_assoc()) {
						$this -> resultado[] = $fila;
					}
					$this -> contador = $this->_query -> num_rows;
				}
			} else {
				die($this -> conexion -> error);
				return false;
			}
			/*
			if($this->_query = $this->conexion ->query($instruccion)) {
				while($fila = $this->_query -> fetch_object()) {
					$this -> resultado[] = $fila;
				}
				$this -> contador = $this->_query -> num_rows;
			}*/
			return $this;
		}

		public function obtenerResultado() {
			return $this -> resultado;
		}

		public function contar() {
			return $this -> contador;
		}

		public function regresaID(){
			return $this->conexion->insert_id;
		}

/*
		public function escapar($cadena) {
			return $this->conexion->real_escape_string($cadena);
		}
*/
	}
?>