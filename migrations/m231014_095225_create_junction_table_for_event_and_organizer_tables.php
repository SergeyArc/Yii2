<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%event_organizer}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%event}}`
 * - `{{%organizer}}`
 */
class m231014_095225_create_junction_table_for_event_and_organizer_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%event_organizer}}', [
            'event_id' => $this->integer(),
            'organizer_id' => $this->integer(),
            'PRIMARY KEY(event_id, organizer_id)',
        ]);

        // creates index for column `event_id`
        $this->createIndex(
            '{{%idx-event_organizer-event_id}}',
            '{{%event_organizer}}',
            'event_id'
        );

        // add foreign key for table `{{%event}}`
        $this->addForeignKey(
            '{{%fk-event_organizer-event_id}}',
            '{{%event_organizer}}',
            'event_id',
            '{{%event}}',
            'id',
            'CASCADE'
        );

        // creates index for column `organizer_id`
        $this->createIndex(
            '{{%idx-event_organizer-organizer_id}}',
            '{{%event_organizer}}',
            'organizer_id'
        );

        // add foreign key for table `{{%organizer}}`
        $this->addForeignKey(
            '{{%fk-event_organizer-organizer_id}}',
            '{{%event_organizer}}',
            'organizer_id',
            '{{%organizer}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%event}}`
        $this->dropForeignKey(
            '{{%fk-event_organizer-event_id}}',
            '{{%event_organizer}}'
        );

        // drops index for column `event_id`
        $this->dropIndex(
            '{{%idx-event_organizer-event_id}}',
            '{{%event_organizer}}'
        );

        // drops foreign key for table `{{%organizer}}`
        $this->dropForeignKey(
            '{{%fk-event_organizer-organizer_id}}',
            '{{%event_organizer}}'
        );

        // drops index for column `organizer_id`
        $this->dropIndex(
            '{{%idx-event_organizer-organizer_id}}',
            '{{%event_organizer}}'
        );

        $this->dropTable('{{%event_organizer}}');
    }
}
