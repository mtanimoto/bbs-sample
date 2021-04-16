<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BoardsSeeder extends Seeder
{
    public function run()
    {
        $users = $this->db->table('users')->get()->getResultArray();
        $data = [];
        foreach ($users as $i => $user) {
            for ($i = 0; $i < 10; $i++) {
                $data[] = [
                    'user_id' => $user['id'],
                    'id' => 'hoge' . ($i+1),
                    'title' => 'hoge' . ($i+1),
                    'name' => null,
                    'description' => '説明１２３４５６７８９０１２３４５６７８９０１２３４５６７８９０',
                    'email' => null,
                ];
            }
        }

        $this->db->table('boards')->insertBatch($data);
    }
}
