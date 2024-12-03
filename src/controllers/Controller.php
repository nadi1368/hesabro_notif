<?php

namespace hesabro\notif\controllers;

use yii\web\Controller as WebController;

class Controller extends WebController
{
    protected ?string $group = null;

    protected array $events = [];
}
