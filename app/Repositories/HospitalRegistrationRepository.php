<?php

namespace App\Repositories;

use App\Model\HospitalRegistration;

class HospitalRegistrationRepository
{
    protected $model;

    public function __construct(HospitalRegistration $model_name)
    {
        $this->model = $model_name;
        $this->select = ["hospital_name", "spoc_name", "spoc_designation", "email", "mobile", "city", "state", "pincode", "number_of_bed"];
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
        return $this->model->select($this->select)->find($id);
    }

    public function findByField($field_name, $field_value, $multiple = false)
    {
        if ($multiple) {
            return $this->model->select($this->select)->where($field_name, $field_value)->get();
        } else {
            return $this->model->select($this->select)->where($field_name, $field_value)->first();
        }
    }

    public function getDataByCustomeWhere($param, $multiple = false)
    {
        if ($multiple) {
            return $this->model->select($this->select)->where($param)->get();
        } else {
            return $this->model->select($this->select)->where($param)->first();
        }
    }

}
