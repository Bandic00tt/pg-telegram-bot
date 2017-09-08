<?php
namespace app\commands;

use yii\console\Controller;

class ConsoleController extends Controller
{
    
    public function actionParseRss()
    {
        $object = simplexml_load_file('http://pg21.ru/rss');
        dd($object);
    }
}
