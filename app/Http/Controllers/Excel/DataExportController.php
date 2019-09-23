<?php

namespace App\Http\Controllers\Excel;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Model\IndicatorsData;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;

class DataExportController extends Controller implements FromCollection, WithHeadings
{
    public function __construct($excel_data)
    {

        $this->indicators_data = new IndicatorsData();
        $this->heading_array = $excel_data['heading_array'];
        $this->excel_data = $excel_data['excel_data'];
    }

    public function collection()
    {
        return $this->excel_data;
        /*$data = $this->indicators_data->all();
        $this->heading_array = array_merge($this->heading_array,array_keys($data[0]->toArray()));

        return $this->indicators_data->all();*/
    }

    public function headings(): array
    {
        return $this->heading_array;
    }

}
