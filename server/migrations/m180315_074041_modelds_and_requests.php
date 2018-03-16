<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180315_074041_modelds_and_requests
 */
class m180315_074041_modelds_and_requests extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";

        /** Token */
        $this->createTable("token", [
            "id" => Schema::TYPE_PK,
            "token" => "varchar(50)",
            "allowance" => Schema::TYPE_INTEGER . " default 0",
            "allowance_updated_at" => Schema::TYPE_INTEGER . " NULL",
            "created_at" => Schema::TYPE_INTEGER . " NULL",
            "updated_at" => Schema::TYPE_INTEGER . " NULL",
        ], $tableOptions);

        /** Models */
        $this->createTable("model", [
            "id" => Schema::TYPE_PK,
            "token" => Schema::TYPE_INTEGER . " NOT NULL",
            "trained_images" => Schema::TYPE_INTEGER . " default 0",
            "created_at" => Schema::TYPE_INTEGER . " NULL",
            "updated_at" => Schema::TYPE_INTEGER . " NULL",
        ], $tableOptions);

        /** Requests */
        $this->createTable("request", [
            "id" => Schema::TYPE_PK,
            "request" => "text NULL",
            "answer" => "text NULL",
            "created_at" => Schema::TYPE_INTEGER . " NULL",
            "updated_at" => Schema::TYPE_INTEGER . " NULL",
        ], $tableOptions);

        $this->addForeignKey('model_fk_token', 'model', 'token', 'token', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180315_074041_modelds_and_requests cannot be reverted.\n";

        return false;
    }
}
