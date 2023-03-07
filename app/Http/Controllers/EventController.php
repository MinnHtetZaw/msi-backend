<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

use App\Http\Resources\EventResource;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'data'=>Event::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $event = new Event();
        $event->event_code = $request->event_code;
        $event->title = $request->title;
        $event->description =$request->description;

            $photo = $request->file('photo');

                $newName = uniqid().".".$photo->getClientOriginalName();
                $photo->storeAs('public/photo',$newName);
                $event->photo = $newName;

        $event->save();
        return response()->json([
            'data' => 'Success',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        return new EventResource($event);
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
    public function update(Request $request, Event $event)
    {
        if($request->photo){
            $oldPhoto = asset('storage/photo/'.$event->photo);
            Storage::delete($oldPhoto);
            $event->photo = null;
            $event->update();
            return response()->json([
                'data'=>'Success'
            ]);
        }


        $event->event_code = $request->event_code;
        $event->title = $request->title;
        $event->description =$request->description;

        if ($request->file('photo')){
            if(!is_null($event->photo)){
                $oldPhoto = asset('storage/photo/'.$event->photo);
                Storage::delete($oldPhoto);
            }
            $photo = $request->file('photo');

                $newName = uniqid().".".$photo->getClientOriginalName();
                $photo->storeAs('public/photo',$newName);
                $event->photo = $newName;
        }
        $event->update();
        return response()->json([
            'data' => 'Success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        if(!is_null($event->photo)){
            $oldPhoto = asset('storage/photo/'.$event->photo);
            Storage::delete($oldPhoto);
        }
        $event->delete();
        return response()->json([
            'data'=>'Success'
        ]);
    }
}
