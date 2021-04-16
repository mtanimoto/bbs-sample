<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableThreads extends Migration
{
    public function up()
    {
        $this->forge->addField(
            [
                'board_id' => [
                    'type' => 'VARCHAR',
                    'constraint' => '128',
                    'null' => false,
                ],
                'id' => [
                    'type' => 'CHAR',
                    'constraint' => 10,
                    'null' => false,
                ],
                'title' => [
                    'type' => 'VARCHAR',
                    'constraint' => '128',
                    'null' => false,
                ],
            ]
        );
        $this->forge->addField('thread_age BOOL NOT NULL DEFAULT TRUE');
        $this->forge->addField('aged_at DATETIME NOT NULL');
        $this->forge->addField('created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->forge->addField('updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $this->forge->addPrimaryKey(['board_id', 'id']);
        $this->forge->createTable('threads');
    }

    public function down()
    {
        $this->forge->dropTable('threads');
    }
}
