<?php

namespace hesabro\notif\interfaces;


interface NotifInterface
{
    public function notifUsers(): array;

    public function notifTitle(): string;

    public function notifDescription(): ?string;

    public function notifConditionToSend(): bool;

    public function notifSmsConditionToSend(): bool;

    public function notifSmsDelayToSend(): ?int;

    public function notifEmailConditionToSend(): bool;

    public function notifEmailDelayToSend(): ?int;

    public function notifTicketConditionToSend(): bool;

    public function notifTicketDelayToSend(): ?int;
}