<?php
namespace app\commands;

use yii\console\Controller;
use app\components\RssParser;
use app\components\TelegramBot;

class ConsoleController extends Controller
{
    /**
     * @return type
     */
    public function actionGetFreshNews()
    {
        $news = (new RssParser())->getNews();
        (new TelegramBot())->sendNews();
        
        return 'Fresh news count: '. count($news);
    }       
}
