<?php

namespace jordanbeattie\hubspotforms;

use Craft;
use craft\base\Plugin;
use craft\events\PluginEvent;

use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterTemplateRootsEvent;
use craft\services\Fields;
use craft\services\Plugins;
use craft\web\twig\variables\CraftVariable;
use craft\web\View;
use jordanbeattie\hubspotforms\fields\HubspotFormDropdown;
use jordanbeattie\hubspotforms\services\HubspotFormsService;
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
    private $isSavingSettings = false;
    
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
            function( Event $event ){
                $variable = $event->sender;
                $variable->set('hubspotforms', HubspotFormsVariable::class);
            }
        );

        /*
         * Field
         */
        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function( RegisterComponentTypesEvent $event ){
                $event->types[] = HubspotFormDropdown::class;
            }
        );

        /*
         * Templates
         */
        Event::on(
            View::class,
            View::EVENT_REGISTER_SITE_TEMPLATE_ROOTS,
            function( RegisterTemplateRootsEvent $event ){
                $event->roots['hubspot-forms'] = __DIR__ . '/templates';
            }
        );

        /*
         * Service
         */
        $this->setComponents([
            'hubspotFormsService' => HubspotFormsService::class,
        ]);

        /*
         * Settings Updated Event
         * Set the PortalId
         */
        Event::on(
            Plugins::class,
            Plugins::EVENT_BEFORE_SAVE_PLUGIN_SETTINGS,
            function(PluginEvent $event) {
                
                /* Prevent Recursion */
                if ($this->isSavingSettings) {
                    return;
                }

                /* Get new settings */
                $newSettings = $event->plugin->getSettings();

                /* Get Portal ID with new token */
                $newSettings->hsPortalId = $this->hubspotFormsService->getPortalId( $newSettings->getHsToken() );

                /* Save new settings */
                $this->isSavingSettings = true;
                Craft::$app->getPlugins()->savePluginSettings($this, $newSettings->toArray());
                $this->isSavingSettings = false;

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
        return \Craft::$app->getView()->renderTemplate('hubspot-forms/cms/settings', [
            'settings' => $this->getSettings()
        ]);
    }

}
