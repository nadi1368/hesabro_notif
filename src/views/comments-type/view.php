<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\CommentsType */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Notification System'), 'url' => ['index']];
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
		<?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
		'class' => 'btn btn-danger',
		'data' => [
		'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
		'method' => 'post',
		],
		]) ?>
	</div>
</div>
