<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\MaskedInput;
use kartik\select2\Select2;
use common\models\User;
use common\models\CustomerComments;

/* @var $this yii\web\View */
/* @var $model backend\models\CustomerCommentsSearch */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="customer-comments-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2 date-input">
                <?php echo $form->field($model, 'type')->dropDownList(CustomerComments::itemAlias('Type'), ['prompt'=>Yii::t('app','Select...')]) ?>
            </div>
            <div class="col-md-2 date-input">
                <?php echo $form->field($model, 'fromDate')->widget(MaskedInput::class, [
                    'mask' => '9999/99/99',
                    'options' => [
                        'autocomplete' => 'off'
					]

                ]) ?>
            </div>
            <div class="col-md-2 date-input">
                <?php echo $form->field($model, 'toDate')->widget(MaskedInput::class, [
                    'mask' => '9999/99/99',
                    'options' => [
                        'autocomplete' => 'off'
					]

                ]) ?>
            </div>

            <div class="col-md-2">
                <?php echo $form->field($model, 'fromPriceBuy')->textInput([ 'class'=>'form-control currency-format']) ?>
            </div>
            <div class="col-md-2">
                <?php echo $form->field($model, 'toPriceBuy')->textInput([ 'class'=>'form-control currency-format']) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'creator_id')->widget(Select2::class, [
                    'data' => User::userOptions(),
                    'options' => [
                        'placeholder' => '-',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]); ?>
            </div>
            <div class="col-md-2">
                <?php echo $form->field($model, 'moreThan')->checkbox() ?>
            </div>
            <div class="col align-self-center text-right">
                <?= Html::submitButton(Yii::t("app", "Search"), ['class' => 'btn btn-primary ']) ?>

                <?= Html::resetButton(Yii::t("app", "Reset"), ['class' => 'btn btn-secondary ']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<div class="clearfix"></div>
