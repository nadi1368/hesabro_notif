<?php

use hesabro\helpers\widgets\grid\GridView;
use hesabro\notif\models\NotifListener;
use hesabro\notif\models\NotifListenerSearch;
use hesabro\notif\Module;
use yii\bootstrap4\ButtonDropdown;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var yii\web\View $this */
/* @var NotifListenerSearch $searchModel */
/* @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Module::t('module', 'Notification System Settings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notif-listener-index card">
    <div class="panel-group m-bot20" id="accordion">
        <div class="card-header d-flex justify-content-between">
            <h4 class="panel-title">
                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion"
                   href="#collapseOne" aria-expanded="false">
                    <i class="far fa-search"></i> <?= Module::t('module', 'Search') ?>
                </a>
            </h4>
            <div>
                <?= Html::a(Module::t('module', 'Create') . ' ' . Module::t('module', 'Settings'),
                    'javascript:void(0)', [
                        'title' => Module::t('module', 'Create') . ' ' . Module::t('module', 'Notification System Settings'),
                        'id' => 'create-payment-period',
                        'class' => 'btn btn-success',
                        'data-size' => 'modal-lg',
                        'data-title' => Module::t('module', 'Create') . ' ' . Module::t('module', 'Notification System Settings'),
                        'data-toggle' => 'modal',
                        'data-target' => '#modal-pjax',
                        'data-url' => Url::to(['create']),
                        'data-reload-pjax-container-on-show' => 0,
                        'data-reload-pjax-container' => 'notif-listeners',
                        'data-handleFormSubmit' => 1,
                        'disabled' => true
                    ]); ?>
            </div>
        </div>
        <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false">
            <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>
    <div class="card-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'pjax' => 'true',
            'pjaxSettings' => [
                'options' => [
                    'id' => 'notif-listeners'
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
                    'attribute' => 'event',
                    'value' => function (NotifListener $model) {
                        return NotifListener::itemAlias('Events', $model->event);
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'sms',
                    'value' => function (NotifListener $model) {
                        return Yii::$app->helper::itemAlias('CheckboxIcon', (int)$model->sms);
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'email',
                    'value' => function (NotifListener $model) {
                        return Yii::$app->helper::itemAlias('CheckboxIcon',(int) $model->email);
                    },
                    'format' => 'raw'
                ],
                [
                    'class' => 'common\widgets\grid\ActionColumn',
                    'contentOptions' => ['style' => 'width:100px;text-align:left;'],
                    'template' => '{group}',
                    'buttons' => [
                        'group' => function ($url, NotifListener $model, $key) {
                            $items = [];

                            if($model->canUpdate()) {
                                $items[] = [
                                    'label' => Html::tag('span', ' ', ['class' => 'fa fa-pen']) . ' ' . Module::t('module', 'Update'),
                                    'url' => 'javascript:void(0)',
                                    'encode' => false,
                                    'linkOptions' => [
                                        'title' => Module::t('module', 'Update') . ' ' . Module::t('module', 'Notification System Settings'),
                                        'data-size' => 'modal-lg',
                                        'data-title' => Module::t('module', 'Update') . ' ' . Module::t('module', 'Notification System Settings'),
                                        'data-toggle' => 'modal',
                                        'data-target' => '#modal-pjax',
                                        'data-url' => Url::to(['update', 'id' => $model->id]),
                                        'data-action' => 'edit-ipg',
                                        'data-reload-pjax-container' => 'notif-listeners',
                                        'data-reload-pjax-container-on-show' => 0,
                                    ]
                                ];
                            }

                            if($model->canDelete())
                            {
                                $items[] = [
                                    'label' =>  Html::tag('span', '', ['class' => 'fa fa-trash-alt']) .' '. Module::t('module', 'Delete'),
                                    'url' => 'javascript:void(0)',
                                    'encode' => false,
                                    'linkOptions' => [
                                        'data-confirm' => Module::t('module', 'Are you sure you want to delete this item?'),
                                        'title' => Module::t('module', 'Delete'),
                                        'aria-label' => Module::t('module', 'Delete'),
                                        'data-reload-pjax-container' => 'notif-listeners',
                                        'data-pjax' => '0',
                                        'data-url' => Url::to(['delete', 'id' => $model->id]),
                                        'class' => " text-danger p-jax-btn",
                                        'data-title' => Module::t('module', 'Delete'),
                                        'data-method' => 'post'
                                    ],
                                ];
                            }

                            $items[] = [
                                'label' => Html::tag('span', ' ', ['class' => 'fa fa-history']) .' '. Module::t('module', 'Log'),
                                'url' => ['/change-log/default/view-ajax', 'modelId' => $model->id, 'modelClass' => NotifListener::class],
                                'encode' => false,
                                'linkOptions' => [
                                    'title' => Module::t('module', 'Log'),
                                    'class' => 'showModalButton',
                                    'data-size' => 'modal-xxl',
                                ],
                            ];

                            return ButtonDropdown::widget([
                                'buttonOptions' => ['class' => 'btn btn-info btn-md dropdown-toggle', 'style' => 'padding: 3px 7px !important;', 'title' => Module::t('module', 'Actions')],
                                'encodeLabel' => false,
                                'label' => '<i class="far fa-list mr-1"></i>',
                                'options' => ['class' => 'float-right'],
                                'dropdown' => [
                                    'items' => $items,
                                ],
                            ]);
                        },
                    ],
                ],
            ],
        ]); ?>
    </div>
</div>
