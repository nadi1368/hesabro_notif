<?php

namespace hesabro\notif\controllers;

use hesabro\notif\models\NotifSearch;
use yii\filters\AccessControl;
use yii\web\Controller;

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
}