<?php
namespace CorreoArgentino;

class CA
{
    // Tipos de envío
    const TIPO_ENVIO_DOMICILIO = 'D';
    const TIPO_ENVIO_SUCURSAL = 'S';
    
    // Tipos de servicio de correo
    const TIPO_PRODUCTO_CORREO_CLASICO = 'CP';
    const TIPO_PRODUCTO_CORREO_EXPRESO = 'EP';
    
    // Mensajes de error
    const ERROR_CONEXION = 'No se pudo conectar a Correo Argentino';
    const ERROR_COSTO = 'No se pudo obtener el costo';
    
    /**
     * 
     * @var \CorreoArgentino\API
     */
    private $api = null;
    
    /**
     * Usuario de conexión a la API.
     * @var string
     */
    private $usuario;
    
    /**
     * Contraseña de conexión a la API.
     * @var string
     */    
    private $clave;

    /**
     * Mensaje de error cuando hay una excepción al consultar la API.
     * @var string 
     */
    private $error;
    
    /**
     * Número de cuenta del vendedor.
     * @var string
     */
    private $numero_cuenta;
    
    /**
     * Código postal desde el que se hace el envío.
     * @var int
     */
    private $codigo_postal_origen;
    
    /**
     * Correo Clásico o Correo Expreso.
     * @var string
     */
    private $tipo_producto;

    
    
    public function __construct($usuario, $clave)
    {
        $this->usuario = $usuario;
        $this->clave = $clave;
    }
    
    /**
     * Obtener una instancia de la API.
     * @return \CorreoArgentino\API
     */
    public function getApi()
    {
        if ($this->api === null) {
            $this->api = new \CorreoArgentino\API();
        }
        
        return $this->api;
    }

    /**
     * Acceder a la API para obtener un token de acceso.
     * @return string
     * @throws \Exception
     */
    public function getToken()
    {
        $response = $this->getApi()->authorize($this->usuario, $this->clave);
            
        if (!isset($response['token']) || $response['token'] == '') {
            throw new \Exception(self::ERROR_CONEXION);
        }
        
        return $response['token'];
    }
    
    /**
     * Acceder a la API para obtener el costo de envío.
     * @param string $tipo_envio
     * @param int $codigo_postal_destino
     * @param int $altura
     * @param int $ancho
     * @param int $largo
     * @param int $peso
     * @return array
     * @throws \Exception
     */
    public function getCosto($tipo_envio, $codigo_postal_destino, $altura, $ancho, $largo, $peso)
    {   
        $costo = array();
        
        try {
            
            $api = $this->getApi();            
            $token = $this->getToken();
            
            $values = array(
                'numero_cuenta'         => $this->numero_cuenta,
                'codigo_postal_origen'  => $this->codigo_postal_origen,
                'codigo_postal_destino' => $codigo_postal_destino,
                'tipo_envio'            => $tipo_envio,
                'peso'                  => $peso,
                'altura'                => $altura,
                'ancho'                 => $ancho,
                'largo'                 => $largo,
            ); 
            
            $response = $api->rates($token, $values);
            
            if (!isset($response['rates'])) {
                throw new \Exception(self::ERROR_COSTO);
            }
            if (count($response['rates']) == 0) {
                throw new \Exception(self::ERROR_COSTO);
            }
            
            // si se definió un tipo de producto, devolver el que corresponda:
            if ($this->tipo_producto) {                
                foreach ($response['rates'] as $rate) {
                    if ($rate['productType'] == $this->tipo_producto) {
                        $costo = $rate;
                        break;
                    }
                }                
            } else {                
                $costo = $response['rates'];
            }
            
        } catch (\Exception $ex) {
            $this->error = $ex->getMessage();
        }
        
        return $costo;
        
    }    
    
    /**
     * Establecer el número de cuenta del vendedor.
     * @param string $numero_cuenta
     */
    public function setNumeroCuenta($numero_cuenta)
    {
        $this->numero_cuenta = $numero_cuenta;
    }

    /**
     * Establer el código postal desde el que se hace el envío.
     * @param string $codigo_postal_origen
     */
    public function setCodigoPostalOrigen($codigo_postal_origen)
    {
        $this->codigo_postal_origen = $codigo_postal_origen;
    }
    
    /**
     * Establecer si se quiere el costo de Correo Clásico o de Correo Expreso.
     * @param string $tipo_producto
     */
    public function setTipoProducto($tipo_producto)
    {
        $this->tipo_producto = $tipo_producto;
    }
    
    /**
     * Obtener el mensaje de error que se haya capturado al acceder a la API.
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }
}
