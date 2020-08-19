<?php

namespace App\Http\Controllers;

use App\EmployeeMovement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DailyMovementTrackerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        return view('dailymovementtracker');
    }

    public function getdailymovement(Request $request) {

        if($request->ajax())
        {
            $data = EmployeeMovement::where('user_id', '=', Auth::user()->id)
                ->where('transact_date', '>=', $request->work_date_from)
                ->where('transact_date', '<=', $request->work_date_to)
                ->orderBy('transact_date')
                ->get();

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'places' => 'required',
            'people' => 'required',
            'modeoftranspo' => 'required',
        );
        $messages = [
            'transact_date.required' => 'Date is required.',
            'places.required' => 'Places you have gone is required.',
            'people.required' => 'Names of people you have met is required.',
            'modeoftranspo.required' => 'Mode of transportation is required.',
        ];

        $error = Validator::make($request->all(), $rules, $messages);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

////        check if previous date already entered, only if user has records
//        $data = EmployeeMovement::where('user_id', '=', Auth::user()->id)->get();
//        if(!empty($data)) {
//            $previous_date = Carbon::createFromFormat('Y-m-d', $request->transact_date)
//                ->subDays(1)
//                ->format('Y-m-d');
//
//            $employeeactivity = EmployeeMovement::where('user_id', '=', Auth::user()->id)
//                ->where('transact_date', '=', $previous_date)
//                ->get();
//
//            if(!empty($employeeactivity->transact_date)) {
//
//            }
//            else {
//                $error->errors()->add('transact_date', 'Please enter movement for the previous date before entering for this date.');
//                return response()->json(['errors' => $error->errors()->all()]);
//            }
//        }

        $transact_date = Carbon::createFromFormat('Y-m-d', $request->transact_date)->format('Y-m-d');

        $data = EmployeeMovement::where('user_id', '=', Auth::user()->id)
            ->where('transact_date', '=', $transact_date)
            ->get();

        if(!empty($data->id)) {
            $error->errors()->add('transact_date', 'A movement record is already present for this date. Enter another date.');
            return response()->json(['errors' => $error->errors()->all()]);
        }

        if(empty($request->hidden_id)) {
            $data = new EmployeeMovement();
            $data->user_id = Auth::user()->id;
        }
        else {
            $data = EmployeeMovement::where('user_id', '=',  Auth::user()->id)
                ->where('id', '=', $request->hidden_id)
                ->first();
        }
        $data->transact_date = Carbon::createFromFormat('Y-m-d', $request->transact_date)->format('Y-m-d');
        $data->places = $request->places;
        $data->people = $request->people;
        $data->modeoftranspo = $request->modeoftranspo;
        $data->save();

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
        if(request()->ajax()) {
            $data = EmployeeMovement::findOrFail($id);
            return response()->json(['result' => $data,]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeMovement $employeeActivity)
    {
        //
        $rules = array(
            'transact_date' => 'required|date|date_format:Y-m-d',
            'places' => 'required',
            'people' => 'required',
            'modeoftranspo' => 'required',
        );
        $messages = [
            'transact_date.required' => 'Date is required.',
            'places.required' => 'Places you have gone is required.',
            'people.required' => 'Names of people you have met is required.',
            'modeoftranspo.required' => 'Mode of transportation is required.',
        ];

        $error = Validator::make($request->all(), $rules, $messages);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $data = EmployeeMovement::where('user_id', '=',  Auth::user()->id)
            ->where('id', '=', $request->hidden_id)
            ->first();
        if(!empty($data->id)) {
            $data->transact_date = Carbon::createFromFormat('Y-m-d', $request->transact_date)->format('Y-m-d');
            $data->places = $request->places;
            $data->people = $request->people;
            $data->modeoftranspo = $request->modeoftranspo;
            $data->save();
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
        $data = EmployeeMovement::findOrFail($id);
        $data->delete();
    }

}
