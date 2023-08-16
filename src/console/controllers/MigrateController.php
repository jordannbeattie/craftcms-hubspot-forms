<?php

namespace jordanbeattie\hubspotforms\console\controllers;

use craft\console\Controller;
use craft\helpers\Console;
use yii\console\ExitCode;

class MigrateController extends Controller
{
    
    public $defaultAction = 'run';

    
    public function actionRun(): int
    {

        $this->stdout("Migrate command ready!");

        return ExitCode::OK;

    }
    
}