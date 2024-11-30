<?php

namespace hesabro\notif\models;

use hesabro\changelog\behaviors\LogBehavior;
use hesabro\errorlog\behaviors\TraceBehavior;
use hesabro\helpers\behaviors\JsonAdditional;
use hesabro\notif\Module;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * @property string title
 * @property string event
 * @property string description
 * @property string userType
 * @property int[] users
 * @property bool sms
 * @property bool email
 * @property int $updated_at
 * @property bool $created_at
 * @property bool $updated_by
 * @property bool $created_by
 *
 * @property-read object $createdBy
 * @property-read object $updatedBy
 * @property-read string $usersList
 * @property-read string $usersModel
 */
class NotifListener extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';

    const SCENARIO_UPDATE = 'update';

    const USER_STATIC = 'static';

    const USER_DYNAMIC = 'dynamic';

    public bool $sms = false;

    public bool $email = false;

    public bool $ticket = false;

    public ?string $userType = null;

    public mixed $users = [];

    public static function tableName(): string
    {
        return '{{%notif_listeners}}';
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
            'SoftDeleteBehavior' => [
                'class' => SoftDeleteBehavior::class,
                'softDeleteAttributeValues' => [
                    'deleted_at' => time(),
                ],
                'restoreAttributeValues' => [
                    'deleted_at' => null,
                ],
                'replaceRegularDelete' => true,
                'invokeDeleteEvents' => false
            ],
            'JsonAdditional' => [
                'class' => JsonAdditional::class,
                'ownerClassName' => self::class,
                'fieldAdditional' => 'additional_data',
                'AdditionalDataProperty' => [
                    'userType' => 'String',
                    'users' => 'IntegerArray',
                    'sms' => 'Boolean',
                    'email' => 'Boolean',
                    'ticket' => 'Boolean'
                ],
            ],
        ]);
    }

    public function beforeValidate()
    {
        if ($this->userType === self::USER_DYNAMIC) {
            $this->users = [];
        }

        return parent::beforeValidate();
    }

    public function rules()
    {
        $userStaticType = self::USER_STATIC;
        return array_merge(parent::rules(), [
            [['title', 'event', 'userType'], 'required'],
            [['title', 'event', 'description'], 'string'],
            ['userType', 'in', 'range' => [self::USER_DYNAMIC, self::USER_STATIC]],
            ['event', 'in', 'range' => Module::getInstance()->eventsKey],
            [
                'users',
                'required',
                'when' => fn(self $model) => $model->userType === self::USER_STATIC,
                'whenClient' => "function() { return $('input[name=\"NotifListener[userType]\"]:checked').val() === '$userStaticType' }"
            ]
        ]);
    }

    public function attributeLabels()
    {
        return [
            'title' => Module::t('module', 'Title'),
            'event' => Module::t('module', 'Event'),
            'description' => Module::t('module', 'Description'),
            'userType' => Module::t('module', 'User Type'),
            'users' => Module::t('module', 'Users'),
            'sms' => Module::t('module', 'SMS'),
            'email' => Module::t('module', 'Email'),
            'ticket' => Module::t('module', 'Ticket'),
            'created_at' => Module::t('module', 'Created At'),
            'updated_at' => Module::t('module', 'Updated At'),
            'created_by' => Module::t('module', 'Created By'),
            'updated_by' => Module::t('module', 'Updated By'),
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['title', 'event', 'description', 'userType', 'users', 'sms', 'email', 'ticket'],
            self::SCENARIO_UPDATE => ['title', 'event', 'description', 'userType', 'users', 'sms', 'email', 'ticket'],
        ];
    }

    public static function find()
    {
        return (new NotifListenerQuery(get_called_class()))->notDeleted();
    }

    public function getCreatedBy(): ActiveQuery
    {
        return $this->hasOne(Module::getInstance()->user, ['id' => 'created_by']);
    }

    public function getUpdatedBy(): ActiveQuery
    {
        return $this->hasOne(Module::getInstance()->user, ['id' => 'updated_by']);
    }

    public function canUpdate()
    {
        return true;
    }

    public function canDelete()
    {
        return true;
    }

    public static function itemAlias($type, $code = null)
    {
        $items = [
            'UserType' => [
                self::USER_DYNAMIC => Module::t('module', 'Event User'),
                self::USER_STATIC => Module::t('module', 'Select User')
            ],
            'Events' => Module::getInstance()->eventsAll
        ];

        return isset($code) ? ($items[$type][$code] ?? false) : ($items[$type] ?? false);
    }

    public function getUsersModel(): ActiveQuery
    {
        return $this->hasMany(Module::getInstance()->user, ['id' => 'users']);
    }

    public function getUsersList(): string
    {
        $users = Module::getInstance()->user::find()->where(['id' => is_array($this->users) ? $this->users : [$this->users]])->all();

        return implode('', array_map(fn($user) => '<label class="badge badge-info mr-2 mb-2">' . $user->fullName . ' (' . $user->email . ')' . '</label>', $users));
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert && $this->userType === self::USER_STATIC) {
            foreach (($this->users ?: []) as $user) {
                $event = NotifSetting::findUserEvent($user, $this->event);

                if (!$event) {
                    NotifSetting::updateUserEvent($user, $this->event, [
                        'sms' => $this->sms,
                        'email' => $this->email,
                        'ticket' => $this->ticket,
                    ]);
                }
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }
}
