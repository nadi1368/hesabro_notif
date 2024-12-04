<?php

use yii\db\Migration;

/**
 * Class m241203_074029_alter_notif_listeners_table
 */
class m241203_074029_alter_notif_listeners_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(hesabro\notif\models\NotifListener::tableName(), 'group', $this->string()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(hesabro\notif\models\NotifListener::tableName(), 'group');
    }
}
