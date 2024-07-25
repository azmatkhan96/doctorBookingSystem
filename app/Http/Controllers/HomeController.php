<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MstStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = Appointment::getListofAppointment();
        $status = MstStatus::get();
        return view('home', compact('data', 'status'));
    }

    public function store(Request $request)
    {
        $isExists = Appointment::where('date', $request->date)->where('time', $request->time)->exists();
        if ($isExists) {
            session()->flash('error', 'Appointment Already Taken');
            return redirect('/home');
        }
        Appointment::insert([
            'patientName' => $request->name,
            'date' => $request->date,
            'time' => $request->time,
            'created_at' => now(),
        ]);

        session()->flash('status', 'Your Appointment is booked Successfully');
        return redirect('/home');
    }

    public function filterData(Request $request)
    {
        $data = Appointment::query();

        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay();
            $data->whereBetween('date', [$startDate, $endDate]);
        }

        $data = $data->paginate(10);

        return view('home', compact('data'));
    }

    public function updateStatus(Request $request)
    {

    }
}
