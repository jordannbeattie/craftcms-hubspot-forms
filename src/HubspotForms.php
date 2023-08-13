<?php

namespace jordanbeattie\hubspotforms;

use craft\base\Plugin;
use craft\web\twig\variables\CraftVariable;
use jordanbeattie\hubspotforms\variables\HubspotFormsVariable;

use yii\base\Event;

class HubspotForms extends Plugin
{
    
    public static $plugin;
    public string $schemaVersion = '1.0.0';
    public bool $hasCpSettings = true;
    
    public function init()
    {
        
        /*
         * Initiate Parent
         */
        parent::init();

        /*
         * Set Plugin Variable
         */
        self::$plugin = $this;
        
        /*
         * Twig Variable
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

}
