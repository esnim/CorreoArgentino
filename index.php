<?php
require 'CorreoArgentino/API.php';
require 'CorreoArgentino/CA.php';
require 'CorreoArgentino/HTTP.php';

// Datos de conexión:
$usuario = '';
$clave = '';

// Datos del vendedor:
$numero_cuenta = '';
$codigo_postal_origen = '7600';
$tipo_producto = \CorreoArgentino\CA::TIPO_PRODUCTO_CORREO_CLASICO;

// Datos del destino:
$tipo_envio = \CorreoArgentino\CA::TIPO_ENVIO_DOMICILIO;
$codigo_postal_destino = '7620';

// Datos del paquete:
$altura = 25;
$ancho = 50;
$largo = 75;
$peso = 1000;

try {
    
    $ca = new \CorreoArgentino\CA($usuario, $clave);

    $ca->getApi()->setTestMode(true);
    
    $ca->setNumeroCuenta($numero_cuenta);
    $ca->setCodigoPostalOrigen($codigo_postal_origen);
    $ca->setTipoProducto($tipo_producto); // opcional

    $costo = $ca->getCosto($tipo_envio, $codigo_postal_destino, $altura, $ancho, $largo, $peso);

    print '<pre>';
    print_r($costo);
    print '</pre>';
    
    
    // Respuesta definiendo un tipo de producto:
    /*
    Array
    (
        [deliveredType] => D
        [productType] => CP
        [productName] => Correo Argentino Clasico
        [price] => 21695
        [deliveryTimeMin] => 2
        [deliveryTimeMax] => 5
    )
    */    
    
    // Respuesta sin definir un tipo de producto:
    /*
    Array
    (
        [0] => Array
            (
                [deliveredType] => D
                [productType] => CP
                [productName] => Correo Argentino Clasico
                [price] => 21695
                [deliveryTimeMin] => 2
                [deliveryTimeMax] => 5
            )

        [1] => Array
            (
                [deliveredType] => D
                [productType] => EP
                [productName] => Correo Argentino Expreso
                [price] => 31303
                [deliveryTimeMin] => 1
                [deliveryTimeMax] => 3
            )

    )
    */ 
    
} catch (Exception $ex) {

    print '<pre>';
    print_r($ex);
    print '</pre>';    
    
}
