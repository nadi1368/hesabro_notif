<?php

use hesabro\notif\models\Notif;
use hesabro\notif\Module;
use yii\helpers\Html;

/**
 * @var Notif $notif
 */
?>

<div class="card">
    <div class="card-body py-0">
        <h4><?= $notif->title ?></h4>
        <div class="d-flex align-items-center justify-content-start" style="gap: 12px">
            <h6 class="d-flex align-items-center justify-content-start mb-0" style="gap: 4px">
                <i class="far fa-user"></i>
                <?= $notif->createBy?->fullName ?>
            </h6>
            <h6 class="d-flex align-items-center justify-content-start mb-0" style="gap: 4px">
                <i class="far fa-calendar"></i>
                <?= Yii::$app->jdf->jdate('Y/m/d H:i', $notif->created_at) ?>
            </h6>
        </div>

        <hr />
        <p class="mb-0"><?= $notif->description ?></p>
        <?php if ($notif->link): ?>
            <hr />
            <?= Html::a(
                Html::tag('i', '', ['class' => 'fas fa-external-link-alt']) . Module::t('module', 'Related Link'),
                $notif->link,
                ['class' => 'd-flex align-items-center justify-content-start', 'style' => 'gap: 4px']
            ) ?>
        <?php endif; ?>
    </div>
</div>
