<?php
namespace app\components;

use app\models\News;
use app\models\SavingError;

class RssParser 
{
    const URL = 'http://pg21.ru/rss';
    
    /**
     * @return type
     * @throws \Exception
     */
    public function getNews()
    {
        $data = simplexml_load_file(self::URL);
        if (empty($data)){
            return false;
        }
        
        foreach ($data->channel->item as $item){
            $row = [];
            $row['news_id'] = $this->getNewsId($item->link);
            $row['title'] = trim($item->title);
            $row['url'] = trim($item->link);
            $row['image'] = trim((string)$item->enclosure['url']);
            $row['description'] = trim($item->description);
            $row['pub_date'] = $this->getPubDate($item->pubDate);
            
            $model = new News();
            $model->load(['News' => $row]);
            try {
                $model->save();
            } catch (\Exception $e){
                $err = new SavingError();
                $err->news_id = $row['news_id'];
                $err->errors = print_r($model->errors, true) ."\n". $e->getMessage();
                $err->save();
            }
        }
        
        return $data;
    }
    
    /**
     * Получаем ID новости, чтобы отличать их между собой
     * @param type $url
     * @return type
     */
    public function getNewsId($url)
    {
        $parts = explode("/", $url);
        return end($parts);
    }
    
    /**
     * Получаем дату публикации в приемлемом формате
     * @param type $rawDate
     * @return type
     */
    public function getPubDate($rawDate)
    {
        return date('Y-m-d H:i:s', strtotime($rawDate));
    }        
}
