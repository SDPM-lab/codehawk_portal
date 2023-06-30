<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Initdatabase extends Migration
{
	public function up()
	{
		if(ENVIRONMENT !== "production"){
			$this->forge->createDatabase("test_codehawk");
		}	
	}

	public function down()
	{
		//
	}
}
