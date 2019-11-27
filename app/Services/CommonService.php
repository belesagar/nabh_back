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
        Excel::store(new DataExportController($excel_data), \Config::get('constant.UPLOAD_DOCUMENT_URL')."public/hospital/excel/" . $file_name);
        $file_url = \Config::get('constant.DOCUMENT_URL')."hospital/excel/" . $file_name;

        return $file_url;
    }

    public function createPdf($pdf_data = [], $file_name)
    {

            $data = [          'title' => 'First PDF for Medium',          'heading' => 'Hello from 99Points.info',          'content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.'        
        ];

        $pdf = PDF::loadView('pdf_template/hospital/pdf_view', $data);  

        Storage::put(\Config::get('constant.UPLOAD_DOCUMENT_URL').'hospital/pdf/'.$file_name, $pdf->output());
                $file_url = \Config::get('constant.DOCUMENT_URL')."hospital/pdf/" . $file_name;

        return $file_url;
    }

}
