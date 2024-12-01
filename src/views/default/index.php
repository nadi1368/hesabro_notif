<?php

use hesabro\helpers\widgets\grid\GridView;
use hesabro\notif\models\Notif;
use hesabro\notif\models\NotifSearch;
use hesabro\notif\Module;

/* @var yii\web\View $this */
/* @var NotifSearch $searchModel */
/* @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Module::t('module', 'Notifications');
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss(<<<CSS
.notif-text {
    display: flex;
    text-align: right;
    justify-content: right;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    max-width: 768px;
}
.notif-text > p {
    margin-bottom: unset !important;
}
CSS);
?>
<div class="card">
    <div class="panel-group" id="accordion">
        <div class="card-header d-flex justify-content-between">
            <h4 class="panel-title">
                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion"
                   href="#collapseOne" aria-expanded="false">
                    <i class="far fa-search"></i> <?= Module::t('module', 'Search') ?>
                </a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false">
            <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>
    <div class="card-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'title',
                'description' => [
                    'attribute' => 'description',
                    'value' => fn(Notif $notif) => "<div class='notif-text'>$notif->description</div>",
                    'format' => 'raw'
                ],
                'created_at' => [
                    'attribute' => 'created_by',
                    'value' => fn(Notif $notif) => $notif->created_at ? Yii::$app->jdf::jdate('H:i:s Y/m/d', $notif->created_at) : Module::t('module', 'System'),
                    'format' => 'raw'
                ],
                'created_by' => [
                    'attribute' => 'created_by',
                    'value' => fn(Notif $notif) => $notif->createBy?->fullName ?: '--',
                    'format' => 'raw'
                ]
            ],
        ]); ?>
    </div>
</div>
