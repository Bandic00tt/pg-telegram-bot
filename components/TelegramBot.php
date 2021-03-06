<?php
namespace app\components;

use Yii;
use app\models\News;
use app\models\Proxy;
use GuzzleHttp\Client;
use app\models\Receiver;
use yii\helpers\ArrayHelper;
use app\models\LogSendMessage;

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
        $receivers = $this->sendContent($newsContent);
        
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
     * @param type $content
     */
    public function sendManually($content)
    {
        $this->sendContent($content);
    }    

    public function getLastAddedProxy()
    {
        $proxy = Proxy::find()->orderBy('id desc')->one();
        return $proxy ? ['host' => $proxy->ip, 'port' => $proxy->port] : false;
    }

    /**
     * @param type $content
     * @return boolean
     */
    public function sendContent($content)
    {
        $url = $this->getApiUrl();
        $receivers = $this->getReceivers();
        $proxy = $this->getLastAddedProxy();
        if ($proxy) {
            $host = $proxy['host'];
            $port = $proxy['port'];
        }
        
        foreach ($receivers as $r){
            $params = [
                'chat_id' => $r,
                'text' => $content,
                'parse_mode' => 'HTML'
            ];
            
            $client = new Client();
            try {
                $sendParams = [
                    'query' => $params,
                ];

                if ($proxy) {
                    $sendParams['proxy'] = "http://$host:$port";
                }

                $client->request('GET', $url .'/sendMessage', $sendParams);
            } catch (\Exception $e){
                echo 'Ошибка отправки сообщения: '. $e->getMessage() . "\n";
            }
        }
        
        return $receivers;
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
            if (!isset($msg['message'])){
                continue;
            }
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
        // Настройки прокси
        //$user = Yii::$app->params['user'];
        //$pass = Yii::$app->params['pass'];
        //$auth = base64_encode("$user:$pass");

        $url = $this->getApiUrl();
        $updates = [];
        try {
            $proxy = $this->getLastAddedProxy();
            if ($proxy) {
                $host = $proxy['host'];
                $port = $proxy['port'];

                $aContext = [
                    'http' => [
                        'proxy' => "tcp://$host:$port",
                        'request_fulluri' => true,
                        //'header' => "Proxy-Authorization: Basic $auth",
                    ],
                ];
                $cxContext = stream_context_create($aContext);
                $updJson = file_get_contents($url .'/getUpdates', false, $cxContext);
            } else {
                $updJson = file_get_contents($url .'/getUpdates');
            }
            
            $updates = json_decode($updJson, true);
        } catch (\Exception $e) {
            Yii::error($e->getMessage(). " host: $host; port: $port");
        }
        
        
        return $updates;
    }        
}
