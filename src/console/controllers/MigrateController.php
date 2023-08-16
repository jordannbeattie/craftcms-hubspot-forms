<?php

namespace jordanbeattie\hubspotforms\console\controllers;

use Craft;
use craft\console\Controller;
use craft\helpers\Console;
use jordanbeattie\hubspotforms\fields\HubspotFormDropdown;
use yii\console\ExitCode;

class MigrateController extends Controller
{
    
    public $defaultAction = 'run';

    
    public function actionRun(): int
    {

        /* Get the user to confirm */
        $this->line( "You should ensure you have a backup of your database and run this in a local environment.", "warning" );
        if( !$this->confirm("Are you sure you want to continue?") )
        {
            $this->line( "Migration cancelled!", "error" );
            return $this->end();
        }

        /* Check old plugin is installed */
        if( !Craft::$app->plugins->isPluginInstalled('hubspot') )
        {
            $this->line("HubSpot plugin not installed", "error");
            return $this->end();
        }
        else 
        {
            $this->line( "'hubspot' plugin settings validated.", "success" );
        }

        /* Check plugin has valid settings */
        if( !Craft::$app->plugins->getPlugin('hubspot-forms')->hubspotFormsService->hasValidSettings() )
        {
            $this->line("Your HubSpot token could not be validated. Please check your settings.", "error");
            return $this->end();
        }
        else
        {
            $this->line( "'hubspot-forms' plugin settings validated.", "success" );
        }

        /* Create array of old fields */
        $oldFields = [];

        /* Search for old fields */    
        $oldFields = $this->getOldFormFields( Craft::$app->fields->getAllFields(), $oldFields );

        /* Output how many fields were found */
        $this->line( "Found " . count( $oldFields ) . " old field(s)" );

        /* Update each field type */
        foreach( $oldFields as $field )
        {
            
            /* Output info */
            $this->line( "Migrating {$field->handle}...");
            
            /* Update field type */
            if( !$this->updateFieldType( $field ) )
            {
                $this->line( "Error migrating " . $field->handle );
            }

        }

        /* Output success message */
        $this->line( count($oldFields) . " field(s) migrated!", "success" );
        return $this->end();

    }

    /*
     * Output
     */
    private function line( $message, $type = null )
    {

        
        switch( $type )
        {
            case "success":
                $type = Console::FG_GREEN;
                break;

            case "warning":
                $type = Console::FG_YELLOW;
                break;

            case "error":
                $type = Console::FG_RED;
                break;

            default:
                $type = null;
                break;
        }

        $this->stdout( 
            $message . PHP_EOL, 
            $type
        );
    }

    /*
     * End Command
     */
    private function end()
    {
        return ExitCode::OK;
    }

    /*
     * Add To Array If Type Matched
     */
    private function getOldFormFields( $fields, $array )
    {
        foreach( $fields as $field )
        {

            // $this->line( $field->handle . " is instanceof " . get_class( $field ) );

            if( is_string( $field ) )
            {
                $this->line( $field );
            }

            /* If field is old hubspot form, add to the array */
            if( $field instanceof \jordanbeattie\hubspot\fields\HubspotForm )
            {
                array_push( $array, $field );
            }

            /* If field is matrix, loop block types and check fields */
            // elseif( $field instanceof \craft\fields\Matrix )
            // {
            //     foreach( $field->getBlockTypes() as $blockType )
            //     {
            //         $array = $this->getOldFormFields( Craft::$app->fields->id($blockType->fieldLayout->fieldIds), $array );
            //         $this->line( $blockType->handle . " is a matrix block and the " . $blockType->handle . " has a layout id of " . $blockType->fieldLayoutId );
            //     }
            // }

            /* If SuperTable installed */
            elseif( Craft::$app->plugins->isPluginInstalled('super-table') )
            {
                /* If field is SuperTable, loop fields and check */
                if( $field instanceof \verbb\supertable\fields\SuperTableField )
                {
                    $array = $this->getOldFormFields( $field->getBlockTypeFields(), $array );
                }
            }
            
        }

        /* Return fields */
        return $array;

    }

    /*
     * Update Field Type
     */
    private function updateFieldType( $field )
    {
        // Create a new field of type HubspotFormDropdown
        $newField = new HubspotFormDropdown();
        $newField->id = $field->id;
        $newField->groupId = $field->groupId;
        $newField->name = $field->name;
        $newField->handle = $field->handle;
        $newField->instructions = $field->instructions;
        $newField->translationMethod = $field->translationMethod;
        $newField->translationKeyFormat = $field->translationKeyFormat;

        // Save the new field
        return Craft::$app->fields->saveField($newField) ? true : false;

    }
    
}