<?php

use yii\db\Migration;

class m180829_234200_create_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('message', [
            'id' => $this->primaryKey(),
            'sender_id' => $this->integer()->notNull(),
            'receiver_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'text' => $this->text()->notNull(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createIndex(
            'idx-message-sender_id',
            'message',
            'sender_id'
        );

        $this->createIndex(
            'idx-message-receiver_id',
            'message',
            'receiver_id'
        );

        $this->addForeignKey(
            'fk-user-sender_id',
            'message',
            'sender_id',
            'user',
            'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-user-receiver_id',
            'message',
            'receiver_id',
            'user',
            'id',
            'RESTRICT'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-user-receiver_id',
            'message'
        );

        $this->dropForeignKey(
            'fk-user-sender_id',
            'message'
        );

        $this->dropIndex(
            'idx-message-receiver_id',
            'message'
        );

        $this->dropIndex(
            'idx-message-sender_id',
            'message'
        );

        $this->dropTable('message');
    }

}
