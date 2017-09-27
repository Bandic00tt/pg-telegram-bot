<?php

use yii\db\Migration;

/**
 * Handles the creation of table `saving_errors`.
 */
class m170927_074744_create_saving_errors_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('saving_errors', [
            'id' => $this->primaryKey(),
            'news_id' => $this->integer()->notNull(),
            'errors' => $this->text(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('saving_errors');
    }
}
