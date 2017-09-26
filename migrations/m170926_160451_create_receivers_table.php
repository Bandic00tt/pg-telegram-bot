<?php

use yii\db\Migration;

/**
 * Handles the creation of table `receivers`.
 */
class m170926_160451_create_receivers_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('receivers', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->string()->unique()->notNull(),
            'added_at' => $this->dateTime()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('receivers');
    }
}
