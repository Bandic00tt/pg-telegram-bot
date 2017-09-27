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
            $row['news_id'] = $this->getNewsId($item);
            $row['title'] = $this->getTitle($item);
            $row['url'] = $this->getUrl($item);
            $row['image'] = $this->getImage($item);
            $row['description'] = $this->getDescription($item);
            $row['pub_date'] = $this->getPubDate($item);
            
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
    public function getNewsId($item)
    {
        if (isset($item->link)){
            $url = $item->link;
            $parts = explode("/", $url);
            return end($parts);
        } else {
            return false;
        }
    }
    
    /**
     * @param type $item
     * @return boolean
     */
    public function getTitle($item)
    {
        if (isset($item->title)){
            return trim($item->title);
        } else {
            return false;
        }
    }  
    
    /**
     * @param type $item
     * @return boolean
     */
    public function getUrl($item)
    {
        if (isset($item->link)){
            return trim($item->link);
        } else {
            return false;
        }
    }        
    
    /**
     * @param type $item
     * @return boolean
     */
    public function getImage($item)
    {
        if (isset($item->enclosure)){
            return trim((string)$item->enclosure['url']);
        } else {
            return false;
        }
    }   
    
    /**
     * @param type $item
     * @return boolean
     */
    public function getDescription($item)
    {
        if (isset($item->description)){
            return trim($item->description);
        } else {
            return false;
        }
    }        
    
    /**
     * Получаем дату публикации в приемлемом формате
     * @param type $rawDate
     * @return type
     */
    public function getPubDate($item)
    {
        if (isset($item->pubDate)){
            $rawDate = $item->pubDate;
            return date('Y-m-d H:i:s', strtotime($rawDate));
        } else {
            return false;
        }
    }        
}
