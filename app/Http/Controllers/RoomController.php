<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::with('category')->latest()->paginate(5);

        return response()->json([
            'message' => $rooms ? 'Successfully retrieved rooms data' : 'No data',
            'data' => $rooms
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRoomRequest $request)
    {
        $data = $request->validated();
        // check if incoming request has image uploaded
        if($request->hasFile('image')) {
            // upload the image into $data
            $data['image'] = $request->file('image')->store('rooms', 'public');
        }

        $room = Room::create($data);
        return response()->json([
            'message' => 'Successfullyu created room data',
            'data' => $room
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        return response()->json([
            'message' => 'Successfully retriev room data',
            'data' => $room
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        $data = $request->validated();

        if($request->hasFile('image')) {
            // check if room already has image
            if($room->image) {
                Storage::disk('public')->delete($room->image);
            }
            $data['image'] = $request->file('image')->store('rooms', 'public');
        } else {
            $data['image'] = $room->image;
        }

        $room->update($data);
        return response()->json([
            'message' => 'Successfully update room data',
            'data' => $room
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        Storage::disk('public')->delete($room->image);
        $room->delete();

        return response()->json([
            'message' => 'Room successfuly deleted'
        ], 201);
     }
}
