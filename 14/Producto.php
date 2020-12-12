<?php
class Producto {
    public $nombre;
    public $stock = 0;

    /**
     * [ [ Producto ingrediente, cantidad], ... ]
     */
    public $ingredientes = [];
    public $pack;

    function __construct($nombre, $ingredientes=null, $pack=null) {
        $this->nombre = $nombre;
        $this->ingredientes = $ingredientes;
        $this->pack = $pack;
    }
        
    /**
     * @param $objetivo int producción míninima objetivo
     * @return int sobrante de producción
     * 
     */
    public function produce($objetivo) {

        $rondas = ceil($objetivo/ $this->pack);
        $producido = $rondas * $this->pack;
        $sobrante = $producido - $objetivo;
        echo "Produciendo ". $rondas ." rondas de ". $this->nombre .", sobra ". $sobrante."\n";
        //echo "   Objetivo: ".$objetivo ." Producido: ". $producido ."\n";
        foreach ($this->ingredientes as $ingrediente) {
            $ingrediente[0]->gasta($rondas*$ingrediente[1]);
        }
        return $sobrante;

    }

    public function gasta($cantidad) {
        if ($this->stock >= $cantidad) {
            $this->stock -= $cantidad;
        } else {
            $this->stock = $this->produce($cantidad - $this->stock);
        }

    }

    public function getStock() {
        
        return $this->stock;

    }

    public function getNombre() {
        
        return $this->nombre;

    }

    public function __toString() {

        return $this->nombre;

    }

    public function setNombre($nombre) {
        
        $this->nombre = $nombre;

    }

    public function setIngredientes($ingredientes) {

        $this->ingredientes = $ingredientes;

    }

    public function setPack($pack) {

        $this->pack = $pack;

    }



}

class Ore extends Producto {

    public $gastado;

    function __construct() {
        parent::setNombre('ORE');
    }

    public function gasta($cantidad) {

        $this->gastado += $cantidad;

    }

    public function getGastado() {
        
        return $this->gastado;

    }
}

$almacen = ['ORE' => new Ore()] ;

/*$almacen['A'] = new Producto('A', [[$almacen['ORE'] , 10]] ,10) ;
$almacen['B'] = new Producto('B', [[$almacen['ORE'] , 1]] ,1) ;
$almacen['C'] = new Producto('C', [[$almacen['A'] , 7], [$almacen['B'] , 1]] ,1) ;
$almacen['D'] = new Producto('D', [[$almacen['A'] , 7], [$almacen['C'] , 1]] ,1) ;
$almacen['E'] = new Producto('E', [[$almacen['A'] , 7], [$almacen['D'] , 1]] ,1) ;
$almacen['FUEL'] = new Producto('FUEL', [[$almacen['A'] , 7], [$almacen['E'] , 1]] ,1) ;
*/

$txt_file = file_get_contents('input');
$rows = explode("\n", $txt_file);

foreach($rows as $row) {
    if ($row == '') {
        break;
    }
    list($ingredientes_txt, $producto_txt) = explode(' => ', $row);
    list($pack, $nombre) = explode(' ', $producto_txt);
    $ingredientes = explode(', ', $ingredientes_txt);
    $ingredientes_array = [];
    foreach($ingredientes as $ingrediente) {
        list($cantidad, $producto) = explode(' ', $ingrediente);
        if ( ! isset($almacen[$producto]) ) {
            $almacen[$producto] = new Producto($producto);
        }
        $ingredientes_array[] = array( $almacen[$producto] , $cantidad );
    }
    if ( ! isset($almacen[$nombre]) ) {
        $almacen[$nombre] = new Producto($nombre, $ingredientes_array, $pack);
    } else {
        $almacen[$nombre]->setIngredientes($ingredientes_array);
        $almacen[$nombre]->setPack($pack);
    }
    echo "Guardada receta de ". $nombre ."\n";
}
echo "Parseado fichero\n";

//$almacen['ORE']->gasta(5);
//$almacen['A']->gasta(1);
$almacen['FUEL']->gasta(1);

foreach ($almacen as $producto) {
    echo $producto->getNombre() ." \t-> ". $producto->getStock()."\n"; 
}

echo "ORE gastado: ". $almacen['ORE']->getGastado() ."\n" ;
