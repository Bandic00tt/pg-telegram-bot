<?php

use yii\db\Migration;

/**
 * Handles the creation of table `proxy`.
 */
class m180422_150641_create_proxy_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('proxy', [
            'id' => $this->primaryKey(),
            'ip' => $this->string()->notNull(),
            'port' => $this->integer()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('proxy');
    }
}
