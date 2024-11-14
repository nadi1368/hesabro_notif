<?php

use hesabro\notif\models\NotifListener;
use hesabro\notif\Module;

/* @var yii\web\View $this */
/* @var NotifListener $model */

$this->title = Module::t('module', 'Update');
$this->params['breadcrumbs'][] = ['label' => Module::t('module', 'Notification System'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Module::t('module', 'Update');
?>
<div class="comments-type-update card">
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>
