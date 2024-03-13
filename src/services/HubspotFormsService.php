<?php

namespace jordanbeattie\hubspotforms\services;

use craft\base\Component;
use Craft;
use GuzzleHttp\Client;
use jordanbeattie\hubspotforms\HubspotForms;

class HubspotFormsService extends Component
{

    /*
     * Get Forms
     */
    public function getForms()
    {

        /* Create forms array */
        $forms = [];

        /* Set API URL */
        $link = "https://api.hubapi.com/marketing/v3/forms";

        while( true )
        {

            /* Send request to HubSpot */
            $request = $this->sendRequest( $link );
            
            /* Exit on failure */
            if( is_null( $request ) || !$request->getStatusCode() == "200" )
            {
                break;
            }

            /* Decode response */
            $response = json_decode( $request->getBody()->getContents() );

            /* Loop forms from API request */
            foreach( $response->results as $form )
            {
                /* Add form to forms array */
                /* Key = name, Value = ID */
                $forms[ $form->name ] = $form->id;
            }

            /* Check if there are more pages */
            if( !property_exists( $response, 'paging' ) || !isset( $response->paging->next->link ) )
            {
                break;
            }
            
            /* Set API URL */
            $link = $response->paging->next->link;
            
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
     * Check settings
     */
    public function hasValidSettings(): bool
    {

        /* Get settings */
        $settings = Craft::$app->plugins->getPlugin('hubspot-forms')->getSettings();

        /* Return token & portalId set */
        return ( $settings->getHsToken() && $settings->getHsPortalId() );

    }

    /*
     * Forms URL
     */
    public function getFormsUrl()
    {
        return "https://app.hubspot.com/forms/{$this->getPortalId()}";
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