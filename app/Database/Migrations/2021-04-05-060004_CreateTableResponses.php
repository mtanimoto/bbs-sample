<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableResponses extends Migration
{
    public function up()
    {
        $this->forge->addField(
            [
                'thread_id' => [
                    'type' => 'CHAR',
                    'constraint' => 10,
                    'null' => false
                ],
                'id' => [
                    'type' => 'INT',
                    'constraint' => '4',
                    'null' => false,
                    'unsigned' => true,
                ],
                'name' => [
                    'type' => 'VARCHAR',
                    'constraint' => '128',
                    'null' => true,
                ],
                'email' => [
                    'type' => 'VARCHAR',
                    'constraint' => '128',
                    'null' => true,
                ],
                'comment' => [
                    'type' => 'TEXT',
                    'null' => false,
                ],
                'blob_path' => [
                    'type' => 'VARCHAR',
                    'constraint' => '128',
                    'null' => true,
                ]
            ]
        );
        $this->forge->addField('posted_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->forge->addField('created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->forge->addField('updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $this->forge->addPrimaryKey(['thread_id', 'id']);
        $this->forge->createTable('responses');
    }

    public function down()
    {
        $this->forge->dropTable('responses');
    }
}
