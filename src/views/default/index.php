<?php

use hesabro\helpers\widgets\grid\GridView;
use hesabro\notif\models\NotifSearch;
use hesabro\notif\Module;

/* @var yii\web\View $this */
/* @var NotifSearch $searchModel */
/* @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Module::t('module', 'Notifications');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'title',
                'description'
            ],
        ]); ?>
    </div>
</div>
