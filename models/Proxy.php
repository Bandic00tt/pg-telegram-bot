<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "proxy".
 *
 * @property int $id
 * @property string $ip
 * @property int $port
 */
class Proxy extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'proxy';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip', 'port'], 'required'],
            [['port'], 'integer'],
            [['ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip' => 'Ip',
            'port' => 'Port',
        ];
    }
}
