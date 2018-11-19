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
            'api_key_cityBike' => 'eca092226d4b38574f27fbb08dd30c2882eb12f7',
            'api_key_weather' => '15b073f5f4a04bdf90e4ad74941d0cad',
        ];
    }

}