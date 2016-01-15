<?php

class cesta {
    protected $productos = array();
    
    
    // Introduce un nuevo artículo en la cesta de la compra
    public function nuevo_articulo($con, $codigo) {
        
        $producto = claseBD::obtieneProducto($con, $codigo);
        $this->productos[] = $producto;
    }
 
    // Obtiene los artículos en la cesta
    public function get_productos() { return $this->productos; }
 
    // Devuelve true si la cesta está vacía
    public function vacia() {
        if(count($this->productos) == 0)
           return true;
        return false;
       // alternativa  return !(count($this->productos));
    }
 
   // Guarda la cesta de la compra en uns variable de sesión
    public function guarda_cesta() { 
        $_SESSION['cesta'] = $this;
    }
 
    // Recupera la cesta de la compra almacenada en la variable de sesión . Si no existía crea una variable de sesión con una instancia del objeto Cesta
    public static function carga_cesta() {
        if (!isset($_SESSION['cesta'])) {
            return new cesta();
        }else{
           return $_SESSION['cesta'];
        }
    }
}
