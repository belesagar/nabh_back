<?php

namespace App\Repositories;

use App\Model\IndicatorsData;

class HospitalIndicatorsRepository
{
    protected $model;

    public function __construct(IndicatorsData $model_name)
    {
        $this->model = $model_name;
    }

    public function create($params)
    {
        return $this->model->create($params);
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

    public function findByField($field_name, $field_value, $multiple = false)
    {
        if ($multiple) {
            return $this->model->where($field_name, $field_value)->get();
        } else {
            return $this->model->where($field_name, $field_value)->first();
        }
    }

    public function getDataByCustomeWhere($param, $multiple = false)
    {
        if ($multiple) {
            return $this->model->where($param)->get();
        } else {
            return $this->model->where($param)->first();
        }
    }

    public function getDataByCustomeWhereWithSelect($param, $select_data = ['*'], $multiple = false)
    {
        if ($multiple) {

//             \DB::enableQueryLog();

// $this->model->select($select_data)->where($param)->get();

// $query = \DB::getQueryLog();

// print_r($query);
// exit;
            return $this->model->select($select_data)->where($param)->get();
        } else {
            return $this->model->select($select_data)->where($param)->first();
        }
    }

}
