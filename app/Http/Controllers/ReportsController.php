<?php

namespace App\Http\Controllers;

use App\User;
use App\Entry;
use App\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
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
