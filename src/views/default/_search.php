<?php

use hesabro\notif\models\NotifSearch;
use hesabro\notif\Module;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model NotifSearch */
/* @var ActiveForm $form */
?>

<?php $form = ActiveForm::begin(['action' => ['index'], 'method' => 'get']); ?>
<div class="card-body">
    <div class="row">
        <div class="col-12 col-md-3">
<!--            --><?php //= $form->field($model, 'event')->dropDownList(Module::getInstance()->eventsAll, ['prompt' => Module::t('module', 'Select')]) ?>
        </div>
        <div class="col-12 col-md-9 align-self-center text-right">
            <?= Html::submitButton(Module::t('module', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Module::t('module', 'Reset'), ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
