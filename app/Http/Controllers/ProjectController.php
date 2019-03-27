<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use App\Http\Resources\ProjectResource;

class ProjectController extends Controller
{
    public function indexWithActivities()
    {
         $projectsWithActivities = Project::with('activities')->latest()->paginate(5);

         // @TODO fix de resource later
         return $projectsWithActivities;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Get projects
        $projects = Project::with('customer')->latest()->paginate(10);
    
        //Return collection of projects as a resource
        return ProjectResource::collection($projects);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|integer',
            'name' => 'required|string',
            'comment' => 'required|string',
            'budget' => 'required|integer',
            'status' => 'required|integer',
        ]);
        $project = new Project([
            'customer_id' => $request->customer_id,
            'name' => $request->name,
            'comment' => $request->comment,
            'budget' => $request->budget,
            'status' => $request->status,
        ]);
        $project->save();
        return response()->json([
            'message' => 'Successfully created project!'
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
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dump($request->all());
        $project = Project::where('id', '=', $id)->firstOrFail();

        $project->fill($request->all())->save();
        return new ProjectResource($project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response()->json([
            'message' => Project::where('id', '=', $id)->firstOrFail()->delete() ? 'Success.' : 'Failed.'
        ]);
    }
}
