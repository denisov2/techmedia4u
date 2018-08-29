<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property int $sender_id
 * @property int $receiver_id
 * @property string $title
 * @property string $text
 * @property int $status
 * @property int $created_at
 * @property int $updated
 *
 * @property User $receiver
 * @property User $sender
 */
class Message extends \yii\db\ActiveRecord
{
    const TYPE_SEND = 'sent';
    const TYPE_RECEIVED = 'received';

    const STATUS_SENT = 0;
    const STATUS_DELIVERED = 1;
    const STATUS_VIEWED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }
    
    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sender_id', 'receiver_id', 'title', 'text'], 'required'],
            [['sender_id', 'receiver_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['receiver_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['receiver_id' => 'id']],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sender_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sender_id' => 'Sender ID',
            'receiver_id' => 'Receiver ID',
            'title' => 'Title',
            'text' => 'Text',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver()
    {
        return $this->hasOne(User::className(), ['id' => 'receiver_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'sender_id']);
    }
}
