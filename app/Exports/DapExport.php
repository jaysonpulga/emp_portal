<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class DapExport implements FromCollection, WithHeadings, WithDrawings
{

    protected $employeeid;
    protected $work_date;

    public function __construct(string $employeeid, string $work_date) {
        $this->employeeid = $employeeid;
        $this->work_date = $work_date;
    }

  
    public function drawings() {

        $drawing = new MemoryDrawing();

        $IMG = 'https://mygreenapplebucket.s3-ap-southeast-1.amazonaws.com/bilrey/13_IMG_20180607_115742-min.jpeg';
        $gdImage = imagecreatefromjpeg($IMG);
        $drawing->setName('Company Logo');
        $drawing->setDescription('Company Logo image');
        $drawing->setResizeProportional(false);
        $drawing->setImageResource($gdImage);
        $drawing->setRenderingFunction(MemoryDrawing::RENDERING_JPEG);
        $drawing->setMimeType(MemoryDrawing::MIMETYPE_DEFAULT);
        $drawing->setWidth(211);
        $drawing->setHeight(40);
        $drawing->setOffsetX(5);
        $drawing->setOffsetY(30);
        $drawing->setCoordinates('H3');
        return [$drawing];
    }
}
