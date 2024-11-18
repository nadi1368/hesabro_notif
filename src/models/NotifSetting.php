<?php

namespace hesabro\notif\models;

use hesabro\changelog\behaviors\LogBehavior;
use hesabro\errorlog\behaviors\TraceBehavior;
use hesabro\helpers\behaviors\JsonAdditional;
use hesabro\notif\Module;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $user_id
 * @property array $additional_data
 * @property int $updated_at
 * @property bool $created_at
 * @property bool $updated_by
 * @property bool $created_by
 */
class NotifSetting extends ActiveRecord
{
    const TYPE_SMS = 'sms';

    const TYPE_EMAIL = 'email';

    const TYPE_TICKET = 'ticket';

    public mixed $settings = [];

    public static function tableName()
    {
        return '{{%notif_settings}}';
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'TraceBehavior' => [
                'class' => TraceBehavior::class,
                'ownerClassName' => self::class
            ],
            'LogBehavior' => [
                'class' => LogBehavior::class,
                'ownerClassName' => self::class,
                'saveAfterInsert' => true
            ],
            'TimestampBehavior' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at'
            ],
            'BlameableBehavior' => [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by'
            ],
            'JsonAdditional' => [
                'class' => JsonAdditional::class,
                'ownerClassName' => self::class,
                'fieldAdditional' => 'additional_data',
                'AdditionalDataProperty' => [
                    'settings' => 'ClassArray::' . NotifSettingItem::class
                ],
            ],
        ]);
    }

    public function attributeLabels()
    {
        return [
            'user_id' => Module::t('module', 'User'),
        ];
    }

    public static function find()
    {
        return new NotifSettingQuery(get_called_class());
    }

    public static function updateUserEvent(int|string $userId, string $event, array $settings): bool
    {
        /** @var NotifSetting $setting */
        $setting = self::find()->whereUser($userId)->one();
        $setting = $setting ?: new NotifSetting(['user_id' => $userId]);
        $setting->settings = $setting->settings ?: [];

        $settingItem = current(array_filter($setting->settings, fn(NotifSettingItem $item) => $item->event === $event));
        $index = $settingItem ? array_search($settingItem, $setting->settings) : false;

        if($index !== false) {
            $setting->settings[$index] = [
                ...$setting->settings[$index],
                ...$settings,
                'event' => $event
            ];
        } else {
            $setting->settings[] = [
                ...$settings,
                'event' => $event
            ];
        }

        return $setting->save();
    }

    public static function findUserEvent(int|string $userId, string $event) : ?NotifSettingItem
    {
        /** @var NotifSetting $setting */
        $setting = self::find()->whereUser($userId)->one();

        if (!$setting) {
            return null;
        }

        $setting->settings = $setting->settings ?: [];

        return current(array_filter($setting->settings, fn(NotifSettingItem $item) => $item->event === $event)) ?: null;
    }

    public static function canUserEvent(
        int|string $userId,
        string $event,
        string $type,
        bool $default = false
    ) : bool
    {
        /** @var NotifSettingItem|null $setting */
        $setting = self::findUserEvent($userId, $event);

        if (!$setting) {
            return $default;
        }

        return $setting[$type];
    }

    public function loadDefaultValues($skipIfSet = true)
    {
        parent::loadDefaultValues($skipIfSet);

        $eventKeys = array_keys(Module::getInstance()->events);
        $defaultSettings = array_combine($eventKeys, array_map(fn($event) => new NotifSettingItem([
            'event' => $event,
            'sms' => false,
            'email' => false,
            'ticket' => false,
        ]), $eventKeys));

        $existenceSettings = array_combine(array_map(fn($item) => $item->event, $this->settings), $this->settings);
        $this->settings = array_values(array_merge($defaultSettings, $existenceSettings));

        return $this;
    }
}
