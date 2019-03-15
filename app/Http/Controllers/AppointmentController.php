<?php

namespace App\Http\Controllers;

use App\User;
use App\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // return response()->json([], 200);

        $start = "";
        $end = "";
        if($request->get('start') && $request->get('end')){
            $start = $request->get('start');
            $end = $request->get('end');
        }

        $apos = Appointment::whereDate('start_date', '>=', $start)
            ->whereDate('end_date', '<=', $end)
            ->get();

        $users = User::pluck('id')->all();

        $appointments = [];
        foreach($apos as $apo){
            $aposdata = $apo->users()->get();

            foreach($aposdata as $apodata){
                if(in_array($apodata->id, $users)) {
                    $i = Appointment::where('id', $apodata->pivot->appointment_id)->first();
                    $i['user_id'] = $apodata->pivot->user_id;
                    $appointments[] = $i;
                }
            }
        }
        return response()->json($appointments, 200);
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

        $appointment = new Appointment();
        $appointment->title = $request["appointment-title"];
        $appointment->description = $request["appointment-description"];
        $appointment->start_date = $request["start-date"];
        $appointment->start_time = $request["start-time"];
        $appointment->end_date = $request["start-date"];
        $appointment->end_time = $request["end-time"];
        $appointment->color = $request["color"];
        $appointment->text_color = $request["text-color"];
        $appointment->save();

        $appointment->users()->sync($request["team-user-id"]);

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appointment $appointment)
    {

        $appo = Appointment::findOrFail($appointment->id);
        $appo->title = $request["appointment-title"];
        $appo->description = $request["appointment-description"];
        $appo->start_date = $request["start-date"];
        $appo->start_time = $request["start-time"];
        $appo->end_date = $request["start-date"];
        $appo->end_time = $request["end-time"];
        $appo->color = $request["color"];
        $appo->text_color = $request["text-color"];
        $appo->save();

        $appo->users()->sync($request["team-user-id"]);

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        $appo = Appointment::findOrFail($appointment->id);
        $appo->users()->detach();

        return redirect('/');
    }
}
