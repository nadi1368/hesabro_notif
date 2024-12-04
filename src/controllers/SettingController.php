<?php

namespace hesabro\notif\controllers;

use hesabro\helpers\traits\AjaxValidationTrait;
use hesabro\notif\models\NotifSetting;
use hesabro\notif\models\NotifSettingItem;
use hesabro\notif\Module;
use Yii;
use yii\filters\AccessControl;

class SettingController extends Controller
{
    use AjaxValidationTrait;

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
            $settings = NotifSettingItem::createMultiple(NotifSettingItem::class);
            NotifSettingItem::loadMultiple($settings, $this->request->post());

            if (NotifSettingItem::validateMultiple($settings)) {
                $updatedEvents = array_map(fn(NotifSettingItem $item) => $item->event, $settings);
                $model->settings = array_merge($settings, array_filter($model->settings, fn(NotifSettingItem $item) => !in_array($item->event, $updatedEvents)));

                $model->save(false);
                return $this->asJson([
                    'success' => true,
                    'msg' => Module::t('module', 'Item Updated Successfully')
                ]);
            }

            $this->performAjaxMultipleValidation($settings);
            return $this->asJson([
                'success' => false,
                'msg' => Module::t('module', 'Error In Save Info')
            ]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
            'events' => array_values(NotifSetting::getRelatedSettings(array_keys($this->events))),
            'eventsAll' => $this->events
        ]);
    }
}