<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "receivers".
 *
 * @property integer $id
 * @property string $chat_id
 * @property string $added_at
 */
class Receiver extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'receivers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['chat_id'], 'required'],
            [['added_at'], 'safe'],
            [['chat_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'chat_id' => 'ID чата',
            'added_at' => 'Дата добавления',
        ];
    }
    
    /**
     * @param type $insert
     * @return type
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord){
            $row = static::findOne(['chat_id' => $this->chat_id]);
            if (!empty($row)){
                return false;
            }
            $this->added_at = date('Y-m-d H:i:s');
        }
        return parent::beforeSave($insert);
    }        
}
