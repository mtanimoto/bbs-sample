<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TestSeeder extends Seeder
{
    public function run()
    {
        $this->call('UsersSeeder');
        $this->call('BoardsSeeder');
        $this->call('ThreadsSeeder');
        $this->call('ResponsesSeeder');
    }
}
