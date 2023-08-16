<?php

namespace jordanbeattie\hubspotforms\variables;

use Craft;
use GuzzleHttp\Client;
use jordanbeattie\hubspotforms\HubspotForms;
use Twig\Markup;

class HubspotFormsVariable
{

    /*
     * Render Form
     */
    public function render( $formId, $options = [] )
    {

        // Set the template path
        $templatePath = ( $options['loadOnEvent'] ?? false )
            ? 'hubspot-forms/forms/load-on-event'
            : 'hubspot-forms/forms/default';

        // Get the template content as a string
        $content = Craft::$app->getView()->renderTemplate( $templatePath, [
            'form'     => $formId,
            'portalId' => HubspotForms::getInstance()->settings->getHsPortalId(),
            'event'    => $options['loadOnEvent'] ?? null
        ]);

        // Render the template content as raw string
        return new Markup($content, Craft::$app->charset);
    }

}
