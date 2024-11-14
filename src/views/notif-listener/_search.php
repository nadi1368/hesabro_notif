<?php

use hesabro\notif\models\NotifListener;
use hesabro\notif\models\NotifListenerSearch;
use hesabro\notif\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model NotifListenerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="comments-type-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
<div class="card-body">
    <div class="row">

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'event')->dropDownList(Module::getInstance()->events) ?>

    <?= $form->field($model, 'userType')->dropDownList(NotifListener::itemAlias('UserType')) ?>

		<div class="col align-self-center text-right">
			<?= Html::submitButton(Module::t('module', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?= Html::resetButton(Module::t('module', 'Reset'), ['class' => 'btn btn-secondary']) ?>
		</div>
	</div>
</div>
    <?php ActiveForm::end(); ?>

</div>
