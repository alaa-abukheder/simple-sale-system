<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientReuest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = User::all();
        return response()->json($clients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientReuest $request)
    {
        $data = $request->validated();
        $data["password"] = Hash::make($data["password"]);
        $client = User::create($request->validated());
        return response()->json($client, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $client)
    {
        return response()->json($client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientReuest $request, User $client)
    {
        
        $data = Arr::except($request->validated(),["email","role"]);
        $data["password"] = Hash::make($data["password"]);
        $client->update($data);
        return response()->json($client);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $client)
    {
        $client->delete();
        return response()->json(null, 204);
    }
}
