<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\RssParser;
use app\components\TelegramBot;

class TestController extends Controller
{
    public function actionTest()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        dd((new TelegramBot())->getUpdates());
    }        
}
