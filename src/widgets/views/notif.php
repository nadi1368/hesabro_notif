<?php

use hesabro\notif\models\Notif;
use hesabro\notif\Module;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;

/** @var Notif[] $items */
/** @var int $count */
/** @var View $this */

Pjax::begin(['id' => "notif-list-pjax", 'options' => ['class' => 'd-flex flex-column justify-content-center ml-2']])
?>

<li class="nav-item dropdown <?= $count > 0 ? 'notif-pulse' : '' ?>">
        <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="far fa-bell font-18"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-left mailbox animated bounceInDown">
            <span class="with-arrow"><span class="bg-primary"></span></span>
            <ul class="list-style-none">
                <?php if ($count > 0): ?>
                    <li>
                        <div class="drop-title border-bottom text-center"><?= "$count اعلان جدید" ?></div>
                    </li>
                <?php endif; ?>
                <li>
                    <div class="message-center notifications">
                        <?php if ($count == 0): ?>
                            <div class="drop-title border-bottom text-center text-muted">
                                اعلان جدیدی وجود ندارد
                            </div>
                        <?php endif; ?>
                        <!-- Message -->
                        <?php foreach ($items as $item): ?>
                            <?=
                            Html::a(
                                Html::tag('span', '', ['class' => "far fa-bell mx-2 text-danger"]) .
                                Html::tag('span',
                                    Html::tag('h5', $item->title, ['class' => 'text-bold']) .
                                    Html::tag('span', $item->createBy?->fullName, ['class' => 'mail-desc']) .
                                    Html::tag('span', Yii::$app->jdf->jdate('Y/m/d H:i', $item->created_at), ['class' => 'time text-muted'])
                                    , [
                                        'class' => 'mail-contnet pr-0 pl-2'
                                    ]),
                                'javascript:void(0)', [
                                'title' => Module::t('module', 'Details'),
                                'id' => 'view-announce-btn',
                                'class' => 'message-item',
                                'data-size' => 'modal-lg',
                                'data-title' => $item->title,
                                'data-toggle' => 'modal',
                                'data-target' => '#modal-pjax',
                                'data-url' => Module::createUrl('default/view', ['id' => ((string) $item->_id)]),
                                'data-reload-pjax-container-on-show' => 1,
                                'data-reload-pjax-container' => 'notif-list-pjax',
                                'data-handleFormSubmit' => 0,
                                'disabled' => true
                            ]);
                            ?>
                        <?php endforeach; ?>
                    </div>
                </li>
                <li>
                    <a class="nav-link text-center mb-1 text-dark d-flex align-items-center justify-content-center"
                       href="<?= Module::createUrl('/') ?>" data-pjax="0">
                        <strong>تمام اعلان‌های من</strong>
                        <i class="fa fa-angle-left ml-2"></i>
                    </a>
                </li>
            </ul>
        </div>
    </li>

<?php
Pjax::end();
$this->registerCss(<<<CSS
.notif-pulse {
    position: relative;
}

.notif-pulse:after {
    content: '';
    position: absolute;
    top: 6px;
    right: 2px;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #e11d48;
    box-shadow: 0 0 1px 1px #0000001a;
    animation: notif-pulse-animation 1500ms infinite;
}
@keyframes notif-pulse-animation {
    0% {
        box-shadow: 0 0 0 0 rgba(190, 18, 60, 0.4);
    }
    100% {
        box-shadow: 0 0 0 12px rgba(190, 18, 60, 0);
    }
}
CSS);
?>
