<?php

use yii\db\Migration;
use yii\db\Schema;


class m170813_130708_init_for_demo extends Migration
{
    public function safeUp()
    {
        // create table: user
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // we use utf8mb4 charset so that we can handle multi-byte character (like Japanese, Korean, etc)
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%user}}', [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING . ' NOT NULL',
            'auth_key' => Schema::TYPE_STRING . ' NOT NULL',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING,
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 11',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);
    }

    public function safeDown()
    {
        echo "m170813_130708_init_for_demo cannot be reverted.\n";

        return false;
    }
}
