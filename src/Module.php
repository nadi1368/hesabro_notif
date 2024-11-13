<?php

namespace hesabro\notif;

use Exception;
use hesabro\notif\interfaces\Notify;
use Yii;
use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    public bool $enable = true;

    public ?string $websiteName = null;

    public ?string $user = null;

    public ?string $sms = null;

    public ?string $email = null;

    public ?string $clientComponent = null;

    public function init()
    {
        parent::init();

        if (!($this->sms instanceof Notify)) {
            throw new Exception('sms attribute in Notif module listener must be instance of Notify', 500);
        }

        if (!($this->email instanceof Notify)) {
            throw new Exception('email attribute in Notif module, must be instance of Notify', 500);
        }
    }

    public function getClientId(): int
    {
        if ($this->clientComponent) {
            return Yii::$app->{$this->clientComponent}->id;
        }

        return 1;
    }
}
