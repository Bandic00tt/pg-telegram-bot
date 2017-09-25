<?php
namespace app\commands;

use yii\console\Controller;
use app\components\RssParser;

class ConsoleController extends Controller
{
    /**
     * @return type
     */
    public function actionParseRss()
    {
        $news = (new RssParser())->getNews();
        return 'Fresh news count: '. count($news);
    }
}
