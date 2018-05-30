<?php

use yii\db\Migration;

/**
 * Class m180528_103922_create_db_transactions
 */
class m180528_103922_create_db_transactions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function Up()
    {
      $this->createTable('transactions', [
        'id' => $this->primaryKey(),
        'sender_uid' => $this->integer(11)->notNull(),
        'recipient_uid' => $this->integer(11)->notNull(),
        'amount' => $this->decimal(11, 2)->notNull(),
        'success' => $this->boolean()->defaultValue(0),
        'status' => $this->string(256)->defaultValue(''),
        'datetime' => 'datetime DEFAULT NOW()'
      ]);
    }

    /**
     * {@inheritdoc}
     */
    public function Down()
    {
      $this->dropTable('transactions');
    }
}
