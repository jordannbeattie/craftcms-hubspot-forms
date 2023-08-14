<?php

namespace jordanbeattie\hubspotforms\services;

use craft\base\Component;
use GuzzleHttp\Client;
use jordanbeattie\hubspotforms\HubspotForms;

class HubspotFormsService extends Component
{

    /*
     * Get Forms
     */
    public function getForms()
    {

        /* Send request to HubSpot */
        $request = $this->sendRequest( "https://api.hubapi.com/marketing/v3/forms/" );
        
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

        /* Sort alphabetically */
        ksort( $forms );

        /* Return forms array */
        return $forms;
        
    }

    /*
     * Get Portal ID
     */
    public function getPortalId( $token = null )
    {
        
        /* Send request */
        $request = $this->sendRequest( "https://api.hubapi.com/integrations/v1/me", $token );

        /* Handle invalid response */
        if( is_null( $request ) ){ return null; }

        /* Get portal id from response */
        return json_decode( $request->getBody()->getContents() )->portalId ?? null;

    }

    /*
     * Send request
     * Interact with the HubSpot API
     */
    private function sendRequest( $url, $token = null )
    {
        try
        {

            /* Get HubSpot token */
            if( is_null( $token ) )
            {
                $token = HubspotForms::getInstance()->settings->getHsToken();
            }
            
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