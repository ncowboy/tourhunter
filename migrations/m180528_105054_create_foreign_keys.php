<?php

use yii\db\Migration;

/**
 * Class m180528_105054_create_foreign_keys
 */
class m180528_105054_create_foreign_keys extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function Up()
    {
      $this->addForeignKey('transactions_ibfk_1', 'transactions', 'sender_uid', 'users', 'id');
      $this->addForeignKey('transactions_ibfk_2', 'transactions', 'recipient_uid', 'users', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function Down()
    {
      $this->dropForeignKey('transactions_ibfk_1', 'transactions');
      $this->dropForeignKey('transactions_ibfk_2', 'transactions');
    }
}
