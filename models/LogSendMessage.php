<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log_send_messages".
 *
 * @property integer $id
 * @property integer $news_id
 * @property string $receivers
 * @property integer $r_total
 * @property string $sent_at
 */
class LogSendMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_send_messages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['news_id', 'receivers', 'r_total'], 'required'],
            [['news_id', 'r_total'], 'integer'],
            [['receivers'], 'string'],
            [['sent_at'], 'safe'],
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
            'receivers' => 'Получатели',
            'r_total' => 'Всего получателей',
            'sent_at' => 'Время отправления',
        ];
    }
    
    /**
     * @param type $insert
     * @return type
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord){
            $this->sent_at = date('Y-m-d H:i:s');
        }
        
        return parent::beforeSave($insert);
    }        
}
