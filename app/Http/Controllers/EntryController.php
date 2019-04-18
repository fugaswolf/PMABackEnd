<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entry;
use App\Http\Resources\EntryResource;
use Auth;

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
            'duration' => 'nullable|string'
        ]);

        $duration = $request->has('duration') && !is_null($request->input('duration')) ? : Entry::parseDuration($request->input('start_date'), $request->input('end_date'));
        
        (new Entry)->create([
            'activity_id' => $request->activity_id,
            'user_id' => $user->id,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'duration' => $duration
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

        // projectnaam, customernaam, activitynaam
        // waar zit de project fzo ? 
        // project zou je kunnen ophalen via de activity (er is een project_id in de tabel activities, en customer haal je op via project fso (customer_id))

        // ok dit moet werken
        $entry = Entry::with('activity:id,name,project_id', 'activity.project:id,name,customer_id', 'activity.project.customer:id,name')->where('user_id', '=', $user->id)->latest()->get();


        return new EntryResource($entry);
    }

    public function showPG()
    {
        $user = Auth::user();
        $entry = Entry::where('user_id', '=', $user->id)->latest()->paginate(15);
        dd($entry);
        return new EntryResource($entry);
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
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        $entry = Entry::where('id', '=', $id)->firstOrFail();

        $entry->fill([
            'activity_id'=>   $request->input('activity_id'),
            'user_id'=>   $user->id, // toch ? jup
            'description'=>  $request->input('description'),
            'start_date'=>  $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ]);

        if ($request->has('duration') && !is_null($duration = $request->input('duration'))) {
            $entry->fill(compact('duration'));
        }

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
}
