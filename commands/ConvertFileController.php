<?php

namespace app\commands;

use app\components\Convert;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\BaseConsole;


class ConvertFileController extends Controller
{
    public function actionIndex(): int
    {

        $path = Yii::getAlias('@app/src');
        $convert = new Convert($path, Yii::$app->params['extensions']);
        $convert->run();
        $this->stdout("OK", BaseConsole::BOLD);
        return ExitCode::OK;
    }
}