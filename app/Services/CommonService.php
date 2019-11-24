<?php

namespace App\Services;

use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Excel\DataExportController;
use Illuminate\Support\Facades\Storage;
use PDF;

class CommonService
{
    public function __construct() {

    }

    public function createExcel($excel_data = [], $file_name)
    {
       	$heading_array = array_keys($excel_data[0]->toArray());
        $excel_data = ["excel_data" => $excel_data, "heading_array" => $heading_array];

        Excel::store(new DataExportController($excel_data), "hospital/excel/" . $file_name);
        $file_url = "storage/hospital/excel/" . $file_name;

        return $file_url;
    }

    public function createPdf($pdf_data = [], $file_name)
    {
       	$heading_array = array_keys($pdf_data[0]->toArray());
        $excel_data = ["excel_data" => $pdf_data, "heading_array" => $heading_array];


            $data = [          'title' => 'First PDF for Medium',          'heading' => 'Hello from 99Points.info',          'content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.'        
        ];

        $pdf = PDF::loadView('pdf_template/hospital/pdf_view', $data);  

        Storage::put('hospital/pdf/'.$file_name, $pdf->output());
                $file_url = "storage/hospital/pdf/" . $file_name;

        return $file_url;
    }

}
