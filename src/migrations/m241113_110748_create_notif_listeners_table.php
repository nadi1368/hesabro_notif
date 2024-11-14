<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notif_settings}}`.
 */
class m241113_110748_create_notif_listeners_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notif_listeners}}', [
            'id' => $this->integer()->unsigned()->notNull(),
            'title' => $this->string(),
            'description' => $this->text(),
            'event' => $this->string(),
            'additional_data' => $this->json(),
            'updated_by' => $this->integer()->unsigned(),
            'created_by' => $this->integer()->unsigned(),
            'deleted_at' => $this->integer()->unsigned(),
            'updated_at' => $this->integer()->unsigned(),
            'created_at' => $this->integer()->unsigned(),
            'slave_id' => $this->integer()->unsigned()->notNull(),
        ]);

        $this->addPrimaryKey('PRIMARYKEY', '{{%notif_listeners}}', ['id', 'slave_id']);
        $this->alterColumn("{{%notif_listeners}}", 'id', $this->integer()->unsigned()->notNull()->append('AUTO_INCREMENT'));
        $this->createIndex('idx_key', '{{%notif_listeners}}', ['key']);
        $this->createIndex('idx_slave_id', '{{%notif_listeners}}', ['slave_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%notif_listeners}}');
    }
}
