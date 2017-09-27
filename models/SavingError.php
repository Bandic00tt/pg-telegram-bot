<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "saving_errors".
 *
 * @property integer $id
 * @property integer $news_id
 * @property string $errors
 */
class SavingError extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'saving_errors';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['news_id'], 'required'],
            [['news_id'], 'integer'],
            [['errors'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'news_id' => 'News ID',
            'errors' => 'Errors',
        ];
    }
}
