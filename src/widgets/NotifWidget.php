<?php

namespace hesabro\notif\widgets;

use hesabro\notif\models\Notif;
use yii\base\Widget;

class NotifWidget extends Widget
{
    public function run()
    {
        return $this->render('notif', [
            'items' => Notif::findCurrentUserUnSeen(10),
            'count' => Notif::countCurrentUserUnSeen()
        ]);
    }
}
