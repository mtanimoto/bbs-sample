<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableBoards extends Migration
{
    public function up()
    {
        $this->forge->addField(
            [
                'user_id' => [
                    'type' => 'VARCHAR',
                    'constraint' => '128',
                    'null' => false,
                ],
                'id' => [
                    'type' => 'VARCHAR',
                    'constraint' => '128',
                    'null' => false,
                ],
                'title' => [
                    'type' => 'VARCHAR',
                    'constraint' => '128',
                    'null' => false,
                ],
                'name' => [
                    'type' => 'VARCHAR',
                    'constraint' => '128',
                    'null' => true,
                ],
                'description' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'email' => [
                    'type' => 'VARCHAR',
                    'constraint' => '128',
                    'null' => true,
                ],
            ]
        );
        $this->forge->addField('created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->forge->addField('updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $this->forge->addPrimaryKey(['user_id', 'id']);
        $this->forge->createTable('boards');
    }

    public function down()
    {
        $this->forge->dropTable('boards');
    }
}
