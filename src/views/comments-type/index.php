<?php

use common\models\CommentsType;
use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\grid\GridView;
use common\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CommentsTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Notification System');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comments-type-index card">
    <div class="panel-group m-bot20" id="accordion">
        <div class="card-header d-flex justify-content-between">
            <h4 class="panel-title">
                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion"
                   href="#collapseOne" aria-expanded="false">
                    <i class="far fa-search"></i> جستجو
                </a>
            </h4>
            <div>
                <?= Html::a(Yii::t('app', 'Create'),
                    'javascript:void(0)', [
                        'title' => Yii::t('app', 'Create'),
                        'id' => 'create-payment-period',
                        'class' => 'btn btn-success',
                        'data-size' => 'modal-lg',
                        'data-title' => Yii::t('app', 'Create'),
                        'data-toggle' => 'modal',
                        'data-target' => '#modal-pjax',
                        'data-url' => Url::to(['create']),
                        'data-reload-pjax-container-on-show' => 0,
                        'data-reload-pjax-container' => 'comments-type',
                        'data-handleFormSubmit' => 1,
                        'disabled' => true
                    ]); ?>
            </div>
        </div>
        <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false">
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>
    <div class="card-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => 'true',
            'pjaxSettings' => [
                'options' => [
                    'id' => 'comments-type'
                ]
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'class' => 'kartik\grid\ExpandRowColumn',
                    'expandIcon' => '<span class="fal fa-chevron-down" style="font-size: 13px"></span>',
                    'collapseIcon' => '<span class="fal fa-chevron-up" style="font-size: 13px"></span>',
                    'value' => function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    'detail' => function ($model, $key, $index, $column) {
                        return Yii::$app->controller->renderPartial('_index', [
                            'model' => $model,
                        ]);
                    },
                ],
                'title',
                [
                    'attribute' => 'key',
                    'value' => function (CommentsType $model) {
                        return CommentsType::itemAlias('KeyType', $model->key);
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'is_auto',
                    'value' => function (CommentsType $model) {
                        return Helper::itemAlias('CheckboxIcon',$model->is_auto);
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'sendSms',
                    'value' => function (CommentsType $model) {
                        return Helper::itemAlias('CheckboxIcon',(int)$model->sendSms);
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'sendMail',
                    'value' => function (CommentsType $model) {
                        return Helper::itemAlias('CheckboxIcon',(int)$model->sendMail);
                    },
                    'format' => 'raw'
                ],
                [
                    'class' => 'common\widgets\grid\ActionColumn',
                    'template' => "{update}{delete}{log}",
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return $model->canUpdate() ? Html::a('<span class="fa fa-edit text-primary"></span>',
                                'javascript:void(0)', [
                                    'title' => Yii::t('app', 'Update'),
                                    'data-size' => 'modal-lg',
                                    'data-title' => Yii::t('app', 'Update'),
                                    'data-toggle' => 'modal',
                                    'data-target' => '#modal-pjax',
                                    'data-url' => Url::to(['update', 'id' => $model->id]),
                                    'data-action' => 'edit-ipg',
                                    'data-reload-pjax-container' => 'comments-type',
                                    'data-handleFormSubmit' => 1,
                                    'disabled' => true
                                ]) : '';
                        },
                        'delete' => function ($url, $model, $key) {
                            return $model->canDelete() ? Html::a(Html::tag('span', '', ['class' => "far fa-trash-alt ml-2"]), 'javascript:void(0)',
                                [
                                    'title' => Yii::t('yii', 'Delete'),
                                    'aria-label' => Yii::t('yii', 'Delete'),
                                    'data-reload-pjax-container' => 'comments-type',
                                    'data-pjax' => '0',
                                    'data-url' => Url::to(['delete', 'id' => $model->id]),
                                    'class' => " text-danger p-jax-btn",
                                    'data-title' => Yii::t('yii', 'Delete'),
                                    'data-method' => 'post'

                                ]) : '';
                        },
                        'log' => function ($url, $model, $key) {
                            return Html::a('<span class="fas fa-history text-info"></span>',
                                ['/mongo/log/view-ajax', 'modelId' => $model->id, 'modelClass' => get_class($model)],
                                [
                                    'class' => 'text-secondary showModalButton',
                                    'title' => Yii::t('app', 'Logs'),
                                    'data-size' => 'modal-xl'
                                ]
                            );
                        },
                    ]
                ],
            ],
        ]); ?>
    </div>
</div>
