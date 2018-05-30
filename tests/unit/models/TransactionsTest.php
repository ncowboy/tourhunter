<?php
namespace models;

use app\models\Transactions;
use app\models\Users;

class TransactionsTest extends \Codeception\Test\Unit
{
       /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
      $this->tester->haveFixtures([
        'users' => [
          'class' => \app\fixtures\UsersFixture::class,
          'dataFile' => codecept_data_dir() . 'users.php'
        ]
      ]);
    }

    public function testCreate()
    {
      $transaction = new Transactions;
      $transaction->sender_uid = 1;
      $transaction->recipient_uid = 2;
      $transaction->amount = 500;
      $transaction->processTransaction();
      expect_that($transaction->save());
      expect_that($transaction->success == 1);
    }
    
    public function testCreateUserLowBallance()
    {
      $transaction = new Transactions;
      $transaction->sender_uid = 4;
      $transaction->recipient_uid = 3;
      $transaction->amount = 200;
      $transaction->processTransaction();
      expect_that($transaction->save());
      expect_that($transaction->success == 0);
    }
    
    public function testUserBallanceChanges(){
      $transaction = new Transactions;
      $transaction->sender_uid = 5;
      $transaction->recipient_uid = 6;
      $transaction->amount = 50.00;
      $transaction->processTransaction();
      $sender = Users::findOne(['id' => 5]);
      $recipient = Users::findOne(['id' => 6]);
      expect_not($sender == NULL);
      expect_not($recipient == NULL);
      expect_that($sender->ballance == 150.00);
      expect_that($recipient->ballance == 250.00);
    }
    
    public function testUserBallanceNotChangesIfLowBallance(){
      $transaction = new Transactions;
      $transaction->sender_uid = 5;
      $transaction->recipient_uid = 6;
      $transaction->amount = 1250.00;
      $transaction->processTransaction();
      $sender = Users::findOne(['id' => 5]);
      $recipient = Users::findOne(['id' => 6]);
      expect_not($sender == NULL);
      expect_not($recipient == NULL);
      expect_that($sender->ballance == 200.00);
      expect_that($recipient->ballance == 200.00);
    }
      
}