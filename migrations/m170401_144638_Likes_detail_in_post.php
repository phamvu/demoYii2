<?php

use yii\db\Schema as Schema;
use yii\db\Migration;

class m170401_144638_Likes_detail_in_post extends Migration
{
    public function up()
    {
		$this->createTable('likes_detail_in_post', [
			'page_id' => $this->integer(),
			'post_id' => Schema::TYPE_STRING,
			'individual_name' => Schema::TYPE_TEXT,
			'individual_category' => Schema::TYPE_TEXT,
			'individual_id' => $this->integer(),
			//'to_name' => Schema::TYPE_TEXT,
			//'data_aquired_time'=>$this->timestamp()->defaultValue(null)
		]);
    }

    public function down()
    {
        $this->dropTable('likes_detail_in_post');
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
