<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ThreadsSeeder extends Seeder
{
    public function run()
    {
        $boards = $this->db->table('boards')->get()->getResultArray();
        $data = [];
        foreach ($boards as $i => $board) {
            for ($i = 0; $i < 10; $i++) {
                $data[] = [
                    'board_id' => $board['id'],
                    'id' => strtotime('now') + rand(0, 10000000),
                    'title' => 'fuga' . $i,
                ];
            }
        }

        $this->db->table('threads')->insertBatch($data);
    }
}
