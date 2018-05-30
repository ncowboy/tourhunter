<?php

use yii\db\Migration;

/**
 * Class m180528_103426_create_db_users
 */
class m180528_103426_create_db_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function Up()
    {
      $this->createTable('users', [
        'id' => $this->primaryKey(),
        'username' => $this->string(64)->notNull()->unique(),
        'ballance' => $this->decimal(11, 2)->defaultValue(0,00)
      ]);
      
      $this->insert('users', ['username' => 'user1']);
      $this->insert('users', ['username' => 'user2']);
      $this->insert('users', ['username' => 'user3']);
      
    }

    /**
     * {@inheritdoc}
     */
    public function Down()
    {
      $this->dropTable('users');
    }
}
