<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            $data = [
            [
                'id' => (string) (99999000 + $i),
                'screen_name' => 'foo' . $i,
            ],
        ];
        }

        $this->db->table('users')->insertBatch($data);
    }
}
