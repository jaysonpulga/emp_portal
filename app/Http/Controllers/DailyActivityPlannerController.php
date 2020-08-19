<?php

namespace App\Http\Controllers;

use App\EmployeeActivity;
use App\Exports\DapExport;
use App\WorkHour;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;


use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Worksheet_MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;


class DailyActivityPlannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $workhours = WorkHour::orderBy('id')->get();

//        if($request->ajax())
//        {
//            $data = DB::table('employee_activities')->where('user_id', '=', Auth::user()->id)
////                ->where('transact_date', '=', $request->work_date)
//                ->leftJoin('work_hours','employee_activities.hour_from', '=','work_hours.id')
//                ->leftJoin('work_hours as work_hours_to','employee_activities.hour_to', '=','work_hours_to.id')
//                ->orderBy('transact_date')
//                ->orderBy('hour_from')
//                ->get(['employee_activities.*', 'work_hours.name as start_hour', 'work_hours_to.name as end_hour']);
//
//            return Datatables::of($data)
//                ->addColumn('action', function($data){
//                    $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm">Edit</button>';
//                    $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="edit" id="'.$data->id.'" class="delete btn btn-danger btn-sm">Delete</button>';
//                    return $button;
//                })
//                ->rawColumns(['action'])
//                ->make(true);
//        }
        return view('dailyactivityplanner', compact('workhours'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function getdailyactivity(Request $request) {

        if($request->ajax()) {
            $data = DB::table('employee_activities')->where('user_id', '=', Auth::user()->id)
                ->where('transact_date', '=', $request->work_date)
                ->leftJoin('work_hours','employee_activities.hour_from', '=','work_hours.id')
                ->leftJoin('work_hours as work_hours_to','employee_activities.hour_to', '=','work_hours_to.id')
                ->orderBy('transact_date')
                ->orderBy('hour_from')
                ->get(['employee_activities.*', 'work_hours.name as start_hour', 'work_hours_to.name as end_hour']);

            return Datatables::of($data)
                ->addColumn('action', function($data){
                    $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm">Edit</button>';
                    $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="edit" id="'.$data->id.'" class="delete btn btn-danger btn-sm">Delete</button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $rules = array(
            'transact_date' => 'required|date|date_format:Y-m-d',
            'hour_from' => 'required',
            'hour_to' => 'required',
            'image' => 'mimes:jpeg|max:4096',
        );
        $messages = [
            'transact_date.required' => 'Work Date is required.',
            'hour_from.required' => 'Work Hour (From) is required.',
            'hour_to.required' => 'Work Hour (To) is required.',
        ];

        $error = Validator::make($request->all(), $rules, $messages);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        if($request->hour_to < $request->hour_from) {
            $error->errors()->add('hour_to', 'Invalid range of work hours.');
            return response()->json(['errors' => $error->errors()->all()]);
        }

        if(empty($request->hidden_id)) {
            $data = new EmployeeActivity();
            $data->user_id = Auth::user()->id;
        }
        else {
            $data = EmployeeActivity::where('user_id', '=',  Auth::user()->id)
                ->where('id', '=', $request->hidden_id)
                ->first();
        }
        $data->transact_date = Carbon::createFromFormat('Y-m-d', $request->transact_date)->format('Y-m-d');
        $data->hour_from = $request->hour_from;
        $data->hour_to = $request->hour_to;
        $data->planned_activity = $request->planned_activity;
        $data->actual_activity = $request->actual_activity;
        $data->status = $request->status;
        $data->save();

        if($request->hasfile('image'))
        {
            $data = EmployeeActivity::where('user_id', '=',  Auth::user()->id)
                ->where('id', '=', $data->id)
                ->first();

            $filename = 'https://mygreenapplebucket.s3-ap-southeast-1.amazonaws.com/bilrey/' . $data->id . '_' . $request->file('image')->getClientOriginalName();
            $data->filename = $filename;
            $data->save();


            $file = $request->file('image');

            $name = $data->id . '_' . $file->getClientOriginalName();

            $filePath = 'bilrey/' . $name;

            Storage::disk('s3')->put($filePath, file_get_contents($file));
//            return back()->with('success','Image Uploaded successfully');
        }

        return response()->json(['success' => 'Record added successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $workhours = WorkHour::orderBy('id')->get();

        if(request()->ajax()) {
            $data = EmployeeActivity::findOrFail($id);
            return response()->json(['result' => $data,
                'workhours' => $workhours
                ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeActivity $employeeActivity)
    {
        //
        $rules = array(
            'transact_date' => 'required|date|date_format:Y-m-d',
            'hour_from' => 'required',
            'hour_to' => 'required',
            'image' => 'mimes:jpeg|max:4096',
        );
        $messages = [
            'transact_date.required' => 'Work Date is required.',
            'hour_from.required' => 'Work Hour (From) is required.',
            'hour_to.required' => 'Work Hour (To) is required.',
        ];

        $error = Validator::make($request->all(), $rules, $messages);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        if($request->hour_to < $request->hour_from) {
            $error->errors()->add('hour_to', 'Invalid range of work hours.');
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $data = EmployeeActivity::where('user_id', '=',  Auth::user()->id)
            ->where('id', '=', $request->hidden_id)
            ->first();
        if(!empty($data->id)) {
            $data->transact_date = Carbon::createFromFormat('Y-m-d', $request->transact_date)->format('Y-m-d');
            $data->hour_from = $request->hour_from;
            $data->hour_to = $request->hour_to;
            $data->planned_activity = $request->planned_activity;
            $data->actual_activity = $request->actual_activity;
            $data->status = $request->status;
            $filename = '';
            if($request->hasfile('image')) {
                $filename = 'https://mygreenapplebucket.s3-ap-southeast-1.amazonaws.com/bilrey/' . $data->id . '_' . $request->file('image')->getClientOriginalName();
            }
            $data->filename = $filename;
            $data->save();
        }

        if($request->hasfile('image'))
        {
            $file = $request->file('image');

            $name = $data->id . '_' . $file->getClientOriginalName();

            $filePath = 'bilrey/' . $name;

            Storage::disk('s3')->put($filePath, file_get_contents($file));
        }

        return response()->json(['success' => 'Record successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $data = EmployeeActivity::findOrFail($id);
        $data->delete();
    }
    public function getdap(Request $request) {
		

        $employeeid = Auth::user()->id;
        $work_date = $request->work_date;
        $name = Auth::user()->name;
        $currDateTime = Carbon::now()->format('Y-m-d H:i:s');
		
		
		     $data = DB::table('employee_activities')->where('employee_activities.user_id', '=', $employeeid)
            ->where('transact_date', '=', $work_date)
            ->leftJoin('work_hours as work_hours_from', 'employee_activities.hour_from', '=', 'work_hours_from.id')
            ->leftJoin('work_hours as work_hours_to', 'employee_activities.hour_to', '=', 'work_hours_to.id')
            ->select('transact_date', 'work_hours_from.name as starttime', 'work_hours_to.name as endtime', 'planned_activity', 'actual_activity', 'status', 'filename')
            ->orderBy('transact_date')
            ->orderBy('hour_from')
            ->orderBy('hour_to')
            ->orderBy('employee_activities.id')
            ->get();
			
			
			$reportdetails = array();
			$dd = array();
			//format
			foreach($data as $object)
			{
					
			
				$dd['Work Date'] = $object->transact_date;
				$dd['Start Time'] = $object->starttime;
				$dd['End Time'] =  $object->endtime;
				$dd['Planned Activity'] = $object->planned_activity;
				$dd['Actual Activity'] = $object->actual_activity;
				$dd['Status'] =  $object->status;
				$dd['ImageAttachment'] = $object->filename;
				
				$reportdetails[]  = $dd;
				
			}
			
		


			$objPHPExcel = new PHPExcel(); 
			$objPHPExcel->getProperties()
					->setCreator("user")
					->setLastModifiedBy("user")
					->setTitle("Office 2007 XLSX Test Document")
					->setSubject("Office 2007 XLSX Test Document")
					->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
					->setKeywords("office 2007 openxml php")
					->setCategory("Test result file");

			// Set the active Excel worksheet to sheet 0
			$objPHPExcel->setActiveSheetIndex(0); 

			// Initialise the Excel row number
			$rowCount = 0; 

			// Sheet cells
			$cell_definition = array(

				'A' => 'Work Date',
				'B' => 'Start Time',
				'C' => 'End Time',
				'D' => 'Planned Activity',
				'E' => 'Actual Activity',
				'F' => 'Status',
				'G' => 'ImageAttachment'
			);
			
	
			

			// Build headers
			foreach( $cell_definition as $column => $value )
			{
				$objPHPExcel->getActiveSheet()->getColumnDimension("{$column}")->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->setCellValue( "{$column}1", $value ); 
			}
			
			
				// Build cells
				while( $rowCount < count($reportdetails) ){ 
					$cell = $rowCount + 2;
					foreach( $cell_definition as $column => $value ) {

						$objPHPExcel->getActiveSheet()->getRowDimension($rowCount + 2)->setRowHeight(35); 
						
						switch ($value) {
							case 'ImageAttachment':
							
									$IMG = $signature = $reportdetails[$rowCount][$value];
									$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
									$gdImage = imagecreatefromjpeg($IMG);
									$objDrawing->setName('Company Logo');
									$objDrawing->setDescription('Company Logo image');
									$objDrawing->setResizeProportional(false);
									$objDrawing->setImageResource($gdImage);
									$objDrawing->setRenderingFunction(MemoryDrawing::RENDERING_JPEG);
									$objDrawing->setMimeType(MemoryDrawing::MIMETYPE_DEFAULT);
									$objDrawing->setWidth(32);  
									$objDrawing->setHeight(32); 
									$objDrawing->setOffsetX(25);  //setOffsetX works properly
									$objDrawing->setOffsetY(10);
									$objDrawing->setCoordinates($column.$cell);  //set image to cell
									$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save 
									
								
								break;

							default:
								$objPHPExcel->getActiveSheet()->setCellValue($column.$cell, $reportdetails[$rowCount][$value] ); 
								break;
						}

					}
						
					$rowCount++; 
				} 
				
		
				$fileName = 'DAP_' . $employeeid . '_' . $currDateTime . '.xlsx';
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="'.$fileName.'"');
				header('Cache-Control: max-age=0');

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save('php://output');				
				die();

		//return Excel::download(new DapExport($employeeid), 'DAP_' . $employeeid . '_' . $currDateTime . '.xlsx');
       // return Excel::download(new DapExport($employeeid, $work_date), 'DAP' . '.xlsx');
    }

}
