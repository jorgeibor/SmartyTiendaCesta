<?php
class producto {
    protected $codigo;
    protected $nombre;
    protected $nombre_corto;
    protected $descripcion;
    protected $PVP;
    protected $familia;
    protected $cantidad;
 
    public function getcodigo() {return $this->codigo; }
    public function getnombre() {return $this->nombre; }
    public function getnombrecorto() {return $this->nombre_corto; }
    public function getdescripcion() {return $this->descripcion; }
    public function getPVP() {return $this->PVP; }
    public function getfamilia() {return $this->familia; }
    public function getcantidad() {return $this->cantidad; }
    public function set($name, $value) {

        echo "Set:$name to $value";
        $this->$name = $value;
    }
 
    public function __construct($row) {
        $this->codigo = $row['cod'];
        $this->nombre = $row['nombre'];
        $this->nombre_corto = $row['nombre_corto'];
        $this->descripcion = $row['descripcion'];
        $this->PVP = $row['PVP'];
        $this->familia = $row['familia'];
        $this->cantidad = 1;
    }
}
?>
