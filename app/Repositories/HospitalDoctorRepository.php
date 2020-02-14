<?php

namespace App\Repositories;

use App\Model\HospitalDoctors;

class HospitalDoctorRepository
{
    protected $model;

    public function __construct(HospitalDoctors $model_name)
    {
        $this->model = $model_name;
    }

    public function create($params)
    {
        return $this->model->create($params);
    }
    
    public function insert($params)
    {
        return $this->model->insert($params);
    }

    public function update($params, $where_clause)
    {
        return $this->model->where($where_clause)->update($params);
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function show($id)
    {
        return $this->model->find($id);
    }

    public function all()
    {
        return $this->model->all();
    }

    public function findByField($field_name, $field_value, $multiple = false)
    {
        if ($multiple) {
            return $this->model->where($field_name, $field_value)->get();
        } else {
            return $this->model->where($field_name, $field_value)->first();
        }
    }

    public function getDataByCustomeWhere($param = [], $multiple = false)
    {
        if ($multiple) {
            return $this->model->where($param)->get();
        } else {
            return $this->model->where($param)->first();
        }
    }

}
