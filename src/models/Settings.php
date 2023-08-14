<?php
namespace jordanbeattie\hubspotforms\models;

use Craft;

class Settings extends \craft\base\Model
{
    public $hsToken;
    
    public function rules(): array
    {
        return [
            [
                ['hsToken'], 'required'
            ]
        ];
    }
    
    public function getHsToken(): ?string
    {
        return Craft::parseEnv($this->hsToken);
    }
    
}
