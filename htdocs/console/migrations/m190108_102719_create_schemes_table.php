<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shemes`.
 */
class m190108_102719_create_schemes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('schemes', [
            'id' => $this->primaryKey(),
            'lab_id' => $this->integer()->notNull(),
            'number' => $this->integer()->notNull()
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('schemes');
    }
}
