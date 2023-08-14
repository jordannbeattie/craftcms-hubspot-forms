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

        $templatePath = ( $options['loadOnScroll'] ?? false )
            ? 'hubspot-forms/forms/load-on-scroll'
            : 'hubspot-forms/forms/default';

        // Get the template content as a string
        $content = Craft::$app->getView()->renderTemplate( $templatePath, [
            'form'     => $formId,
            'portalId' => HubspotForms::getInstance()->settings->getHsPortalId(),
        ]);

        // Render the template content as raw string
        return new Markup($content, Craft::$app->charset);
    }

}
