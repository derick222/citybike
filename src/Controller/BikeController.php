<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use App\Service\Jcdecaux;
use App\Service\OpenWeatherMap;
use App\Service\SecretKeys;
use Symfony\Component\HttpFoundation\JsonResponse;

class BikeController extends AbstractController
{
    /**
     * @Route("/bike", name="bike")
     */
    public function index()
    {
        // Accessing private method using Class ReflectionMethod
        $reflectionMethod = new \ReflectionMethod('App\Service\SecretKeys', 'keys');
        $reflectionMethod->setAccessible(true);

        foreach ($reflectionMethod->invoke(new SecretKeys(), null) as $key => $value) {
            if($key == 'api_key_cityBike'){
                $this->api_key_cityBike = $value ?? NULL;
            }
        }

        // create an object
        $this->cityBike = new Jcdecaux($this->api_key_cityBike);

        // Serialize output
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonCityBike = $serializer->serialize($this->cityBike->stationStatus('dublin'), 'json');    

        
        // unset the key
        unset($this->api_key_cityBike);

        return $this->render('bike/index.html.twig', [
            'controller_name' => 'BikeController',
            'aBikeCity' => $jsonCityBike,
        ]);
    }



    /**
     * @Route("/details/{lat}/{lng}", name="details")
     */
    public function mashup($lat, $lng){
        
        // Accessing private method using Class ReflectionMethod
        $reflectionMethod = new \ReflectionMethod('App\Service\SecretKeys', 'keys');
        $reflectionMethod->setAccessible(true);

        foreach ($reflectionMethod->invoke(new SecretKeys(), null) as $key => $value) {
            if($key == 'api_key_weather'){
                $this->api_key_weather = $value ?? NULL;
            }
        }

        // create new object
        $this->openWeatherMap = new OpenWeatherMap($this->api_key_weather);

        foreach ($this->openWeatherMap->latLon($lat, $lng) as $key => $value) {

                if($key == 'weather'){
                    $todaysWeather = $value[0]['description'];
                }
                if($key == 'main'){
                    // kelvin to celsius
                    $temp = round(($value['temp'] - 273.15),2);
                    $pressure = $value['pressure'];
                    $humidity = $value['humidity'];
                }
                if($key == 'wind'){
                    $wind = $value['speed'];
                }
  
                $weatherDetails = [
                    'todaysWeather' => $todaysWeather ?? null,
                    'temp' => $temp ?? null,
                    'pressure' => $pressure ?? null,
                    'humidity' => $humidity ?? null,
                    'wind' => $wind ?? null,
                ];
                
        }

        // unset the key
        unset($this->api_key_weather);

        // return in json format
        return new JsonResponse($weatherDetails);

    }

}
