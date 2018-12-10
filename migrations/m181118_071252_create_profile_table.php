<?php

use yii\db\Migration;

/**
 * Handles the creation of table `profile`.
 */
class m181118_071252_create_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('profile', [
            'user_id' => $this->primaryKey(),
            'avatar' => $this->string(),
            'first_name' => $this->string(32)->notNull(),
            'last_name' => $this->string(32)->notNull(),
            'birthday' => $this->date()->notNull(),
            'gender' => $this->smallInteger()->notNull(),
        ]);

        $this->addForeignKey('profile_user', 'profile', 'user_id', 'users', 'id', 'cascade', 'cascade');

    }


    public function safeDown()
    {

        $this->dropForeignKey('profile_users', 'profile');
        $this->dropTable('profile');
    }

}
