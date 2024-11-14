<?php

namespace hesabro\notif\controllers;

use Exception;
use hesabro\helpers\traits\AjaxValidationTrait;
use hesabro\notif\models\NotifListener;
use hesabro\notif\models\NotifListenerSearch;
use hesabro\notif\Module;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * CommentsTypeController implements the CRUD actions for CommentsType model.
 */
class NotifListenerController extends Controller
{
    use AjaxValidationTrait;

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
                'rules' =>
                    [
                        [
                            'allow' => true,
                            'roles' => ['notif-listener/view'],
                            'actions' => [
                                'index', 'view'
                            ]
                        ],
                        [
                            'allow' => true,
                            'roles' => ['notif-listener/actions'],
                            'actions' => [
                                'create', 'update', 'delete'
                            ]
                        ],
                    ]
            ]
        ];
    }

    public function actionIndex()
    {
        $searchModel = new NotifListenerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new NotifListener(['scenario' => NotifListener::SCENARIO_CREATE]);
        $result = [
            'success' => false,
            'msg' => Module::t('module', 'Error In Save Info')
        ];

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $flag = $model->save();
                if ($flag) {
                    $result = [
                        'success' => true,
                        'msg' => Module::t('module', 'Item Created Successfully')
                    ];
                    $transaction->commit();
                } else {
                    $transaction->rollBack();
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::error($e->getMessage() . $e->getTraceAsString(), Yii::$app->controller->id.'/'.Yii::$app->controller->action->id);
            }

            return $this->asJson($result);
        }

        $this->performAjaxValidation($model);
        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario(NotifListener::SCENARIO_UPDATE);

        if (!$model->canUpdate()) {
            throw new ForbiddenHttpException(Module::t('module','It is not possible to perform this operation'));
        }

        $result = [
            'success' => false,
            'msg' => Module::t('module', 'Error In Save Info')
        ];

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $transaction = Yii::$app->db->beginTransaction();
            try {
                $flag = $model->save();
                if ($flag) {
                    $result = [
                        'success' => true,
                        'msg' => Module::t('module', 'Item Updated Successfully')
                    ];
                    $transaction->commit();
                } else {
                    $transaction->rollBack();
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::error($e->getMessage() . $e->getTraceAsString(), Yii::$app->controller->id.'/'.Yii::$app->controller->action->id);
            }

            return $this->asJson($result);
        }

        $this->performAjaxValidation($model);
        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (!$model->canDelete()) {
            throw new ForbiddenHttpException(Module::t('module','It is not possible to perform this operation'));
        }

        $result = [
            'status' => false,
            'message' => Yii::t("app", "Error In Save Info")
        ];

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if ($model->softDelete()) {
                $transaction->commit();
                $result = [
                    'status' => true,
                    'message' => Module::t('module', 'Item Deleted Successfully')
                ];
            } else {
                $transaction->rollBack();
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage() . $e->getTraceAsString(), Yii::$app->controller->id . '/' . Yii::$app->controller->action->id);
            $result = [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }

        return $this->asJson($result);
    }

    private function findModel($id): NotifListener
    {
        if (($model = NotifListener::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Module::t('module', 'The requested item does not exist.'));
    }

    private function flash($type, $message): void
    {
        Yii::$app->getSession()->setFlash($type == 'error' ? 'danger' : $type, $message);
    }
}
