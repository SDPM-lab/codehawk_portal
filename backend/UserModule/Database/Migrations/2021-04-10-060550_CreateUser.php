<?php

namespace UserModule\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUser extends Migration
{
	public function up()
	{
		$this->forge->addField([
			"key" => [
				'type' => 'int',
				'unsigned' => true,
				'auto_increment' => true 
			],
			"first_name" => [
				'type' => 'varchar',
				'constraint' => 100
			],
			"last_name" => [
				'type' => 'varchar',
				'constraint' => 100
			],
			"email" => [
				'type' => 'varchar',
				'constraint' => 255
			],
			"img" => [
				'type' => 'varchar',
				'constraint' => 100,
				'null' => true
			],
			"created_at" => [
				'type' => 'datetime'
			],
			"updated_at" => [
				'type' => 'datetime',
				'null' => true
			],
		]);
		$this->forge->addPrimaryKey('key', TRUE);
		$this->forge->createTable('user');
	}

	public function down()
	{
		$this->forge->dropTable('user');
	}
}
