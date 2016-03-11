<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApiBundle\Services;

use Buzz\Browser;
use Buzz\Client\Curl;

class ApiService
{
    private $url = 'http://apilayer.net/api/';
    
    private $key;
    
    private $currencies = "ZAR,USD,GBP,EUR,KES";
    
    private $source = "ZAR";

    public function __construct($key)
    {
        $this->key = $key;
        $this->browser = new Browser(new Curl());
    }

    /**
     * @param string $format
     *
     * @return ApiResponse
     * @throws \InvalidArgumentException
     */
    public function query($name, $parameters=array())
    {
        //build url
        $baseUrl = $this->url.$name;
        
        $queryParts = array();
        
        foreach ($parameters as $key => $value) {
            $queryParts[] = $key.'='.rawurlencode($value);
        }
        
        $url =  $baseUrl.'?access_key='.$this->key.'&'.implode('&', $queryParts);
        //query url
        $response = $this->browser->get($url);
        
        return $response;
    }
}
