<?php

namespace App\Models;

use CodeIgniter\Model;

class BoardsModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'boards';
    protected $primaryKey           = ['user_id', 'id'];
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
