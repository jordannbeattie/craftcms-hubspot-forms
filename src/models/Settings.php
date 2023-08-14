<?php
namespace jordanbeattie\hubspotforms\models;

use Craft;

class Settings extends \craft\base\Model
{

    /*
     * Declare Variables
     */
    public $hsToken, $hsPortalId;
    
    /*
     * Define rules
     */
    public function rules(): array
    {
        return [
            [
                ['hsToken'], 'required'
            ]
        ];
    }
    
    /*
     * Get hsToken
     */
    public function getHsToken(): ?string
    {
        return Craft::parseEnv( $this->hsToken );
    }
    
    /*
     * Get hsPortalId
     */
    public function getHsPortalId(): ?string
    {
        return Craft::parseEnv( $this->hsPortalId );
    }
    
}
