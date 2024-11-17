<?php

use hesabro\helpers\widgets\TableView;
use hesabro\notif\models\NotifListener;

/* @var yii\web\View $this */
/* @var NotifListener $model */

echo TableView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'description',
        [
            'attribute' => 'created_at',
            'value' => function (NotifListener $model) {
                return '<span title="بروز رسانی شده در ' . Yii::$app->jdf::jdate('Y/m/d  H:i', $model->updated_at) . '">' . Yii::$app->jdf::jdate('Y/m/d  H:i', $model->created_at) . '</span>';
            },
            'format' => 'raw'
        ],
        [
            'attribute' => 'created_by',
            'value' => function (NotifListener $model) {
                return '<span title="بروز رسانی شده توسط ' . $model->updatedBy->fullName . '">' . $model->createdBy->fullName . '</span>';
            },
            'format' => 'raw'
        ],
        [
            'attribute' => 'userType',
            'value' => function (NotifListener $model) {
                return NotifListener::itemAlias('UserType', $model->userType ?: NotifListener::USER_DYNAMIC);
            },
            'format' => 'raw'
        ],
        [
            'attribute' => 'users',
            'value' => function (NotifListener $model) {
                return $model->usersList;
            },
            'format' => 'raw'
        ]
    ]
]);
