<?php

use yii\db\Migration;

/**
 * Class m230803_115332_data
 */
class m230803_115332_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(file_get_contents(__DIR__ . '/test_db_data.sql'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230803_115332_data cannot be reverted.\n";
        $this->truncateTable('orders');
        $this->truncateTable('users');
        $this->truncateTable('services');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230803_115332_data cannot be reverted.\n";

        return false;
    }
    */
}
