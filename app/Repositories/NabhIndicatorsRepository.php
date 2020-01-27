<?php

namespace App\Repositories;

use App\Model\NabhIndicators;

class NabhIndicatorsRepository
{
    protected $model;

    public function __construct(NabhIndicators $model_name)
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

    public function getIndicatorDataByForExcel($param)
    {
        $where_clause = ["status" => "ACTIVE"];
        return $this->model->where($where_clause)->where($param)->get();
        // return $this->model->where($where_clause)->whereIn('indicators_id', $param['indicator_ids'])->get();
    }

}
