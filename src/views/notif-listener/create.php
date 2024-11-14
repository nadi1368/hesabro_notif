<?php

use hesabro\notif\models\NotifListener;
use hesabro\notif\Module;

/* @var yii\web\View $this */
/* @var NotifListener $model */

$this->title = Module::t('module', 'Create');
$this->params['breadcrumbs'][] = ['label' => Module::t('module', 'Notification System'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comments-type-create card">
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>
