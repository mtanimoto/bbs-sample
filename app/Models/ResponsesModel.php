<?php

namespace App\Models;

use CodeIgniter\Model;

class ResponsesModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'responses';
    protected $primaryKey           = ['thread_id', 'id'];
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDelete        = false;
    protected $protectFields        = false;
    protected $allowedFields        = [];

    // Dates
    protected $useTimestamps        = false;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks       = true;
    protected $beforeInsert         = [];
    protected $afterInsert          = [];
    protected $beforeUpdate         = [];
    protected $afterUpdate          = [];
    protected $beforeFind           = [];
    protected $afterFind            = [];
    protected $beforeDelete         = [];
    protected $afterDelete          = [];

    public function getResList(string $boardId, string $threadId, ?int $reponseId = null): array
    {
        $select = [
            'responses.thread_id',
            'responses.id',
            'responses.name',
            'responses.email',
            'responses.comment',
            'responses.blob_path',
            "CONCAT (" .
                "DATE_FORMAT(responses.posted_at, '%Y-%m-%d'), " .
                "'(', ELT(WEEKDAY(responses.posted_at) + 1, '月','火','水','木','金','土','日'), ') '," .
                "DATE_FORMAT(responses.posted_at, '%T')" .
            ") AS posted_at",
        ];

        $builder = $this->builder();
        $builder->select(implode(', ', $select))
                ->join('threads', 'responses.thread_id = threads.id')
                ->join('boards', 'threads.board_id = boards.id')
                ->where([
                    'boards.id' => $boardId,
                    'threads.id' => $threadId,
                ])
                ->orderBy('responses.id ASC');
        if ($reponseId !== null) {
            $builder->where('responses.id', $reponseId);
        }

		$query = $builder->get();
        if ($query === false) {
            throw new Exception('database select error!!!');
        }

        return $query->getResultArray();
    }

    public function getNextId(string $threadId) {
        $resList = $this->where([
            'thread_id' => $threadId,
        ])->findAll();
        $max = max(array_column($resList, 'id'));
        return $max + 1;
    }
}
