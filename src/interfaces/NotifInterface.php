<?php

namespace hesabro\notif\interfaces;


interface NotifInterface
{
    public function notifUsers(string $event): array;

    public function notifTitle(string $event): string;

    public function notifLink(string $event, ?int $userId): ?string;

    public function notifDescription(string $event): ?string;

    public function notifConditionToSend(string $event): bool;

    public function notifSmsConditionToSend(string $event): bool;

    public function notifSmsDelayToSend(string $event): ?int;

    public function notifEmailConditionToSend(string $event): bool;

    public function notifEmailDelayToSend(string $event): ?int;
}