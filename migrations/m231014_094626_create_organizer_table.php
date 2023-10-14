<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%organizer}}`.
 */
class m231014_094626_create_organizer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%organizer}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'email' => $this->string(100)->notNull(),
            'phone' => $this->string(20),
        ]);

        $this->createIndex(
            'idx-organizer-name',
            '{{%organizer}}',
            'name'
        );

        $this->createIndex(
            'idx-organizer-email',
            '{{%organizer}}',
            'email'
        );

        $this->createIndex(
            'idx-organizer-phone',
            '{{%organizer}}',
            'phone'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%organizer}}');
    }
}
