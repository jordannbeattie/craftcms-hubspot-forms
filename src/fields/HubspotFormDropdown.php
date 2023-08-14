<?php

namespace jordanbeattie\hubspotforms\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use jordanbeattie\hubspotforms\variables\HubspotFormsVariable;

class HubspotFormDropdown extends Field
{

    /*
     * Display Name
     */
    public static function displayName(): string
    {
        return Craft::t('hubspot-forms', 'Hubspot Form');
    }

    /*
     * Define input
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {

        /* Set existing value */
        if ($value === null || $value === '') {
            $value = '';
        }

        /* Create empty value */
        $options = [[
            'label' => '-- Select --', 
            'value' => ''
        ]];

        /* Create options */
        foreach( Craft::$app->getModule('hubspot-forms')->hubspotFormsService->getForms()
        as $name => $id )
        {
            array_push( $options, [
                'label' => $name, 
                'value' => $id
            ]);
        }

        /* Render the dropdown */
        return Craft::$app->getView()->renderTemplate('_includes/forms/select', [
            'name'    => $this->handle,
            'value'   => $value,
            'options' => $options
        ]);

    }

    /*
     * Define field settings
     */
    public function getSettingsHtml(): ?string
    {
        return null; // No settings for this field
    }

}