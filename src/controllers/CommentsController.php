<?php

namespace hesabro\notif\controllers;

use backend\models\CustomerCommentsSearch;
use common\models\Comments;
use common\models\CustomerComments;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * CommentsController implements the CRUD actions for CustomerComments model.
 */
class CommentsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['customer_comment/index'],
                        'actions' => ['index']
                    ],
                    [
                        'allow' => true,
                        'roles' => ['customer_comment/view'],
                        'actions' => ['get-customer-comment', 'view', 'test']
                    ],
                ]
            ]
        ];
    }

    /**
     * Lists all CustomerComments models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CustomerCommentsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CustomerComments model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model=$this->findModel($id);
        $comments=Comments::find()->byClass($model::className(), $model->id)->orderBy(['id' => SORT_DESC])->all();

        $providerChecks = new ActiveDataProvider([
            'query' => $model->getChecks(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $providerFactors = new ActiveDataProvider([
            'query' => $model->getFactor(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('view', [
            'model' => $model,
            'comments' => $comments,
            'providerChecks' => $providerChecks,
            'providerFactors' => $providerFactors,
        ]);
    }


    /**
     * لیست حساب های یک مشتری برای ثبت فاکتور
     * $type=1 نوع اقساط
     */
    public function actionGetCustomerComment($selected=0, $type=CustomerComments::TYPE_DEBT)
    {

        $params_shobe = null;
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $customer_id = empty($parents[0]) ? null : $parents[0];
                $out = CustomerComments::getCustomerComment($customer_id, true, $type);

                return $this->asJson(['output' => $out['item'], 'selected' =>$selected ? "$selected" : $out['select']]);
            }
        }
        return $this->asJson(['output' => '', 'selected' => "$selected"]);

    }

    /**
     * Finds the CustomerComments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CustomerComments the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CustomerComments::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
