<?php

use yii\db\Schema as Schema;
use yii\db\Migration;

class m170404_195916_page_list extends Migration
{
    public function up()
    {
        $this->createTable('page_list', [
            'page_id' => $this->bigInteger(),
            'name' => Schema::TYPE_TEXT,
            'data_aquired_time'=>$this->timestamp()->defaultValue(null),
            'PRIMARY KEY (page_id)'
        ]);
    }

    public function down()
    {
        echo "m170404_195916_page_list cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
