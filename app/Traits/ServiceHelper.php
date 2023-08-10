<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait ServiceHelper
{
    /**
     * Send a request to any service
     * @return string
     */
    public function performRequest($method, $requestUrl, $formParams = [], $headers = [])
    {
        $client = new Client([
            'base_uri' => $this->baseUri,
        ]);

        if (isset($this->secret)) {
            $headers['Authorization'] = $this->secret;
        }
        //return json_encode($this->baseUri.'kk'.$requestUrl);   
        $response = $client->request($method, $requestUrl, ['form_params' => $formParams, 'headers' => $headers]);

        //dd($response);
        return $response->getBody()->getContents();
        
    }
}