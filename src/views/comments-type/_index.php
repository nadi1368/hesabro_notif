<?php

use common\components\Helper;
use common\widgets\TableView;
use common\models\CommentsType;

/* @var $this yii\web\View */
/* @var $model CommentsType */

?>
<div class="card">
    <div class="card-body">
        <?= TableView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'description',
                [
                    'attribute' => 'created',
                    'value' => function (CommentsType $model) {
                        return '<span title="بروز رسانی شده در ' . Yii::$app->jdate->date("Y/m/d  H:i", $model->changed) . '">' . Yii::$app->jdate->date("Y/m/d  H:i", $model->created) . '</span>';
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'creator_id',
                    'value' => function (CommentsType $model) {
                        return '<span title="بروز رسانی شده توسط ' . $model->update->fullName . '">' . $model->creator->fullName . '</span>';
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'userType',
                    'value' => function (CommentsType $model) {
                        return CommentsType::itemAlias('UserType', $model->userType ?: CommentsType::USER_DYNAMIC);
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'users',
                    'value' => function (CommentsType $model) {
                        return $model->usersList;
                    },
                    'format' => 'raw'
                ]
            ]
        ]);
        ?>
    </div>
</div>
