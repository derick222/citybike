<?php

/*
 * Description: Open Weather Map Api from OpenWeatherMap website 
 * @author Rick Espina
 * date: 18/11/2018
 */
namespace App\Service;

class OpenWeatherMap
{
    protected $base = 'https://api.openweathermap.org/data/2.5/'; // /< REST endpoint
    protected $api_key; // /< API key that you created in the JCDecaux website member area
    protected $curlOpts = []; // /< User defined curl coptions
    private $httpDebug = false; // /< If you enable this, curl will output debugging information



    /**
     * Constructor for the class,
     * send as many argument as you want.
     * 1 arguments - api key 
     */
    public function __construct () {
        
        $param = func_get_args();

        switch (func_num_args()) {
            case 1:
                $this->api_key = $param[0];
                break;
            default:
                break;
        }
        // isset the api key
        $this->api_key = $this->api_key ?? NULL;
    }


    /**
     * Get all detail by geographic coordinates
     * @param $lat int latitue coordinates
     * @param $lng in longitude coordinates
     * @return array containing the response
     */
    public function latLon(int $lat, int $lng){
        return $this->httpRequest("weather", "GET", [
            "lat" => $lat,
            "lon" => $lng,
        ]);
    }


    /**
     * httpRequest curl wrapper for all http api requests.
     * @param $url string the endpoint to query, typically includes query string
     * @param $method string
     * @param $params array addtional options for the request
     * @return array containing the response
     * @throws \Exception
     */
    private function httpRequest(string $url, string $method = "GET", array $params = []){

        if (empty($this->api_key)) {
            throw new \Exception("Request error: API Key not set!");
        } else {
            $pairs = array_merge(['appid'=>$this->api_key], $params);
        }
        
        if (function_exists('curl_init') === false) {
            throw new \Exception("Sorry cURL is not installed!");
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_VERBOSE, $this->httpDebug);
        $query = http_build_query($pairs, '', '&');
        curl_setopt($curl, CURLOPT_URL, $this->base . $url . '?'.  $query);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($output, true);
        return $json;
    }
}