<?php

namespace hesabro\notif\interfaces;


interface NotifInterface
{
    public function notifUsers(): array;

    /** return title of notif */
    public function notifTitle(): string;

    /** return custom description for notif if null defaultDescription should be apply  */
    public function notifDescription(): ?string;


    /** a condition in order to that behavior know when notif should be send */
    public function notifConditionToSend(): bool;

    /** a condition in order to that behavior know when notif sms should be send */
    public function notifSmsConditionToSend(): bool;
    
    /** a condition in order to that behavior know when notif email should be send */
    public function notifEmailConditionToSend(): bool;

    public function notifEmailDelayToSend(): ?int;

    public function notifSmsDelayToSend(): ?int;
}