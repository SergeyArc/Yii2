<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%event}}`.
 */
class m231014_093315_create_event_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%event}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'date' => $this->date(),
            'description' => $this->text(),
        ]);

        $this->createIndex(
            'idx-event-title',
            '{{%event}}',
            'title'
        );

        $this->createIndex(
            'idx-event-date',
            '{{%event}}',
            'date'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%event}}');
    }
}
