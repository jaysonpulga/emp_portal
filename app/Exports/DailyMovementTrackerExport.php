<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DailyMovementTrackerExport implements FromCollection, WithHeadings
{

    protected $work_date;

    public function __construct(string $work_date) {
        $this->work_date = $work_date;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        $data = DB::table('users')
            ->leftJoin('employee_movements', function($leftJoin) {
                $leftJoin->on('employee_movements.user_id', '=', 'users.id')
                    ->where('transact_date', '=', $this->work_date);
            })
//            ->leftJoin('employee_movements', 'users.id', '=','employee_movements.user_id')
            ->leftJoin('companies', 'users.company_id', '=', 'companies.id')
            ->select('users.name', 'users.email', 'users.address', 'users.mobile', 'companies.name as company', 'users.designation', 'transact_date', 'places', 'people', 'modeoftranspo')
            ->orderBy('users.name')
            ->get();

        return $data;
    }
    public function headings(): array
    {
        // TODO: Implement headings() method.
        return [
            'Employee Name',
            'Email',
            'Address',
            'Mobile',
            'Company',
            'Designation',
            'Work Date',
            'Places Been To',
            'Met With These People',
            'Mode of Transportation'
        ];
    }
}
