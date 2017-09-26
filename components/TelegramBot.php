<?php
namespace app\components;

use Yii;
use app\models\News;
use app\models\Receiver;
use app\models\LogSendMessage;
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
        
        if (!$freshNewsRow){
            return false;
        }
        
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
            try {
                $client->request('GET', $url .'/sendMessage', [
                    'query' => $params
                ]);
            } catch (\Exception $e){
                echo 'Ошибка отправки сообщения: '. $e->getMessage() . "\n";
            }
        }
        
        $freshNewsRow->posted = 1;
        if (!$freshNewsRow->save()){
            throw new \Exception(print_r($freshNewsRow->errors, true));
        }
        
        $log = new LogSendMessage();
        $log->news_id = $freshNewsRow->news_id;
        $log->receivers = json_encode($receivers);
        $log->r_total = count($receivers);
        $log->save();
        
        return true;
    }  
    
    /**
     * @param type $row
     * @return type
     */
    public function getNewsContent($row)
    {
        $content = "<a href='". $row->url ."' target='_blank'>". $row->title ."</a>";
        return $content;
    }        
    
    /**
     * @return type
     */
    public function getReceivers()
    {
        $this->getReceiversFromApi();
        $receivers = $this->getReceiversFromDb();
        
        return $receivers;
    }
    
    /**
     * @return type
     */
    public function getReceiversFromDb()
    {
        $rows = Receiver::find()->all();
        $receivers = array_map(function($item){
            return $item->chat_id;
        }, $rows);
        
        return $receivers;
    }   
    
    /**
     * @return type
     */
    public function getReceiversFromApi()
    {
        $updates = $this->getUpdates();
        $msgs = ArrayHelper::getValue($updates, 'result', []);
        $chatIds = [];
        
        foreach ($msgs as $msg){
            $chatId = $msg['message']['chat']['id'];
            if (in_array($chatId, $chatIds)){
                continue;
            }
            $chatIds[] = $chatId;
            
            $model = new Receiver();
            $model->chat_id = (string)$chatId;
            if (!$model->save() && !empty($model->errors)){
                throw new \Exception(print_r($model->errors, true));
            }
        }
        
        return $chatIds;
    }        
    
    /**
     * @return type
     */
    public function getUpdates()
    {
        $url = $this->getApiUrl();
        $updJson = file_get_contents($url .'/getUpdates');
        $updates = json_decode($updJson, true);
        
        return $updates;
    }        
}
