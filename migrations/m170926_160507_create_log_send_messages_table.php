<?php

use yii\db\Migration;

/**
 * Handles the creation of table `log_send_messages`.
 */
class m170926_160507_create_log_send_messages_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('log_send_messages', [
            'id' => $this->primaryKey(),
            'news_id' => $this->integer()->notNull(),
            'receivers' => $this->text()->notNull(),
            'r_total' => $this->integer()->notNull(),
            'sent_at' => $this->datetime()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('log_send_messages');
    }
}
