<?php

namespace hesabro\notif\controllers;

use hesabro\hris\Module;
use hesabro\notif\models\Notif;
use hesabro\notif\models\NotifSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new NotifSearch();
        $dataProvider = $searchModel->searchUser($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionView($id)
    {
        $notif = $this->findModel($id);
        $notif->markAsSeen();

        return $this->renderAjax('view', [
            'notif' => $notif
        ]);
    }

    public function findModel($id): Notif
    {
        if ($notif = Notif::find()->own()->andWhere(['_id' => $id])->one()) {
            return $notif;
        }

        throw new NotFoundHttpException(Module::t('module', 'The requested page does not exist.'));
    }
}