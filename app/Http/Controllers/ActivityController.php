<?php

namespace App\Http\Controllers;

use App\Activity;
use Illuminate\Http\Request;
use App\Http\Resources\ActivityResource;
use App\Project;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         //Get projects
        //  $activities = Activity::with('project')->latest()->paginate(5);

         $projectsWithActivities = Project::with('activities')->latest()->paginate(5);

         // @TODO fix de resource later
         return $projectsWithActivities;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'project_id' => 'required|integer',
            'name' => 'required|string',
            'comment' => 'required|string',
            'status' => 'required|integer',
        ]);
        $activity = new Activity([
            'project_id' => $request->project_id,
            'name' => $request->name,
            'comment' => $request->comment,
            'status' => $request->status,
        ]);
        $activity->save();
        return response()->json([
            'message' => 'Successfully created activity!'
        ], 201);
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $activity = Activity::where('id', '=', $id)->firstOrFail();
        return new ActivityResource($activity);
    }

    public function showActivitiesByProject(Request $request)
    {
        $projectWithActivities = Activity::where('project_id', '=', $request->input('project_id'))->get();
        // return alleen de activiteiten
        return $projectWithActivities;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function edit(Activity $activity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         //dump($request->all());
         $activity = Activity::where('id', '=', $id)->firstOrFail();

         $activity->fill($request->all())->save();
         return new ActivityResource($activity);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response()->json([
            'message' => Activity::where('id', '=', $id)->firstOrFail()->delete() ? 'Success.' : 'Failed.'
        ]);
    }
}
