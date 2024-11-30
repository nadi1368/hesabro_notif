<?php

use hesabro\notif\models\NotifSetting;
use hesabro\notif\models\NotifSettingItem;
use hesabro\notif\Module;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/**
 * @var NotifSetting $model
 * @var array $events
 */

$this->title = Module::t('module', 'Notification System Settings');
$this->params['breadcrumbs'][] = $this->title;

$form = ActiveForm::begin(['id' => 'user-notif-settings'])
?>
<div class="card mb-0">
    <div class="card-body">
        <div class="row" style="max-width: 800px; margin: auto">
            <?php if (count($events)): ?>
            <?php /** @var NotifSettingItem $event */ ?>
            <?php foreach ($events as $key => $setting): ?>
                <div class="col-12 py-2">
                    <div class="row">
                        <div class="col-6">
                            <?= Module::getInstance()->eventsAll[$setting->event] ?? Module::t('module', 'Unknown') ?>
                            <?= $form->field($setting, "[$key]event")->hiddenInput(['value' => $setting->event])->label(false) ?>
                        </div>
                        <div class="col-2">
                            <?= $form->field($setting, "[$key]sms")->checkbox() ?>
                        </div>
                        <div class="col-2">
                            <?= $form->field($setting, "[$key]email")->checkbox() ?>
                        </div>
                        <div class="col-2">
                            <?= $form->field($setting, "[$key]ticket")->checkbox() ?>
                        </div>
                    </div>
                    <hr/>
                </div>
            <?php endforeach; ?>
                <div class="col-12">
                    <?= Html::submitButton(Module::t('module', 'Update'), ['class' => 'btn btn-success']) ?>
                </div>
            <?php else: ?>
            <div class="col-12 d-flex align-items-center justify-content-center">
                موردی برای این ماژول یافت نشد.
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
