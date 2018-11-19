<?php

//test/Service/OpenWeatherMapTest.php
namespace App\Tests\Service;

use App\Service\OpenWeatherMap;
use PHPUnit\Framework\TestCase;
use App\Service\SecretKeys;

class OpenWeatherMapTest extends TestCase
{
    public function testStationStatus()
    {

         // Accessing private method using Class ReflectionMethod
         $reflectionMethod = new \ReflectionMethod('App\Service\SecretKeys', 'keys');
         $reflectionMethod->setAccessible(true);
 
         foreach ($reflectionMethod->invoke(new SecretKeys(), null) as $key => $value) {
             if($key == 'api_key_weather'){
                 $this->api_key_weather = $value ?? NULL;
             }
         }

        $openWeatherMap = new OpenWeatherMap($this->api_key_weather);

        $result = $openWeatherMap->latLon(53.349562, -6.278198);
       
        // Check if function returning arrays
        $this->assertInternalType('array', $result);
    }
}