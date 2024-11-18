<?php

namespace hesabro\notif\models;

use hesabro\helpers\traits\ModelHelper;
use hesabro\notif\Module;
use yii\base\Model;

class NotifSettingItem extends Model
{
    use ModelHelper;

    public ?string $event = null;

    public bool $sms = true;

    public bool $email = true;

    public bool $ticket = true;

    public function rules()
    {
        return [
            [['event','sms','email','ticket'], 'required'],
            ['event', 'in', 'range' => array_keys(Module::getInstance()->events)],
            [['sms', 'email', 'ticket'], 'boolean']
        ];
    }

    public function attributeLabels()
    {
        return [
            'event' => Module::t('module', 'Event'),
            'sms' => Module::t('module', 'SMS'),
            'email' => Module::t('module', 'Email'),
            'ticket' => Module::t('module', 'Ticket'),
        ];
    }
}