<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Models\JobSalaryRange;

class JobSalaryRangeController extends Controller
{
    public function listSalaryRanges()
    {
        $ranges = JobSalaryRange::orderBy('id', 'desc')->get();

        return view('backend.pages.salaryRange.list', compact('ranges'));
    }

    public function createSalaryRange()
    {
        return view('backend.pages.salaryRange.create');
    }

    public function storeSalaryRange(Request $request)
    {
        $rules = [
            'range' => 'required|string|max:255|unique:job_salary_ranges,range',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        JobSalaryRange::create([
            'range' => $request->range
        ]);

        return redirect()->route('listSalaryRanges')->with('success', 'Record Added Successfully.');
    }

    public function editSalaryRange($id)
    {
        $range = JobSalaryRange::find($id);

        if($range == null)
        {
            return redirect()->route('listSalaryRanges')->with('error', 'No Record Found.');
        }

        return view('backend.pages.salaryRange.edit', compact('range'));
    }

    public function updateSalaryRange(Request $request,$id)
    {
        $range = JobSalaryRange::find($id);

        if($range == null)
        {
            return redirect()->route('listSalaryRanges')->with('error', 'No Record Found.');
        }

        $rules = [
            'range' => 'required|string|max:255|unique:job_salary_ranges,range,'.$range->id,
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        JobSalaryRange::find($id)->update([
            'range' => $request->range
        ]);

        return redirect()->route('listSalaryRanges')->with('success','Record Successfully Updated');

    }

    public function deleteSalaryRange(Request $request,$id){
        $range = JobSalaryRange::find($id);

        if(empty($range)) {
            return response()->json(['status' => 0]);
        }

        DB::table('job_salary_ranges')->where('id',$request->id)->delete();

        return response()->json(['status' => 1]);
    }
}
