<?php

use yii\db\Schema;
use yii\db\Migration;

class m170401_144617_Post_from_feed extends Migration
{
    public function up()
    {
		$this->createTable('post_from_feed', [
			'page_id' => $this->integer(),
			'post_id' => Schema::TYPE_STRING,
			'from_name' => Schema::TYPE_STRING,
			'from_category' => Schema::TYPE_STRING,
			'from_id' => $this->integer(),
			'number_of_likes' => $this->integer(),
			'number_of_comments' => $this->integer(),
			'title' => Schema::TYPE_STRING,
			'page_owner' => $this->boolean(),
			'to_name' => Schema::TYPE_TEXT,
			'to_category' => Schema::TYPE_TEXT,
			'to_id' => $this->integer(),
			'message' => Schema::TYPE_TEXT,
			'message_tags' => $this->boolean(),
			'picture' => Schema::TYPE_TEXT,
			'link' => Schema::TYPE_TEXT,
			'name' => Schema::TYPE_TEXT,
			'caption' => Schema::TYPE_TEXT,
			'description' => Schema::TYPE_TEXT,
			'source' => Schema::TYPE_TEXT,
			'properties' => Schema::TYPE_TEXT,
			'icon' => Schema::TYPE_TEXT,
			'actions_name_comment' => Schema::TYPE_TEXT,
			'actions_link_comment' => Schema::TYPE_TEXT,
			'actions_name_like' => Schema::TYPE_TEXT,
			'actions_link_like' => Schema::TYPE_TEXT,
			'privacy_description' => Schema::TYPE_TEXT,
			'privacy_value' => Schema::TYPE_TEXT,			
			'type' => Schema::TYPE_TEXT,
			'likes' => $this->integer(),
			'place' => Schema::TYPE_TEXT,
			'story' => Schema::TYPE_TEXT,
			'story_tags' => Schema::TYPE_TEXT,
			'with_tags' => $this->boolean(),
			'comments' => Schema::TYPE_TEXT,			
			'object_id' => Schema::TYPE_TEXT,
			'application_name' => Schema::TYPE_TEXT,
			'application_id' => Schema::TYPE_TEXT,			
			'created_time'=> $this->timestamp(),
            'updated_time'=> $this->timestamp(),
			'data_aquired_time'=>$this->timestamp()->defaultValue(null)
		]);
    }

    public function down()
    {
		$this->dropTable('post_from_feed');
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
