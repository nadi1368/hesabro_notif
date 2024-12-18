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

    public function rules()
    {
        return [
            [['event','sms','email'], 'required'],
            [['sms', 'email'], 'boolean']
        ];
    }

    public function attributeLabels()
    {
        return [
            'event' => Module::t('module', 'Event'),
            'sms' => Module::t('module', 'SMS'),
            'email' => Module::t('module', 'Email')
        ];
    }
}