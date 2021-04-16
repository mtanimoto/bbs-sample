<?php

namespace App\Models;

use CodeIgniter\Model;

class ThreadsModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'threads';
    protected $primaryKey           = ['board_id', 'id'];
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

    public function getTitle(string $boardId, string $id): ?string
    {
        $row = $this->where([
            'board_id' => $boardId,
            'id' => $id,
        ])->first();
        
        return empty($row) ? null : $row['title'];
    }

    public function getThraeds(string $boardId)
    {
        $builder = $this->builder('responses');
        $subQuery = $builder
                        ->select(
                            "thread_id, " .
                            "max(id) AS id"
                        )
                        ->groupBy("thread_id")
                        ->getCompiledSelect();
        $builder = $this->builder();
        $query = $builder
                    ->select('threads.*, latest_res.id AS response_id')
                    ->join("boards", 'boards.id = threads.board_id')
                    ->join("({$subQuery}) AS latest_res", 'latest_res.thread_id = threads.id')
                    ->where('board_id', $boardId)
                    ->orderBy('thread_age DESC, aged_at DESC')
                    ->get();
        if ($query === false) {
            throw new Exception('database select error!!!');
        }

        return $query->getResultArray();
    }

    protected function doUpdate($id = null, $data = null): bool
    {
        $escape       = $this->escape;
        $this->escape = [];

        $builder = $this->builder();

        if (is_string($this->primaryKey) && $id) {
            $builder->whereIn($this->table . '.' . $this->primaryKey, $id);
        }

        if (is_array($this->primaryKey) && $id) {
            foreach ($this->primaryKey as $key) {
                $builder->where($key, $id[$key]);    
            }
        }

        // Must use the set() method to ensure to set the correct escape flag
        foreach ($data as $key => $val) {
            $builder->set($key, $val, $escape[$key] ?? null);
        }

        return $builder->update();
    }
}
