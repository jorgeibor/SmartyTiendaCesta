<?php


//Incluimos la libreria de smarty
require_once('libs/Smarty.class.php');

//Creamos un objeto de la clase smarty
$smarty = new Smarty;

//Inicializamos los atributos básicos.
$smarty->template_dir = './templates/';
$smarty->compile_dir = './templates_c/';
$smarty->config_dir = './configs/';
$smarty->cache_dir = './cache/';


require_once "class/claseBD.php";
$conexion = claseBD::connectPDO($dns,$username,$password,$options);
session_start();

//Funciones

function mostrarCesta($smarty, $aniadir, $prod, $nombreProd, $precioProd){
    if(isset($aniadir) || isset($_SESSION["cesta"])){
        if(isset($_SESSION["cesta"][$prod])){
            foreach ( $_SESSION["cesta"] as $id => $val ) {
                if($nombreProd == $val['nombre']){
                    $cantidad = $val['cantidad'];
                }
            }
            $producto['nombre'] = $nombreProd;
            $producto['cantidad'] = $cantidad+1;
            $producto['precio']= ($precioProd) * ($producto['cantidad']);
            $_SESSION['cesta'][$prod] = $producto;
            
        }else{
            if($prod != "" || $prod != null){
                $producto['nombre'] = $nombreProd;
                $producto['cantidad'] = 1;
                $producto['precio'] = $precioProd;
                $_SESSION['cesta'][$prod] = $producto;
            }
        }
    }else{
        $cestaVacia = "<hr><p>Cesta vacía</p>";
        $smarty->assign("cestaVacia", $cestaVacia);
    }    
}

//Generamos el código php
if(!isset($_SESSION['usuario'])){
    die("<h1>Esta página esta restringida. Necesitas estar <a href='login.php'>Registrado</a> para entrar.</h1>");
}else{
    $smarty->assign("usuario", $_SESSION['usuario']);
    $smarty->assign("cesta", $_SESSION['cesta']);
    $vaciarCesta = filter_input(INPUT_POST, 'vaciar');
    $descripcion = filter_input(INPUT_POST, 'masInfo');
    $logout = filter_input(INPUT_POST, 'logout');
    $aniadir = filter_input(INPUT_POST, 'aniadir');
    $codProducto = filter_input(INPUT_POST, 'producto');
    
    $listaProductos = claseBD::obtieneProductos($conexion);
    $producto = claseBD::obtieneProducto($conexion, $codProducto);
    //var_dump($productos);

    if(isset($logout)){
        unset($_SESSION['cesta']);
        unset($_SESSION['usuario']);
        header("Location: login.php");
    }
    
    if(isset($vaciarCesta)){
        unset($_SESSION['cesta']);
        header("Location: productos.php");
    }  
    
    if(isset($descripcion)){
        header("Location: descripcion.php?producto=".$codProducto);
    }
    
    $smarty->assign('listaProductos', $listaProductos);
    
    
    //mostrarProductos($conexion, $smarty);
    //mostrarCesta($smarty, $aniadir, $producto);
    
    $smarty->assign("productosCesta", claseBD::obtieneCesta($smarty, $conexion, $aniadir, $producto));
    
    //$cesta = $_SESSION['cesta'];
    //var_dump($cesta);
    $smarty->display('productos.tpl');
}


?>

