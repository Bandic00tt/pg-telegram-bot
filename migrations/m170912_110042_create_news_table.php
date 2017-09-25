<?php

use yii\db\Migration;

/**
 * Handles the creation of table `news`.
 */
class m170912_110042_create_news_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('news', [
            'id' => $this->primaryKey(),
            'news_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'url' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'image' => $this->string(),
            'pub_date' => $this->dateTime()->notNull(),
            'saved_at' => $this->dateTime(),
            'posted' => $this->smallInteger()->defaultValue(0)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('news');
    }
}
