<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CommentsType */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Notification System'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comments-type-create card">
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>
