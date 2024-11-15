<?php

use hesabro\notif\models\NotifListener;
use hesabro\notif\Module;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var yii\web\View $this */
/* @var NotifListener $model */
/* @var ActiveForm $form */


$model->userType = $model->userType ?: NotifListener::USER_DYNAMIC;
?>

<?php $form = ActiveForm::begin(['id' => 'notif-listener-form']); ?>
<div class="notif-listener-form">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'key')->dropDownList(Module::getInstance()->events, ['prompt' => Module::t('module', 'Select')]) ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
            </div>
            <div class="col-12">
                <hr />
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'is_auto', ['options' => ['class' => 'mb-0']])->checkbox() ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'sendSms', ['options' => ['class' => 'mb-0']])->checkbox() ?>
            </div>

            <div class="col-md-4">
                <?= $form->field($model, 'sendMail', ['options' => ['class' => 'mb-0']])->checkbox() ?>
            </div>
            <div class="col-12">
                <hr />
            </div>
            <div class="col-md-12 my-2 d-flex items-center justify-content-start gap-4">
                <label>
                    <input
                        type="radio"
                        name="<?= $model->formName() . '[userType]' ?>"
                        value="<?= NotifListener::USER_DYNAMIC ?>"
                        <?= $model->userType === NotifListener::USER_DYNAMIC ? 'checked' : '' ?>
                    >
                    <span><?= NotifListener::itemAlias('UserType', NotifListener::USER_DYNAMIC) ?></span>
                </label>
                <label>
                    <input
                        type="radio"
                        name="<?= $model->formName() . '[userType]' ?>"
                        value="<?= NotifListener::USER_STATIC ?>"
                        <?= $model->userType === NotifListener::USER_STATIC ? 'checked' : '' ?>
                    >
                    <span><?= NotifListener::itemAlias('UserType', NotifListener::USER_STATIC) ?></span>
                </label>
            </div>

            <div id="select-users" class="col-md-12 <?= $model->userType === NotifListener::USER_STATIC ? 'hide' : '' ?>">
                <?= $form->field($model, 'users')->widget(Select2::class, [
                    'data' => Module::getInstance()->user::userOptions(),
                    'options' => [
                        'placeholder' => 'کاربران',
                        'dir' => 'rtl',
                        'multiple' => true
                    ],
                ]); ?>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>

<?php
ActiveForm::end();
$formName = $model->formName();
$userTypeDynamic = NotifListener::USER_DYNAMIC;
$js = <<<JS
$('input[name="$formName\[userType]"]').on('change', function (e) {
    const selectUsers = $('#select-users')
    if (e.target.value === '$userTypeDynamic') {
        selectUsers.removeClass('hide')
    } else {
        selectUsers.addClass('hide')
        selectUsers.find('select')[0].value = []
        selectUsers.find('.select2-selection__choice').each((_, item) => {
            item.remove()
        })
    }
})
JS;
$this->registerJs($js);
?>

