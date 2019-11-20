<?php

namespace App\Repositories;

use App\Model\VirtualHospitalData;

class VirtualHospitalDataRepository
{
    protected $model;

    public function __construct(VirtualHospitalData $virtual_hospital)
    {
        $this->model = $virtual_hospital;
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

    public function getVitualFloorData($param)
    {
        return $this->model->where([
            ["hospital_id", $param['hospital_id']],
            ["floor_no", $param['floor_no']],
            ["virtual_hospital_id", $param['virtual_hospital_id']]
        ])->first();
    }

    public function getVitualFloorDataByFloor($param)
    {
        return $this->model->where([
            ["hospital_id", $param['hospital_id']],
            ["floor_no", $param['floor_no']]
        ])->first();
    }

}
