<?php

namespace App\Http\Controllers;

use App\User;
use App\Holiday;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $start = "";
        $end = "";
        if($request->get('start') && $request->get('end')){
            $start = $request->get('start');
            $end = $request->get('end');
        }

        $users = User::pluck('id')->all();

        $holidays = Holiday::whereDate('start_date', '>=', $start)
            ->whereDate('end_date', '<=', $end)
            ->whereIn('user_id', $users)
            ->get();

        $result = [];
        foreach($holidays as $holiday) {
            $start_date = new Carbon($holiday->start_date);
            $end_date = new Carbon($holiday->end_date);

            $result[] = [
                'resourceId' => $holiday->user_id,
                'start' => $start_date->toIso8601String(),
                'end' => $end_date->toIso8601String(),
            ];
        }
        return response()->json($result, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json([], 200);
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
     * @param  \App\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function show(Holiday $holiday)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function edit(Holiday $holiday)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Holiday $holiday)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function destroy(Holiday $holiday)
    {
        //
    }
}
