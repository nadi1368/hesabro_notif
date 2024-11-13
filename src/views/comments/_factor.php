<?php

use common\models\Factor;
use common\models\FactorType;
use common\widgets\grid\GridView;
use yii\helpers\Html;

?>
<?php if($dataProvider->getKeys()): ?>
<div class="card">
    <div class="card-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}",
            'rowOptions' => function ($model, $index, $widget, $grid) {
                if (!$model->confirm) {
                    return ['class' => 'danger'];
                } elseif ($model->review) {
                    return ['class' => 'success'];
                } else {
                    return ['class' => 'warning'];
                }
            },
            'columns' => [
                [
                    'attribute' => 'id',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a($model->id, ['factor/view', 'id' => $model->id], ['class' => 'target']);
                    }
                ],

                'factor_date',
                [
                    'attribute' => 'remind',
                    'value' => function ($model) {
                        return Html::a($model->getRemind(), ['/account/ajax-cycle', 'DocumentDetailsSearch[a_id]' => $model->account_id], ['title' => 'مشاهده گردش حساب ' . $model->account->full_name, 'class' => 'showModalButton']);
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'buy_sale',
                    'value' => function ($model) {
                        $data = Factor::itemAlias('FactorOut', $model->buy_sale);
                        if ($model->buy_sale == Factor::T_ORDER_OUT && $model->in_id) {
                            $data .= '<br />' . Html::a('#' . $model->in_id, ['factor-buy/view', 'id' => $model->in_id]);
                        }
                        return $data;
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'factor_type_id',
                    'value' => function ($model) {
                        return FactorType::itemAlias('List', $model->factor_type_id);
                    },
                ],
                [
                    'attribute' => 'payment',
                    'value' => function ($model) {
                        return number_format((float)$model->payment);
                    }
                ],
                [
                    'attribute' => 'zamen_id',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->zamen_id) {
                            return Html::a($model->zamen->user->fullName, ['customer/view', 'id' => $model->zamen_id]);
                        }
                        return '<i class="text-danger ti-minus"></i>';
                    }
                ],

                [
                    'attribute' => 'moaref_id',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->moaref_id) {
                            return Html::a($model->moaref->user->fullName, ['customer/view', 'id' => $model->moaref_id]);
                        }
                        return '<i class="text-danger ti-minus"></i>';
                    }
                ],
                [
                    'attribute' => 'seller_id',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a($model->seller->name, ['seller/view', 'id' => $model->seller_id]);
                    },
                ],

                [
                    'attribute' => 'created',
                    'value' => function ($model) {
                        return Yii::$app->jdate->date("Y/m/d H:i:s", $model->created);
                    }
                ],
                [
                    'attribute' => 'debt',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->debt == 1 ? 'اقساط' : 'نقد';

                    }
                ],
            ],
        ]); ?>
    </div>
</div>
<?php endif; ?>
