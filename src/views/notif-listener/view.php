<?php

use hesabro\notif\models\NotifListener;
use hesabro\notif\Module;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var yii\web\View $this */
/* @var NotifListener $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Module::t('module', 'Notification System'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comments-type-view card">
	<div class="card-body">
	<?= DetailView::widget([
		'model' => $model,
		'attributes' => [
            'id',
            'creator_id',
            'update_id',
            'title',
            'status',
            'created',
            'changed',
		],
	]) ?>
	</div>
	<div class="card-footer">
		<?= Html::a(Module::t('module', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a(Module::t('module', 'Delete'), ['delete', 'id' => $model->id], [
		'class' => 'btn btn-danger',
		'data' => [
		'confirm' => Module::t('module', 'Are you sure you want to delete this item?'),
		'method' => 'post',
		],
		]) ?>
	</div>
</div>
