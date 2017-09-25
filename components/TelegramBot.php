<?php
namespace app\components;

use Yii;
use app\models\News;
use yii\helpers\ArrayHelper;
use GuzzleHttp\Client;

class TelegramBot 
{
    private $ws = 'https://api.telegram.org/bot';
    
    /**
     * @return type
     */
    public function getApiUrl()
    {
        return $this->ws . Yii::$app->params['bot_token'];
    }        
    
    /**
     * @return type
     * @throws \Exception
     */
    public function sendNews()
    {
        $freshNewsRow = News::find()->where([
            'posted' => 0
        ])->orderBy('pub_date')->one();
        
        $newsContent = $this->getNewsContent($freshNewsRow);
        
        $url = $this->getApiUrl();
        $receivers = $this->getReceivers();
        foreach ($receivers as $r){
            $params = [
                'chat_id' => $r,
                'text' => $newsContent,
                'parse_mode' => 'HTML'
            ];
            
            $client = new Client();
            $res = $client->request('GET', $url .'/sendMessage', [
                'query' => $params
            ]);
            
            $status = $res->getStatusCode();
            if ($status != 200){
                throw new \Exception('Ошибка отправки сообщения: '. print_r($res, true));
            }
        }
        
        $freshNewsRow->posted = 1;
        $freshNewsRow->save();
        
        $result = [
            'ID новости' => $freshNewsRow->news_id,
            'Время отправки' => date('Y-m-d H:i:s'),
            'Количество получателей' => count($receivers)
        ];
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $result;
    }  
    
    /**
     * @param type $row
     * @return type
     */
    public function getNewsContent($row)
    {
        $content .= "<a href='". $row->url ."' target='_blank'>". $row->title ."</a>";
        
        return $content;
    }        
    
    /**
     * @return type
     */
    public function getReceivers()
    {
        $url = $this->getApiUrl();
        $updJson = file_get_contents($url .'/getUpdates');
        $updates = json_decode($updJson, true);
        $msgs = ArrayHelper::getValue($updates, 'result', []);
        $chatIds = [];
        
        foreach ($msgs as $msg){
            $chatIds[] = $msg['message']['chat']['id'];
        }
        
        return $chatIds;
    }        
}
