<?php

namespace hesabro\notif\models;

use yii\db\ActiveRecord;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

class NotifSetting extends ActiveRecord
{
    public static function tableName()
    {
        return '{{notif_settings}}';
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::class,
                'softDeleteAttributeValues' => [
                    'deleted_at' => time(),
                ],
                'replaceRegularDelete' => true,
            ],
        ]);
    }

}