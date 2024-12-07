<?php

use hesabro\notif\models\Notif;

/**
 * @var Notif $notif
 */
?>

<div class="card">
    <div class="card-body pb-0">
        <h4><?= $notif->title ?></h4>
        <div class="d-flex align-items-center justify-content-start" style="gap: 12px">
            <h6 class="d-flex align-items-center justify-content-start" style="gap: 4px">
                <i class="far fa-user"></i>
                <?= $notif->createBy?->fullName ?>
            </h6>
            <h6 class="d-flex align-items-center justify-content-start" style="gap: 4px">
                <i class="far fa-calendar"></i>
                <?= Yii::$app->jdf->jdate('Y/m/d H:i', $notif->created_at) ?>
            </h6>
        </div>

        <hr />
        <p><?= $notif->description ?></p>
    </div>
</div>
