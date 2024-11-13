<?php

use yii\helpers\Html;
use common\widgets\TableView;

/* @var $this yii\web\View */
/* @var $model common\models\CustomerComments */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customer Comments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$next_model = $model->nextModel('out');
$pre_model = $model->preModel('out');
?>
<div class="col-md-12">
    <div class="card">
        <div class="card-header with-border">
            <?= $pre_model ? Html::a('<span class="ti-angle-right"></span>', ['view', 'id' => $pre_model->id], ['class' => 'btn btn-success  pull-right', 'title' => 'قبلی']) : '' ?>
            <?= $next_model ? Html::a('<span class="ti-angle-left"></span>', ['view', 'id' => $next_model->id], ['class' => 'btn btn-success  pull-left', 'title' => 'بعدی']) : '' ?>
            <div class="clearfix"></div>
        </div>
        <div class="card-body">
            <?= TableView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'customer_id',
                        'value' => function ($model) {
                            return $model->customer->getLink();
                        },
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'comment',
                        'value' => function($model)
                        {
                            //'comment:ntext',
                            return Yii::$app->formatter->asText($model->comment);
                        },
                        'contentOptions' => ['width' => '300px'],
                    ],

                    [
                        'attribute' => 'moaref_id',
                        'value' => function ($model) {
                            return $model->moaref_id ? Html::a($model->moaref->user->fullName, ['customer/view', 'id' => $model->moaref_id], ['class' => 'text-info']) : '';
                        },
                        'format' => 'raw'
                    ],

                    [
                        'attribute' => 'zamen_id',
                        'value' => function ($model) {
                            $zamen = '';
                            $zamen .= $model->zamen_id ? Html::a($model->zamen->user->fullName, ['customer/view', 'id' => $model->zamen_id], ['class' => 'text-info']) . '<br />' : '';
                            $zamen .= $model->zamen2_id ? Html::a($model->zamen2->user->fullName, ['customer/view', 'id' => $model->zamen2_id], ['class' => 'text-info']) . '<br />' : '';
                            $zamen .= $model->zamen3 ? Html::a($model->zamen3->user->fullName, ['customer/view', 'id' => $model->zamen3], ['class' => 'text-info']) . '<br />' : '';

                            return $zamen;
                        },
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'price_buy',
                        'value' => function ($model) {
                            return number_format((float)$model->price_buy);
                        },
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'pre_payment',
                        'value' => function ($model) {
                            return number_format((float)$model->pre_payment);
                        },
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'remind_check',
                        'value' => function ($model) {
                            return number_format((float)$model->remind_check);
                        },
                        'format' => 'raw'
                    ],
                    [
                        'label' => 'چک های ثبت شده',
                        'value' => function ($model) {
                            return number_format((float)$model->getChecks()->sum('amount'));
                        },
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'created',
                        'value' => function ($model) {
                            return '<span title="بروز رسانی شده در '.Yii::$app->jdate->date("Y/m/d  H:i", $model->changed).'">'.Yii::$app->jdate->date("Y/m/d  H:i", $model->created).'</span>';
                        },
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'creator_id',
                        'value' => function ($model) {
                            return '<span title="بروز رسانی شده توسط '.$model->update->fullName.'">'.$model->creator->fullName.'</span>';
                        },
                        'format' => 'raw'
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>

<div class="col-md-12">
    <?= $this->render('_comments', [
        'model' => $model,
        'comments' => $comments,
    ]) ?>
</div>
<div class="col-md-12">
    <?= $this->render('_factor', [
        'model' => $model,
        'dataProvider' => $providerFactors,
    ]) ?>
</div>

<div class="col-md-12">
    <?= $this->render('_checks', [
        'model' => $model,
        'dataProvider' => $providerChecks,
    ]) ?>
</div>

