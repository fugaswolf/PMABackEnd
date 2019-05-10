<?php

namespace App\Http\Controllers;

use Auth;
use App\Entry;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\EntryResource;

class EntryController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        //Get entries
        $entries = Entry::paginate(10);
    
        //Return collection of entries as a resource
        return EntryResource::collection($entries);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'activity_id' => 'integer|required',
            'description' => 'required|string',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            //'duration' => 'nullable|string'
        ]);

        //$duration = $request->has('duration') && !is_null($request->input('duration')) ? : Entry::parseDuration($request->input('start_date'), $request->input('end_date'));
        
        (new Entry)->create([
            'activity_id' => $request->activity_id,
            'user_id' => $user->id,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            //'duration' => $duration
        ]);
        
        return response()->json([
            'message' => 'Successfully created entry!'
        ], 201);
    }

    public function firstOrResponse($field, $value) {
        return ($entry = Entry::where($field, 'LIKE', $value)->get()) instanceof Entry ?
            new EntryResource($entry) : response()->json([
                "Entry not found with $field: $value"
            ], 404);
    }

    public function searchLIKE($field, $value) {
        return ($entry = Entry::where($field, 'LIKE', $value)->get()) instanceof Entry ?
            new EntryResource($entry) : response()->json([
                "Entry not found with $field: $value"
            ], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = Auth::user();
        $entry = Entry::with('activity:id,name,project_id', 'activity.project:id,name,customer_id', 'activity.project.customer:id,name')->where('user_id', '=', $user->id)->latest()->get();


        return new EntryResource($entry);
    }

    public function showPG()
    {
        $user = Auth::user();
        $entry = Entry::with('activity', 'activity.project')->where('user_id', '=', $user->id)->latest()->paginate(15);
        dd($entry);
        return new EntryResource($entry);
    }

    public function showAll(){
        $user = Auth::user();

        $entries = null;
        
        if ($user->hasAnyRole('admin|manager|hr')) {
            $entries = Entry::with('activity', 'activity.project', 'user')->latest()->paginate(15);
        } else {
            $entries = Entry::with('activity', 'activity.project')->where('user_id', '=', $user->id)->latest()->paginate(15);
        }

        return EntryResource::collection($entries);
    }

    public function showByProject($project)
    {
        return Entry::where('project_id', 'LIKE', '%'.$project.'%')->get();

    }

    public function showByDivision($division)
    {
        return Entry::where('division', 'LIKE', '%'.$division.'%')->get();

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
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request/*, $id*/)
    // {

    //     $user = Auth::user();
        
    //     $entry = Entry::where('id', '=', $request->input('id'))->firstOrFail();

    //     //dd($request->all());

    //     $entry->fill($request->all())->save();
    
    //     return new EntryResource($entry);
      
    // }


    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        $entry = Entry::where('id', '=', $request->input('id'))->firstOrFail();
        $entry->fill([
            'activity_id'=>   $request->input('activity_id'),
            'user_id'=>   $user->id,
            'description'=>  $request->input('description'),
            'start_date'=>  $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ]);
    
        $entry->save();
        return new EntryResource($entry);
      
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response()->json([
            'message' => Entry::where('id', '=', $id)->firstOrFail()->delete() ? 'Success.' : 'Failed.'
        ]);
    }

    /**
     * Sync entries from json data
     */
    public function syncAll(Request $request) {
        $user = Auth::user();
        $data = $request->json()->get('data');

        foreach (/*$actualData*/ $data as $entry){
            \App\Entry::updateOrCreate(
                [
                    'id' => $entry['id'] ?? null
                ],
                [
                    'user_id' => $user->id,
                    'start_date' => $entry['start_date'],
                    'end_date' => $entry['end_date'],
                    'duration' => $entry['duration'],
                    'description' => $entry['description'],
                    'activity_id' => $entry['activity_id']
                ]
            );
        }
        
        return response()->json([
            'message' => 'Data succesfully synced'
        ]);
    }
}
