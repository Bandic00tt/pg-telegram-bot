<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property integer $news_id
 * @property string $title
 * @property string $url
 * @property string $description
 * @property string $image
 * @property string $pub_date
 * @property string $saved_at
 * @property integer $posted
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['news_id', 'title', 'url'], 'required'],
            [['news_id', 'posted'], 'integer'],
            [['description'], 'string'],
            [['pub_date', 'saved_at'], 'safe'],
            [['title', 'url', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'news_id' => 'ID новости',
            'title' => 'Заголовок',
            'url' => 'Ссылка на новость',
            'description' => 'Описание',
            'image' => 'Превью',
            'pub_date' => 'Дата публикации',
            'saved_at' => 'Сохранено',
            'posted' => 'Опубликовано (в канале)',
        ];
    }
    
    /**
     * @param type $insert
     * @return type
     */
    public function beforeSave($insert)
    {
        // Предотвращаем повторное сохранение уже имеющихся новостей
        if ($this->isNewRecord){
            $entry = static::findOne(['news_id' => $this->news_id]);
            if ($entry){
                return false;
            }
        }
        
        $this->saved_at = date('Y-m-d H:i:s');
        return parent::beforeSave($insert);
    }        
}
