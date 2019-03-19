<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use App\Http\Resources\CustomerResource;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Get customers
        $customers = Customer::latest()->paginate(5);
    
        //Return collection of works as a resource
        return CustomerResource::collection($customers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){
            
        $request->validate([
                'division_id' => 'integer',
                'name' => 'required|string',
                'comment' => 'required|string',
                'country' => 'required|string',
                'address' => 'required|string',
                'phone' => 'required|string',
                'email' => 'required|string',
                'status' => 'required|integer',
            ]);
            $customer = new Customer([
                'division_id' => $request->division_id,
                'name' => $request->name,
                'comment' => $request->comment,
                'country' => $request->country,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'status' => $request->status,
            ]);
            $customer->save();
            return response()->json([
                'message' => 'Successfully created customer!'
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
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dump($request->all());
        $customer = Customer::where('id', '=', $id)->firstOrFail();

        $customer->fill($request->all())->save();
        return new CustomerResource($customer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response()->json([
            'message' => Customer::where('id', '=', $id)->firstOrFail()->delete() ? 'Success.' : 'Failed.'
        ]);
    }
}
