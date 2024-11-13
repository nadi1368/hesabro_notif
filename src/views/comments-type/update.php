<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CommentsType */

$this->title = Yii::t('app', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Notification System'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="comments-type-update card">
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>
