<?php
namespace app\controllers;

use yii\web\Controller;
use app\components\TelegramBot;

class TestController extends Controller
{
    public function actionTest()
    {
        var_dump((new TelegramBot())->sendNews());
    }        
}
