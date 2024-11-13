<?php

use yii\helpers\Html;
use common\widgets\grid\GridView;
use common\models\CustomerComments;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CustomerCommentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Customer Comments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="panel-group m-bot20" id="accordion">
        <div class="card-header d-flex justify-content-between">
            <h4 class="panel-title">
                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion"
                   href="#collapseOne" aria-expanded="false">
                    <i class="far fa-search"></i> جستجو
                </a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false">
            <?php echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>
    <div class="card-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                //'id',
                [
                    'attribute' => 'type',
                    "label" => $searchModel->getAttributeLabel('type'),
                    'value' => function ($model) {

                        return CustomerComments::itemAlias('Type', $model['type']);
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'customer_id',
                    "label" => $searchModel->getAttributeLabel('customer_id'),
                    'value' => function ($sql_model) {
                        $model = new CustomerComments();
                        $model->customer_id = $sql_model['customer_id'];
                        return $model->customer->getLink();
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'comment',
                    "label" => $searchModel->getAttributeLabel('comment'),
                    'value' => function ($model) {
                        //'comment:ntext',
                        return Yii::$app->formatter->asText($model['comment']);
                    },
                    'contentOptions' => ['width' => '300px'],
                ],

                [
                    'attribute' => 'moaref_id',
                    "label" => $searchModel->getAttributeLabel('moaref_id'),
                    'value' => function ($sql_model) {
                        $model = new CustomerComments();
                        $model->moaref_id = $sql_model['moaref_id'];

                        return $model->moaref_id ? $model->moaref->getLink() : '';
                    },
                    'format' => 'raw'
                ],

                [
                    'attribute' => 'zamen_id',
                    "label" => $searchModel->getAttributeLabel('zamen_id'),
                    'value' => function ($sql_model) {

                        $model = new CustomerComments();
                        $model->zamen_id = $sql_model['zamen_id'];
                        $model->zamen2_id = $sql_model['zamen2_id'];
                        $model->zamen3_id = $sql_model['zamen3_id'];

                        $zamen = '';
                        $zamen .= $model->zamen_id ? $model->zamen->getLink() . '<br />' : '';
                        $zamen .= $model->zamen2_id ? $model->zamen2->getLink() . '<br />' : '';
                        $zamen .= $model->zamen3_id ? $model->zamen3->getLink() . '<br />' : '';

                        return $zamen;
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'price_buy',
                    "label" => $searchModel->getAttributeLabel('price_buy'),
                    'value' => function ($model) {
                        return number_format((float)$model['price_buy']);
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'pre_payment',
                    "label" => $searchModel->getAttributeLabel('pre_payment'),
                    'value' => function ($model) {
                        return number_format((float)$model['pre_payment']);
                    },
                    'format' => 'raw'
                ],
                [
                    "label" => 'مبلغ مجاز',
                    'value' => function ($model) {
                        return number_format((float)($model['price_buy'] - $model['pre_payment']));
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'remind_check',
                    "label" => $searchModel->getAttributeLabel('remind_check'),
                    'value' => function ($model) {
                        return number_format((float)$model['remind_check']);
                    },
                    'format' => 'raw'
                ],
                [
                    'label' => 'چک های ثبت شده',
                    'attribute' => 'totalCheckAmount',
                    'value' => function ($model) {
                        return number_format((float)$model['total_check_amount']);
                    },
                    'format' => 'raw'
                ],
//                    [
//                        'label' => 'لینک',
//                        'value' => function ($model) {
//                            return $model->getLink();
//                        },
//                        'format' => 'raw'
//                    ],
                [
                    'attribute' => 'created',
                    "label" => $searchModel->getAttributeLabel('created'),
                    'value' => function ($model) {
                        return '<span title="بروز رسانی شده در ' . Yii::$app->jdate->date("Y/m/d  H:i", $model['changed']) . '">' . Yii::$app->jdate->date("Y/m/d  H:i", $model['created']) . '</span>';
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'creator_id',
                    "label" => $searchModel->getAttributeLabel('creator_id'),
                    'value' => function ($sql_model) {
                        $model = new CustomerComments();
                        $model->creator_id = $sql_model['creator_id'];
                        $model->update_id = $sql_model['update_id'];
                        return '<span title="بروز رسانی شده توسط ' . $model->update->fullName . '">' . $model->creator->fullName . '</span>';
                    },
                    'format' => 'raw'
                ],
                [
                    'class' => 'common\widgets\grid\ActionColumn',
                    'template' => '{comments}{view}',
                    'buttons' => [
                        'comments' => function ($url, $sql_model, $key) {
                            $key = $sql_model['id'];
                            $model = new CustomerComments();

                            return Html::a('<span class="ti-comment-alt"></span>', ['/ticket/create', 'title' => 'سفارش #' . $key, 'class_name' => get_class($model), 'class_id' => $key, 'link' => 'comments/view'], [
                                'title' => 'کامنت',
                                'class' => 'text-success showModalButton'
                            ]);
                        },
                        'view' => function ($url, $sql_model, $key) {
                            $key = $sql_model['id'];
                            return Html::a('<span class="ti-eye grid-btn grid-btn-view"></span>', ['view', 'id' => $key], [
                                'title' => Yii::t('yii', 'View'),
                                'class' => 'target'
                            ]);
                        },
                    ]
                ],

            ],
        ]); ?>
    </div>
</div>