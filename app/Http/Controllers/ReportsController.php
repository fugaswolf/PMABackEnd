<?php

namespace App\Http\Controllers;

use App\User;
use App\Entry;
use App\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Htpp\Resources\EntryResource;
use App\Htpp\Resources\ProjectResource;


class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function totalEmployees()
    {
        //Get users
        $users = User::count();
        //Return collection of works as a resource
        return $users;
    }

    public function totalProjects()
    {
        //Get users
        $projects = Project::count();
        //Return collection of works as a resource
        return $projects;
    }

    public function totalProjectsSales()
    {
        //Get users
        $sales = Project::sum('budget');
        //Return collection of works as a resource
        return $sales;
    }
    public function totalEntries()
    {
        //Get users
        $entries = Entry::count();
        //Return collection of works as a resource
        return $entries;
    }

    public function totalTodaysEntries()
    {
        // $todays = Carbon::today();
        $today = Carbon::today()->toDateString();
        //Get users
        $entries = Entry::where('created_at', 'like', '%'.$today.'%')->count();
        //Return collection of works as a resource
        return $entries;
    }
    
    public function totalTodaysEntriesTime()
    {
        $today = Carbon::today()->toDateString();

        // $seconds = Entry::where('created_at', 'like', '%'.$today.'%')->sum(DB::raw("TIME_TO_SEC(duration)"));

        $time = DB::table('entries')->select(DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(duration))) as my_sum'))
                                    ->where('created_at', 'like', '%'.$today.'%')
                                    ->get()
                                    ->first()
                                    ->my_sum;

        $split = explode(':', $time);

        return "{$split[0]} hours and {$split[1]} minutes";
    }

    public function totalEntriesTime()
    {
        $time = DB::table('entries')->select(DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(duration))) as my_sum'))
                                    ->get()
                                    ->first()
                                    ->my_sum;

        $split = explode(':', $time);

        return "{$split[0]} hours and {$split[1]} minutes";
    }

    /* ----------MOBILE REPORTS----------*/

    public function totalEntriesTimeMobile()
    {
        $user = Auth::user();
        $time = DB::table('entries')->select(DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(duration))) as my_sum'))
                                    ->where('user_id', '=', $user->id)
                                    ->get()
                                    ->first()
                                    ->my_sum;

        $split = explode(':', $time);

        if($time != null){
            return "{$split[0]} hours and {$split[1]} minutes";
        }
        else {
            return "No todays entries";
        }
    }


    public function totalTodaysEntriesTimeMobile()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        // $seconds = Entry::where('created_at', 'like', '%'.$today.'%')->sum(DB::raw("TIME_TO_SEC(duration)"));

        $time = DB::table('entries')->select(DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(duration))) as my_sum'))
                                    ->where([
                                        ['created_at', 'like', '%'.$today.'%'],
                                        ['user_id', '=', $user->id]
                                    ])
                                    ->get()
                                    ->first()
                                    ->my_sum;
        
                                    
        // str_replace("0","",$time);
        $split = explode(':', $time);

        if($time != null){
            return "ltrim({$split[0]}, '0') hours and {$split[1]} minutes";
        }
        else {
            return "No todays entries";
        }
    }

    public function totalTodaysEntriesMobile()
    {
        $user = Auth::user();
        // $todays = Carbon::today();
        $today = Carbon::today()->toDateString();
        //Get users
        $entries = Entry::where('created_at', 'like', '%'.$today.'%')
        ->where('user_id', '=', $user->id)
        ->count();
        //Return collection of works as a resource
      
        if($entries != null){
            return $entries;
        }
        else {
            return "No entries";
        }
    }

    public function totalEntriesMobile()
    {
        $user = Auth::user();
        $entries = Entry::where('user_id', '=', $user->id)->count();
        //Return collection of works as a resource
        if($entries != null){
            return $entries;
        }
        else {
            return "No entries";
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
}
