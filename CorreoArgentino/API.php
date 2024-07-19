<?php
namespace CorreoArgentino;

class API
{
    /**
     * Servicio para hacer peticiones HTTP.
     * @var \CorreoArgentino\HTTP
     */
    private $http;
    
    private $test_mode = false;    
    
    /**
     * 2023-12-29:
     * La URL de testing no estaba devolviendo resultados, se usa directamente 
     * la de producción.
     * @var bool
     */
    private $url_testing    = 'https://apitest.correoargentino.com.ar/micorreo/v1';
    private $url_production = 'https://api.correoargentino.com.ar/micorreo/v1';

    public function __construct()
    {
        $this->http = new \CorreoArgentino\HTTP();
    }
    
    public function setTestMode($test_mode)
    {
        $this->test_mode = $test_mode;
    }
    
    /**
     * Armar la URL de la petición a la API.
     * @param string $endpoint
     * @return string
     */
    private function getUrl($endpoint)
    {
        $url = $this->test_mode ? $this->url_testing : $this->url_production;
        
        return $url . $endpoint;
    }    
    
    /**
     * Obtener un token de autenticación.
     * @param string $user
     * @param string $password
     * @return array|false
     */
    public function authorize($user, $password)
    {
        $url = $this->getUrl('/token');
        
        $data = array();
        
        $headers = array(
            'Content-Type: application/json',
        );
        
        $options = array(
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD  => "$user:$password",
        );
        
        $response = $this->http->post($url, json_encode($data), $headers, $options);
        
        return $response ? json_decode($response, true) : false;
    }

    /**
     * Obtener costos de envío.
     * @param string $token
     * @param array $values
     * @return array|false
     */
    public function rates($token, $values)
    {
        $url = $this->getUrl('/rates');
        
        $data = array(
            'customerId'            => $values['numero_cuenta'],
            'postalCodeOrigin'      => $values['codigo_postal_origen'],
            'postalCodeDestination' => $values['codigo_postal_destino'],
            'deliveredType'         => $values['tipo_envio'],
            'dimensions'            => array(
                'weight' => $values['peso'],
                'height' => $values['altura'],
                'width'  => $values['ancho'],
                'length' => $values['largo'],
            ),
        );
        
        $headers = array(
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        );
        
        $options = array();
        
        $response = $this->http->post($url, json_encode($data), $headers, $options);
        
        return $response ? json_decode($response, true) : false;
    }    
}
