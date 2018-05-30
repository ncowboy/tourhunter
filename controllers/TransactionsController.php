<?php

namespace app\controllers;

use Yii;
use app\models\Transactions;
use app\models\TransactionsSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Users;
use yii\helpers\ArrayHelper;

/**
 * TransactionsController implements the CRUD actions for Transactions model.
 */
class TransactionsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Transactions models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransactionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Creates a new Transactions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Transactions();
        $loggedUser = Yii::$app->user->identity;
        $users = Users::find()->where('id != ' . $loggedUser->id )->all();
        $userItems = ArrayHelper::map($users, 'id', 'username');
        $model->sender_uid = $loggedUser->id;
        if($model->load(Yii::$app->request->post())&& $model->save()){
          $model->processTransaction(); 
          return $this->redirect(['index']);
        }else{
          return $this->render('create', [
            'model' => $model,
            'userItems' => $userItems,
        ]);
        }
    }
}
