<?php

namespace hesabro\notif\controllers;

use common\traits\AjaxValidationTrait;
use Yii;
use common\models\CommentsType;
use backend\models\CommentsTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * CommentsTypeController implements the CRUD actions for CommentsType model.
 */
class CommentsTypeController extends Controller
{
    use AjaxValidationTrait;
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
            'access' => [
                'class' => AccessControl::className(),
                'rules' =>
                    [
                        [
                            'allow' => true,
                            'roles' => ['comments/type'],
                        ],
                    ]
            ]
        ];
    }

    /**
     * Lists all CommentsType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CommentsTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CommentsType model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @return array|string
     * @throws Yii\base\ExitException
     */
    public function actionCreate()
    {
        $model = new CommentsType(['scenario'=>CommentsType::SCENARIO_CREATE]);
        $result = [
            'success' => false,
            'msg' => Yii::t("app", "Error In Save Info")
        ];
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $flag = $model->save(false);
                if ($flag) {
                    $result = [
                        'success' => true,
                        'msg' => Yii::t("app", "Item Created")
                    ];
                    $transaction->commit();
                } else {
                    $transaction->rollBack();
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::error($e->getMessage() . $e->getTraceAsString(), Yii::$app->controller->id.'/'.Yii::$app->controller->action->id);
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $result;
        }
        $this->performAjaxValidation($model);
        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return array|string
     * @throws NotFoundHttpException
     * @throws Yii\base\ExitException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario(CommentsType::SCENARIO_CREATE);
        if (!$model->canUpdate()) {
            throw new NotFoundHttpException(Yii::t('app','It is not possible to perform this operation'));
        }
        $result = [
            'success' => false,
            'msg' => Yii::t("app", "Error In Save Info")
        ];
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $flag = $model->save(false);
                if ($flag) {
                    $result = [
                        'success' => true,
                        'msg' => Yii::t("app", "Item Updated")
                    ];
                    $transaction->commit();
                } else {
                    $transaction->rollBack();
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::error($e->getMessage() . $e->getTraceAsString(), Yii::$app->controller->id.'/'.Yii::$app->controller->action->id);
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $result;
        }
        $this->performAjaxValidation($model);
        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->canDelete()) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $flag = $model->softDelete();
                if ($flag) {
                    $transaction->commit();
                    $result = [
                        'status' => true,
                        'message' => Yii::t("app", "Item Deleted")
                    ];
                } else {
                    $transaction->rollBack();
                    $result = [
                        'status' => false,
                        'message' => Yii::t("app", "Error In Save Info")
                    ];
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                $result = [
                    'status' => false,
                    'message' => $e->getMessage()
                ];
                Yii::error($e->getMessage() . $e->getTraceAsString(), Yii::$app->controller->id . '/' . Yii::$app->controller->action->id);
            }
        } else {
            $result = [
                'status' => false,
                'message' => Yii::t("app", "It is not possible to perform this operation")
            ];
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }
    /**
     * Finds the CommentsType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CommentsType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CommentsType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function flash($type, $message)
    {
        Yii::$app->getSession()->setFlash($type == 'error' ? 'danger' : $type, $message);
    }
}
