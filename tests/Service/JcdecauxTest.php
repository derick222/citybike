<?php

//test/Service/JcdecauxTest.php
namespace App\Tests\Service;

use App\Service\Jcdecaux;
use PHPUnit\Framework\TestCase;
use App\Service\SecretKeys;

class JcdecauxTest extends TestCase
{
    public function testStationStatus()
    {

         // Accessing private method using Class ReflectionMethod
         $reflectionMethod = new \ReflectionMethod('App\Service\SecretKeys', 'keys');
         $reflectionMethod->setAccessible(true);
 
         foreach ($reflectionMethod->invoke(new SecretKeys(), null) as $key => $value) {
             if($key == 'api_key_cityBike'){
                 $this->api_key_cityBike = $value ?? NULL;
             }
         }

        $jcdecaux = new Jcdecaux($this->api_key_cityBike);

        $result = $jcdecaux->stationStatus('dublin');

       
        // Check if function returning arrays
        $this->assertInternalType('array', $result);
    }
}