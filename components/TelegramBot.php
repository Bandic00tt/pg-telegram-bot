<?php
namespace app\components;

use Yii;
use app\models\News;
use app\models\Receiver;
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
        
        $result = [
            'ID новости' => $freshNewsRow->news_id,
            'Время отправки' => date('Y-m-d H:i:s'),
            'Количество получателей' => count($receivers)
        ];
        
        return json_encode($result, JSON_UNESCAPED_UNICODE);
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
        return $rows;
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
            $chatIds[] = $chatId;
            
            $model = new Receiver();
            $model->chat_id = $chatId;
            $model->save();
        }
        
        // На всякий случай проверяем на дубликаты
        $chatIds = array_unique($chatIds);
        
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
