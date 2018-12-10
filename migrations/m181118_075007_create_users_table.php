<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users`.
 */
class m181118_075007_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
            $this->createTable('users', [
                'id' => $this->primaryKey(),
                'username' => $this ->string(30) -> notNull(),
                'email' => $this ->string(100) -> notNull()-> unique(),
                'password_hash' => $this ->string(255) -> notNull(),
                'auth_key' => $this ->char(50) -> notNull(),
                'secret_key' => $this->string(),
                'status' => $this->smallInteger()->notNull(),
                'created_at' => $this->TIMESTAMP()->notNull(),
                'updated_at' => $this->TIMESTAMP()->notNull(),

            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('users');
    }

}
