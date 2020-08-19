<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CompanyController extends Controller
{
    //
    public function index(Request $request) {
        if($request->ajax())
        {
            $data = Company::latest()->get();
            return Datatables::of($data)
                ->addColumn('action', function($data){
                    $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm">Edit</button>';
                    $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="edit" id="'.$data->id.'" class="delete btn btn-danger btn-sm">Delete</button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.company');
    }
    public function store(Request $request) {
        $rules = array(
            'name' => 'required|max:80',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'name' =>  $request->name,
        );

        Company::create($form_data);

        return response()->json(['success' => 'Record added successfully.']);
    }
    public function edit($id) {
        if(request()->ajax()) {
            $data = Company::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }
    public function update(Request $request, Company $company) {
        $rules = array(
            'name' => 'required|max:80',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'name' => $request->name,
        );

        Company::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Record successfully updated']);
    }
    public function destroy($id) {
        $data = Company::findOrFail($id);
        $data->delete();
    }
}


