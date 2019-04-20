<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Get users
        $users = User::latest()->paginate(5);
    
        //Return collection of works as a resource
        return UserResource::collection($users);
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
    
    public function create(Request $request){
            
        $request->validate([
                // 'division_id' => 'integer',
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string'
            ]);
            $user = new User([
                // 'division_id' => $request->division,
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
        
            $user->save();

            return response()->json([
                'message' => 'Successfully created user!'
            ], 201);
    
        }
    
    public function firstOrResponse($field, $value) {
        return ($work = Work::where($field, 'LIKE', $value)->get()) instanceof Work ?
            new WorkResource($work) : response()->json([
                "Final work not found with $field: $value"
            ], 404);
    }
    public function searchLIKE($field, $value) {
        return ($work = Work::where($field, 'LIKE', $value)->get()) instanceof Work ?
            new WorkResource($work) : response()->json([
                "Final work not found with $field: $value"
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
        $user = User::where('id', '=', $id)->firstOrFail();
        return new UserResource($user);
    }

    public function getRole($id){
        
    }

    public function profile()
    { 
        return auth('api')->user()->load('roles');
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
        $user = User::where('id', '=', $id)->firstOrFail();

        $user->fill($request->all())->save();

        $role = $request->roles;


        if ( ! $user->hasRole($role)) {
            // verwijder alle voorgaande rollen
            $user->removeRoles();

            // voeg nieuwe rol toe
            $user->assignRole($role);
        }

        return new UserResource($user);
        
      
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
            'message' => User::where('id', '=', $id)->firstOrFail()->delete() ? 'Success.' : 'Failed.'
        ]);
    }

}
