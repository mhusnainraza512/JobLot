<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Str;
// use models
use App\Models\Job;
use App\Models\SavedJob;
use App\Models\EmployeeAppliedJob;

// use facades
use Auth;

class JobController extends Controller
{
    // create a new job
    public function post_job(Request $request)
    {
        $job= New Job;
        $job->business_cat_id = $request->business_cat_id;
        $job->employer_id = $request->employer_id;
        $job->title = Str::ucfirst($request->title);
        $job->slug = Str::slug($request->title);
        $job->salary = $request->salary;
        $job->job_type = $request->job_type;
        $job->job_qualification = $request->job_qualification;
        $job->state_authorized = $request->state_authorized;
        $job->description = $request->description;
        $job->comp_name= user()->comp_name;
        $job->comp_location = user()->comp_location;
        $job->salary_schedual = $request->salary_schedual;
        $job->job_schedual_from = date('Y-m-d H:i:s',strtotime($request->job_schedual_from));
        $job-> job_schedual_to = date('Y-m-d H:i:s',strtotime($request->job_schedual_to));
        $job->save();

        
        return response()->json([
            'PostJob' => $job,
        ], 200);
    }

    // update a job
    public function update_job(Request $request)
    {
        $job= Job::where([['id', $request->job_id], ['employer_id', user()->id]])->first();
        $job->business_cat_id = $request->business_cat_id ? $request->business_cat_id : $job->business_cat_id;
        $job->employer_id = $request->employer_id ? $request->employer_id : $job->employer_id;
        $job->title = Str::ucfirst($request->title) ? Str::ucfirst($request->title) : $job->title ;
        $job->slug = Str::slug($request->title) ? Str::slug($request->title) : $job->slug ;
        $job->salary = $request->salary ? $request->salary : $job->salary;
        $job->job_type = $request->job_type ? $request->job_type : $job->job_type;
        $job->job_qualification = $request->job_qualification ? $request->job_qualification : $job->job_qualification;
        $job->state_authorized = $request->state_authorized ? $request->state_authorized : $job->state_authorized;
        $job->description = $request->description ? $request->description : $job->description;
        $job->comp_name= $request->comp_name ? $request->comp_name : user()->comp_name;
        $job->comp_location = $request->comp_location ? $request->comp_location : user()->comp_location;
        $job->salary_schedual = $request->salary_schedual ? $request->salary_schedual : $job->salary_schedual;
        $job->job_schedual_from = date('Y-m-d H:i:s',strtotime($request->job_schedual_from ? $request->job_schedual_from : $job->job_schedual_from));
        $job-> job_schedual_to = date('Y-m-d H:i:s',strtotime($request->job_schedual_to ? $request->job_schedual_to : $job->job_schedual_to));
        $job->save();

        return response()->json([
            'UpdatedJob' => $job,
        ], 200);
    }

    // remove job
    public function remove_job(Request $request)
    {
        $job = Job::where([['id', $request->job_id], ['employer_id', user()->id]])->first();

        if($job->delete()){
            return response()->json([
                'success' => true,
                'message' => 'Job has been deleted successfuly!'
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong please try again!'
        ], 400);
    }

    // get job list
    public function all_job()
    {
        $user = user();
        $jobs = Job::orderBy('id', 'DESC')->get();

        $appliedJob = EmployeeAppliedJob::orderBy('created_at', 'DESC')->where('user_id', $user->id)
        ->pluck('job_id');

        $savedJob = SavedJob::orderBy('created_at', 'DESC')
        ->where('user_id', $user->id)
        ->pluck('job_id');

        // check logged in user applied on job or not
        $jobs = getJob($jobs, $user->id);

        return response()->json([
            'count' => count($jobs),
            'all_jobs' => $jobs,
        ], 200);
    }

    // employee saved job
    public function employee_saved_job(Request $request){
        $user = Auth::user();
        $user->saved_jobs()->attach($request->job_id);

        $job = Job::find($request->job_id);
       
        return response()->json([
            'saved_jobs' => $user->saved_jobs->sortByDesc('created_at')->first(),
        ], 200);
    }

    // get employee saved job list
    public function employee_get_saved_job(Request $request){
        $user = Auth::user();

        $saved_jobs = SavedJob::orderBy('id', 'DESC')->where('user_id', $user->id)->pluck('job_id');
        
        $jobs = formatJob($saved_jobs);
        $jobs = getJob($jobs, $user->id);
        
        return response()->json([
            'count' => count($jobs),
            'saved_jobs' => $jobs,
        ], 200);
        
    }
}