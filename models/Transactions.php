<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transactions".
 *
 * @property int $id
 * @property int $sender_uid
 * @property int $recipient_uid
 * @property int $amount
 * @property int $success
 * @property string $status
 * @property string $datetime
 *
 * @property Users $senderU
 * @property Users $recipientU
 */
class Transactions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transactions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sender_uid', 'recipient_uid', 'amount'], 'required'],
            [['sender_uid', 'recipient_uid', 'success'], 'integer'],
            [['amount'], 'number', 'min' => 0.01],
            [['datetime', 'status'], 'safe'],
            [['datetime'], 'default', 'value' => date('Y:m:d H:i:s')],
            [['sender_uid'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['sender_uid' => 'id']],
            [['recipient_uid'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['recipient_uid' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sender_uid' => 'Sender Uid',
            'recipient_uid' => 'Recipient Uid',
            'amount' => 'Amount',
            'success' => 'Success',
            'status' => 'Status',
            'datetime' => 'Datetime',
            'senderUsername' => 'From',
            'recipientUsername' => 'To',
        ];
    }

    public function getSenderUid()
    {
        return $this->hasOne(Users::className(), ['id' => 'sender_uid']);
    }
    
    public function getSenderUsername()
    {
        return $this->senderUid->username;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecipientUid()
    {
        return $this->hasOne(Users::className(), ['id' => 'recipient_uid']);
    }
    
    public function getRecipientUsername()
    {
        return $this->recipientUid->username;
    }
    
    public function processTransaction(){
      $sender = Users::findOne(['id' => $this->sender_uid]);
      $recipient = Users::findOne(['id' => $this->recipient_uid]);
      $sender->ballance -= $this->amount;
      if($sender->save()){
        $recipient->ballance += $this->amount;
        $recipient->save();
        $this->success = 1;
        $this->status = 'approved';
        $this->save();
      }else{
        $this->success = 0;
        $this->status = 'denied';
        $this->save();
      }
    }
}
