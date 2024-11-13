<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CommentsTypeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="comments-type-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
<div class="card-body">
    <div class="row">
    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'creator_id') ?>

    <?= $form->field($model, 'update_id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'changed') ?>

		<div class="col align-self-center text-right">
			<?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-secondary']) ?>
		</div>
	</div>
</div>
    <?php ActiveForm::end(); ?>

</div>
