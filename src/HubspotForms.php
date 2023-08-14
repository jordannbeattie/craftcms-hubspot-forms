<?php

namespace jordanbeattie\hubspotforms;

use craft\base\Plugin;
use Craft;
use craft\web\twig\variables\CraftVariable;

use jordanbeattie\hubspotforms\variables\HubspotFormsVariable;
use yii\base\Event;

class HubspotForms extends Plugin
{
    
    /*
     * Variables
     */
    public static $plugin;
    public string $schemaVersion = '1.0.0';
    public bool $hasCpSettings = true;
    
    public function init()
    {
        
        /*
         * Initiate parent
         */
        parent::init();

        /*
         * Set plugin variable
         */
        self::$plugin = $this;
        
        /*
         * Twig variable
         */
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                $variable = $event->sender;
                $variable->set('hubspotforms', HubspotFormsVariable::class);
            }
        );
        
    }

    /*
     * Settings model
     */
    protected function createSettingsModel(): ?\craft\base\Model{
        return new \jordanbeattie\hubspotforms\models\Settings();
    }

    /*
     * Settings template
     */
    protected function settingsHtml(): ?string{
        return \Craft::$app->getView()->renderTemplate('hubspot-forms/settings', [
            'settings' => $this->getSettings()
        ]);
    }

}
