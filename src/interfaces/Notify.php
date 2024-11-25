<?php

namespace hesabro\notif\interfaces;

use yii\base\Model;

interface Notify
{
    public static function send(string|array $receptors, string $subject, string $message, int $delay, string $modelClass, ?int $modelId): void;
}