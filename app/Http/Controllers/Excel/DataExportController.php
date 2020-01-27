<?php

namespace App\Http\Controllers\Excel;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DataExportController extends Controller implements FromView
{
    public function __construct($excel_data)
    {
        $this->heading_array = isset($excel_data['heading_array'])?$excel_data['heading_array']:[];
        $this->excel_data = $excel_data['excel_data'];
        $this->total_data = $excel_data;
    }

    public function view(): View
    {
        return view('excel_template.excel', 
            $this->total_data
        );
    }

    // public function collection()
    // {
    //     return $this->excel_data;
    //     /*$data = $this->indicators_data->all();
    //     $this->heading_array = array_merge($this->heading_array,array_keys($data[0]->toArray()));

    //     return $this->indicators_data->all();*/
    // }

    // public function headings(): array
    // {
    //     return $this->heading_array;
    // }

    // public function drawings()
    // {
    //     $drawing = new Drawing();
    //     $drawing->setName('Logo');
    //     $drawing->setDescription('This is my logo');
    //     $drawing->setPath(public_path('/logo1.jpg'));
    //     $drawing->setHeight(90);
    //     $drawing->setCoordinates('B3');

    //     return $drawing;
    // }

}
