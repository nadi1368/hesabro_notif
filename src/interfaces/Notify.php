<?php

namespace hesabro\notif\interfaces;

interface Notify
{
    public function send(string|array $receptors, string $subject, string $message, int $delay): void;
}