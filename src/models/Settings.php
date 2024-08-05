<?php
namespace jordanbeattie\hubspotforms\models;

use Craft;

class Settings extends \craft\base\Model
{

    /*
     * Declare Variables
     */
    public $hsToken, $hsPortalId, $hsLimit;
    
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
    
    /*
     * Get hsPortalId
     */
    public function getHsLimit(): int
    {
        $int = intval($this->hsLimit);
        if( $int > 0 ) return $int;
        return 100;
    }
    
}
