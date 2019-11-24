<?php

namespace App\Repositories;

use App\Model\VirtualHospitalAssetData;

class VirtualHospitalAssetDataRepository
{
    protected $model;

    public function __construct(VirtualHospitalAssetData $model_name)
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

    public function delete($where_clause)
    {
        return $this->model->where($where_clause)->delete();
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

    public function getVitualFloorAssetData($param)
    {
        return $this->model->where([
            ["hospital_id", $param['hospital_id']],
            ["type", $param['type']],
            ["virtual_hospital_data_id", $param['virtual_hospital_data_id']]
        ])->first();
    }

    public function getVitualFloorAssetDataCount($param)
    {
        return $this->model->where([
            ["hospital_id", $param['hospital_id']],
            ["type", $param['type']],
            ["virtual_hospital_data_id", $param['virtual_hospital_data_id']]
        ])->count();
    }

    public function getFloorAssetData($param)
    {
        $where_clause = ["hospital_id" => $param['hospital_id']];
        if(isset($param['type']) && $param['type'] != "")
        {
            $where_clause['type'] = $param['type'];
        }

        if(isset($param['virtual_hospital_data_id']) && $param['virtual_hospital_data_id'] != "")
        {
            $where_clause['virtual_hospital_data_id'] = $param['virtual_hospital_data_id'];
        }

        return $this->model->where($where_clause)->get()->toArray();
    }

}
