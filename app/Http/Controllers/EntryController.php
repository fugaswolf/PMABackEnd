<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entry;

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
        $entries = Entry::paginate(15);
    
        //Return collection of entries as a resource
        return EntryResource::collection($entries);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($user_id, $start_time,$end_time,$duration,$description)
    {
        $work = new Work($user_id,$start_time,$end_time,$duration,$description);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request)
    {
            $work = (new Work)->fill([
            'user_id'=> \Auth::user()->id,
            'start_time'=> $request->input('start_time'),
            'end_time'=> $request->input('end_time'),
            'duration'=> $request->input('duration'),
            'description' => $request->input('description'),
        ]);
        
        $work->save();
        /* if($request->exists("tags")){
            $tagstring = str_replace('"','',substr($request->input("tags"),1,-1));
            $tagarray = explode(",",$tagstring);
            foreach ($tagarray as $key => $val) {
                $taglist = Tags::where("tag", $val)->first();
                if ($taglist != null) {
                    $tagid = $taglist->toArray()["id"];
                    Work::orderBy('created_at', 'desc')->first()->tags()->attach($tagid);
                } else {
                    $thetag = (new Tags)->fill([
                        'tag' => $val
                    ]);
                    $thetag->save();
                    Work::orderBy('created_at', 'desc')->first()->tags()->save($thetag);
                }
            }
        } */
        return "succes";
        //return print_r($thetag);
        //return new WorkResource($work);
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
    public function show($id)
    {
        $entry = Entry::where('id', '=', $id)->firstOrFail();
        // $entry = Entry::with('tags')->where('id', '=', $id)->firstOrFail();
        //dd($work);
        return new EntryResource($entry);
        //return $this->firstOrResponse('finalworkID', $id);
    }
    public function showByProject($project)
    {
        return Entry::where('project_id', 'LIKE', '%'.$project.'%')->get();
        /*return $this->firstOrResponse('finalworkTitle',  $title);*/
    }
    public function showByDivision($division)
    {
        return Entry::where('division', 'LIKE', '%'.$division.'%')->get();
        /*return $this->firstOrResponse('departement', $departement);*/
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
        //dump($request->all());
        $work = Work::where('finalworkID', '=', $id)->firstOrFail();
        $work->update([
        'finalworkURL'=>   $request->input('finalworkURL'),
        'finalworkTitle'=>   $request->input('finalworkTitle'),
        'finalworkDescription'=>  $request->input('finalworkDescription'),
        'finalworkAuthor'=>  $request->input('finalworkAuthor'),
        'departement' => $request->input('departement'),
        'finalworkField' => $request->input('finalworkField'),
        'finalworkYear'=>  $request->input('finalworkYear'),
        'finalworkPromoter'=>  $request->input('finalworkPromoter')
        ]);
        $work->save();
        return new WorkResource($work);
      
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
            'message' => Work::where('finalworkID', '=', $id)->firstOrFail()->delete() ? 'Success.' : 'Failed.'
        ]);
    }
    public function search(Request $request)
    {
        $works = (new Work)->newQuery()->with('tags');
        $filterArray = $this->checkFilters($request);
        $keywords = $this->divideSearchQuery($request);
        $results = $works->where(function($query) use ($request, $filterArray, $keywords) {
            $query->where($filterArray);
            $query->where(function ($query) use ($keywords) {
                for ($i = 0; $i < count($keywords); $i++) {
                    $query->where('finalworkTitle', 'LIKE', '%' . $keywords[$i] . '%');
                    $query->orWhere('finalworkDescription', 'LIKE', '%' . $keywords[$i] . '%');
                    $query->whereTagsLike($keywords[$i]);
                }
            });
        })->get();
        return $results;
    }
    private function divideSearchQuery($request) {
        if ($request->has('q')) {
            $keyword = $request->input('q');
            $keywords = explode(" ", $keyword);
        } else {
            $keywords = [];
        }
        return $keywords;
    }
    private function checkFilters($request) {
        $filterArray = array();
        if ($request->has('department')) {
            array_push($filterArray, ['departement', '=', $request->Input('department')]);
        }
        if ($request->has('author')) {
            array_push($filterArray, ['finalworkAuthor', 'LIKE', '%' . $request->input('author') . '%']);
        }
        if ($request->has('field')) {
            array_push($filterArray, ['finalworkField', 'LIKE',  '%' . $request->input('field') . '%']);
        }
        if ($request->has('promotor')) {
            array_push($filterArray, ['finalworkPromoter', 'LIKE',  '%' . $request->input('promotor') . '%']);
        }
        if ($request->has('maxYear') || $request->has('minYear')) {
            $maxYear = $request->has('maxYear') == null ? ['finalworkYear', '<=', date('Y')] : ['finalworkYear', '<=', $request->input('maxYear')];
            $minYear = $request->has('minYear') == null ? ['finalworkYear', '>=', 1980] : ['finalworkYear', '>=', $request->input('minYear')];
            array_push($filterArray, $minYear);
            array_push($filterArray, $maxYear);
        }
        return $filterArray;
    }
}
