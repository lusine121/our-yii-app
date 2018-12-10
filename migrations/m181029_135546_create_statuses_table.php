<?php

use yii\db\Migration;

/**
 * Handles the creation of table `statuses`.
 */
class m181029_135546_create_statuses_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('statuses', [
            'id' => $this->primaryKey(),
            'message' => $this -> string(255) -> notNull(),
            'permissions' => $this->integer(6) -> notNull(),
            'created_at' => $this -> integer(11)->notNull(),
            'updated_at' => $this -> integer(11)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('statuses');
    }
}
