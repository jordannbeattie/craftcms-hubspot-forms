<?php

namespace jordanbeattie\hubspotforms\variables;

use GuzzleHttp\Client;

class HubspotFormsVariable
{
    
    public function getForms()
    {

        /* Send request to HubSpot */
        $request = static::sendRequest( "https://api.hubapi.com/marketing/v3/forms/" );
        
        /* Exit on failure */
        if( is_null( $request ) || !$request->getStatusCode() == "200" )
        {
            return [];
        }

        /* Create forms array */
        $forms = [];

        /* Get forms from API request */
        $apiForms = json_decode( $request->getBody()->getContents() )->results;

        /* Loop forms from API request */
        foreach( $apiForms as $form )
        {
            /* Add form to forms array */
            /* Key = name, Value = ID */
            $forms[ $form->name ] = $form->id;
        }

        /* Return forms array */
        return $forms;
        
    }

    /*
     * Send API Request
     */
    private static function sendRequest( $url )
    {
        try
        {

            /* Get HubSpot token */
            $token = "";
            
            /* Create HTTP Client */
            $request = new Client();
    
            /* Send request with token */
            return $request->get( $url, [ 'headers' => [
                "Authorization" => "Bearer {$token}",
                "Accept" => "application/json",
            ]]);
            
        }
        catch( \Throwable $th )
        {

            /* Return null upon error */
            return null;

        }
    }

}
