<?php

/*
 * @author Rick Espina
 * date: 18/11/2018
 */
namespace App\Service;


class SecretKeys
{

    /**
     * Storing all private keys
     */
    private function keys(){
        return [
            'api_key_cityBike' => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
            'api_key_weather' => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
        ];
    }

}
