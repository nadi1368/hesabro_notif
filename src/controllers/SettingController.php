<?php

namespace hesabro\notif\controllers;

use hesabro\notif\models\NotifSetting;
use hesabro\notif\models\NotifSettingItem;
use hesabro\notif\Module;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class SettingController extends Controller
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

    public function actionUpdate()
    {
        $model = NotifSetting::find()->whereUser(\Yii::$app->user->id)->one();
        $model = $model ?: new NotifSetting(['user_id' => \Yii::$app->user->id]);

        if ($this->request->isPost) {
            $model->settings = NotifSettingItem::createMultiple(NotifSettingItem::class);
            NotifSettingItem::loadMultiple($model->settings, $this->request->post());
            $valid = NotifSettingItem::validateMultiple($model->settings);
            if ($valid) {
                $model->save(false);
                Yii::$app->getSession()->setFlash('success', Module::t('module', 'Item Updated Successfully'));
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }
}