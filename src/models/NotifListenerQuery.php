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

    public function whereUserId(int $userId)
    {
        $this->andWhere("JSON_CONTAINS(". NotifListener::tableName() .".additional_data, JSON_QUOTE('$userId'), '$.users'");

        return $this;
    }

    public function whereUserInclude(int $userId)
    {
        $dynamic = NotifListener::USER_DYNAMIC;
        $static = NotifListener::USER_STATIC;
        $this->where([
            'OR',
            "JSON_EXTRACT(". NotifListener::tableName() .".additional_data, '$.userType') = '$dynamic'",
            [
                'AND',
                "JSON_EXTRACT(". NotifListener::tableName() .".additional_data, '$.userType') = '$static'",
                "JSON_CONTAINS(". NotifListener::tableName() .".additional_data, \"$userId\", '$.users') = 1"
            ]
        ]);

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