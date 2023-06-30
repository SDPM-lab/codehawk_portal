<?php

namespace UserModule\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserAuth extends Migration
{
	public function up()
	{
		$this->forge->addField([
			"key" => [
				'type' => 'int',
				'unsigned' => true,
				'auto_increment' => true 
			],
			"user_key" => [
				'type' => 'int',
				'unsigned' => true,
			],
			"access_token" => [
				'type' => 'varchar',
				'constraint' => 800
			],
			"refresh_token" => [
				'type' => 'varchar',
				'constraint' => 800
			],
			"user_agent" => [
				'type' => 'varchar',
				'constraint' => 255
			],
			"user_ip" => [
				'type' => 'varchar',
				'constraint' => 50
			],
			"created_at" => [
				'type' => 'datetime'
			],
			"updated_at" => [
				'type' => 'datetime',
				'null' => true
			],
			"deleted_at" => [
				'type' => 'datetime',
				'null' => true
			]
		]);
		$this->forge->addPrimaryKey('key', TRUE);
		$this->forge->addForeignKey('user_key','user','key','CASCADE','CASCADE');
		$this->forge->createTable('user_auth');
	}

	public function down()
	{
		$this->forge->dropTable('user_auth');
	}
}
