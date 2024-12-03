<?php

namespace hesabro\notif\controllers;

use Exception;
use hesabro\helpers\traits\AjaxValidationTrait;
use hesabro\notif\models\NotifListener;
use hesabro\notif\models\NotifListenerSearch;
use hesabro\notif\Module;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class ListenerController extends Controller
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
                            'roles' => ['notif-listener/view', 'superadmin'],
                            'actions' => [
                                'index', 'view'
                            ]
                        ],
                        [
                            'allow' => true,
                            'roles' => ['notif-listener/actions', 'superadmin'],
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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $this->group);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'events' => $this->events
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
        $model = new NotifListener([
            'scenario' => NotifListener::SCENARIO_CREATE,
            'group' => $this->group
        ]);
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
            'events' => $this->events
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
            'eventsAll' => $this->events
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
            'message' => Module::t('module', 'Error In Save Info')
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
        $query = NotifListener::find()->andWhere(['id' => $id]);

        if ($this->group) {
            $query->andWhere(['group' => $this->group]);
        }

        /** @var NotifListener $model */
        if ($model = $query->one()) {
            return $model;
        }

        throw new NotFoundHttpException(Module::t('module', 'The requested item does not exist.'));
    }
}
