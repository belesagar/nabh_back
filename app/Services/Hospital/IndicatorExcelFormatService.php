<?php

namespace App\Services\Hospital;

use App\Repositories\IndicatorExcelFormatRepository;
use App\Repositories\NabhIndicatorsRepository;
use App\Repositories\IndicatorsFormFieldsRepository;
use App\Repositories\HospitalIndicatorsRepository;
use App\Services\CommonService;

class IndicatorExcelFormatService
{
    public function __construct(
        IndicatorExcelFormatRepository $indicator_excel_format_repository,
        NabhIndicatorsRepository $nabh_indicator_repository,
        IndicatorsFormFieldsRepository $indicators_form_fields_repository,
        HospitalIndicatorsRepository $hospital_indicators_repository,
        CommonService $common_service
    ) {
        $this->indicator_excel_format_repository = $indicator_excel_format_repository;
        $this->nabh_indicator_repository = $nabh_indicator_repository;
        $this->indicators_form_fields_repository = $indicators_form_fields_repository;
        $this->hospital_indicators_repository = $hospital_indicators_repository;
        $this->common_service = $common_service;

        $this->payload = auth('hospital_api')->user();
        $this->hospital_id = $this->payload['hospital_id'];
        $this->hospital_user_id = $this->payload['hospital_user_id'];
    }

    public function getIndicatorExcelFormatList($postdata)
    {
        $data = [];
        if(isset($postdata['indicator_id']) && $postdata['indicator_id'] != "")
        {
            $data = $this->indicator_excel_format_repository->findByField("indicator_id", $postdata['indicator_id'], true);
        }
        return $return = array("success" => true, "error_code" => 0, "info" => "","data" => ["list_data" => $data]);
    }

    public function getIndicatorExcelFormatData($postdata)
    {
        $this->hospital_id = 1;
        // $test = [
        //     [
        //         "column_name" => "SUM of RE EXPLORATION NUMBER",
        //         "calculation_ids" => [21],
        //         "calculation_type" => "sum"
        //     ],
        //     [
        //         "column_name" => "RE-EXPLORATION RATE",
        //         "calculation_ids" => [1,2],
        //         "calculation_type" => "percentage"
        //     ]
        // ];
        // dd(json_encode($test));
        $return = [];
        $where_clouse = [
            "indicator_id" => $postdata['indicator_id'],
            "indicator_excel_format_id" => $postdata['excel_format_id'],
        ];
        $report_data = $this->indicator_excel_format_repository->getDataByCustomeWhere($where_clouse,false);
        
        // dd($report_data);
        // print_r(json_decode($report_data->calculation_fields,true));
        if(!empty($report_data))
        {
            if($report_data['indicator_field_ids'] != "")
            {
                $indicator_field_ids_array = explode(',', $report_data['indicator_field_ids']);

                if(count($indicator_field_ids_array) > 1)
                {
                    $param = [
                        "indicator_field_ids" => $indicator_field_ids_array
                    ];
                    $indicator_field_data = $this->indicators_form_fields_repository->getIndicatorDataByForExcel($param);
                }else{
                    $param[] = ['indicators_ids', 'like', '%"' . $indicator_field_ids_array[0] . '"%'];
                    $indicator_field_data = $this->indicators_form_fields_repository->getData($param);
                }
                // dd($indicator_field_data);
                // dd($indicator_field_data);
                if(!empty($indicator_field_data))
                {
                    
                    // dd($calculation_fields);
                    $collection = collect($indicator_field_data);
                    $form_name_data = $collection->pluck('form_name');
                    $form_name_data[] = "created_at";
                    
                    $form_field_param = [
                        ["hospital_id", $this->hospital_id],
                        ["indicators_id", $postdata['indicator_id']]
                    ];
                    // dd($form_field_param);

                    $from_date = !empty($postdata['from_date'])?date("Y-m-d",strtotime($postdata['from_date'])):date("Y-m-01");
                    $to_date = !empty($postdata['to_date'])?date("Y-m-d,",strtotime($postdata['to_date'])):date("Y-m-t", strtotime($from_date));

                    $excel_data_array = [];

                    $date_array = \Helpers::getFirstandlastDate($from_date,$to_date);
                    // dd($date_array);
                    foreach ($date_array as $date_array_value) 
                    {       
                        $form_field_param[] = ["created_at",'>',$date_array_value['first_date']];
                        $form_field_param[] = ["created_at",'<',$date_array_value['last_date']];
                        $indicator_data = $this->hospital_indicators_repository->getDataByCustomeWhereWithSelect($form_field_param,$form_name_data->all(),true);
                        // dd($form_field_param);
                        if(count($indicator_data) > 0)
                        {
                            $excel_data = [];

                            $excel_data['MONTH'] = date("F",strtotime($date_array_value['first_date']));
                            if(!empty($report_data->calculation_fields))
                            {
                                $calculation_fields = json_decode($report_data->calculation_fields,true);
                                $indicator_data_collection = collect($indicator_data);
                                foreach ($calculation_fields as $calculation_fields_value) {

                                    $filtered = $collection->whereIn('form_id', $calculation_fields_value['calculation_ids']);
                                    $form_name_data = $filtered->pluck('form_name');
                                    
                                    $calculation_data_array = [];

                                    foreach ($form_name_data as $form_name_data_value) {
                                        $count = 0;
                                        $column_value = array_column($indicator_data->toArray(), $form_name_data_value);
                                       
                                        foreach ($column_value as $column_value) {
                                            if($column_value == "Yes")
                                            {
                                                ++$count;
                                            } else if($column_value != ""){
                                                ++$count;
                                            }
                                        }

                                        $calculation_data_array[] = $count;
                                    }
                                    $calculated_amount = 0;
                                 
                                    if($calculation_fields_value['calculation_type'] == "sum")
                                    {
                                        $calculated_amount = $calculation_data_array[0];
                                    }

                                    if($calculation_fields_value['calculation_type'] == "percentage")
                                    {
                                        if($calculation_data_array[0] > 0)
                                        {
                                            $calculated_amount = round(($calculation_data_array[1]/$calculation_data_array[0])*100,2);
                                        }
                                    }

                                    $excel_data[$calculation_fields_value['column_name']] = $calculated_amount;

                                    
                                }
                                $excel_data_array[] = $excel_data;
                            }else{

                                //$excel_data = $indicator_data->toArray();
                                $excel_data_array = array_merge($excel_data_array, $indicator_data->toArray());
                            }
                            
                            
                            // dd($excel_data_array);
                        } 
                    }
                    // dd($excel_data_array);
                    if(!empty($excel_data_array))
                    {
                        $heading_array = array_keys($excel_data_array[0]);
                        $excel_data = [
                            "excel_data" => $excel_data_array,
                            "heading_array" => $heading_array,
                            // "other_data" => $hospital_data
                        ];
                        // dd($excel_data);
                        $file_name = $this->hospital_id."/".$report_data["excel_name"]."_".$this->hospital_id.".xlsx";
                        $file_url = $this->common_service->createExcel($excel_data,$file_name.".xlsx");

                        $data['file_url'] = $file_url;

                        $return = ["success" => true, "error_code" => 0, "info" => "", "data" => $data];
                    } else {
                        $return = ["success" => false, "error_code" => 1, "info" => "No data present."];
                    }
                    
                } else {
                    $return = ["success" => false, "error_code" => 1, "info" => "No data found."];
                }
            } else {
                $return = ["success" => false, "error_code" => 1, "info" => "Some data is missing."];
            }
        } else {
            $return = ["success" => false, "error_code" => 404, "info" => "Data not present."];
        }

        return $return;
    }

}
