<?php

use yii\helpers\Html;
use common\widgets\grid\GridView;
use common\models\Checks;
?>
<?php if($dataProvider->getKeys()): ?>
<div class="card">
    <div class="card-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}",
            'columns' => [

                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'store_id',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a($model->store->title, ['check-store/view', 'id' => $model->store_id], ['class' => 'target']);
                    }
                ],
                [
                    'attribute' => 'factor_id',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a($model->factor_id, ['factor/view', 'id' => $model->factor_id], ['class' => 'target']);

                    }
                ],
                [
                    'attribute' => 'distribution_id',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->distribution_id ? Html::a($model->distribution->user->fullName, ['customer/view', 'id' => $model->distribution_id], ['class' => 'target']) : '';

                    }
                ],
                [
                    'attribute' => 'bank_id',
                    'value' => function ($model) {
                        return $model->bank->bankname;
                    }
                ],
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return '<label class="badge badge-' . Checks::itemAlias('StatusClass', $model->status) . '">' . Checks::itemAlias('Status', $model->status) . '</label>';
                    }
                ],
                [
                    'attribute' => 'status_check',
                    'value' => function ($model) {
                        return $model->status == Checks::STATUS_RETURN ? Checks::itemAlias('CheckStatus', $model->status_check) : '';
                    },

                ],
                [
                    'attribute' => 'cheek_number',
                    'value' => function ($model) {
                        return Html::a($model->cheek_number, ['view', 'id' => $model->id]);
                    },
                    'format' => 'raw',

                ],
                'shomare_hesab',
                [
                    'attribute' => 'amount',
                    'value' => function ($model) {
                        return number_format((float)$model->amount);
                    }
                ],
                'get_date',
                'recive_date',
                'vosool_date',
                'check_owner',
                [
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a('<span class="ti-receipt"></span>', ['/checks-fallow/view', 'check_id' => $model->id, 'customer_id' => $model->customer_id], ['title' => Yii::t("app", "Checks Fallow"), 'class' => $model->classBtnFallow . ' showModalButton']);
                    }
                ],
                [
                    'label' => 'سند حسابداری',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $doucument_id = $model->documentId;
                        if ($doucument_id > 0) {
                            return Html::a('سند', ['/document/index', 'DocumentSearch[check_id]' => $model->id], ['class' => 'btn btn-success  ', 'title' => 'مشاهده سند']);
                        }

                    }
                ],

            ],
        ]); ?>
    </div>
</div>
<?php endif; ?>


