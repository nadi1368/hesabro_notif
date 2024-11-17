<?php

namespace hesabro\notif\models;

use yii\db\ActiveQuery;
use yii2tech\ar\softdelete\SoftDeleteQueryBehavior;

/**
 * @mixin SoftDeleteQueryBehavior
 */
class NotifListenerQuery extends ActiveQuery
{
    public function behaviors()
    {
        return [
            'softDelete' => [
                'class' => SoftDeleteQueryBehavior::class,
            ],
        ];
    }

    public function whereUserType(string $userType)
    {
        $this->andWhere("JSON_EXTRACT(". NotifListener::tableName() .".additional_data, '$.userType') = '$userType'");

        return $this;
    }

    public function andFilterUserType(?string $userType)
    {
        if ($userType) {
            $this->whereUserType($userType);
        }

        return $this;
    }
}