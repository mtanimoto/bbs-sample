<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ResponsesSeeder extends Seeder
{
    public function run()
    {
        $boards = $this->db->table('boards')->get()->getResultArray();
        foreach ($boards as $board) {
            $threads = $this->db->table('threads')->where('board_id', $board['id'])->get()->getResultArray();
            foreach ($threads as $thread) {
                $data = [
                    [
                        'thread_id' => $thread['id'],
                        'id' => 1,
                        'name' => 'aaa',
                        'email' => 'sage',
                        'comment' => 'test',
                    ],
                ];
        
                $this->db->table('responses')->insertBatch($data);
            }
        }

    }
}
