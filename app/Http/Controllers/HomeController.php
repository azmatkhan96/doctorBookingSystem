<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MstStatus;
use Auth;
use Carbon\Carbon;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
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
        $this->middleware(VerifyCsrfToken::class);
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
    // ------------------------------------------ CREATE NEW APPOINTMENT -------------------------------------
    public function store(Request $request)
    {
        try
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
                'userID' => Auth::user()->id,
                'created_at' => now(),
            ]);

            session()->flash('status', 'Your Appointment is booked Successfully');
            return redirect('/home');
        } catch (\Illuminate\Session\TokenMismatchException $e) {

            session()->flash('error', 'CSRF token mismatch. Please try again.');
            return redirect('/home');
        }
    }
    // ----------------------- FILTER BY DATE APPOINTMENT ------------------------------------
    public function filterData(Request $request)
    {
        try
        {
            $data = Appointment::join('mst_statuses', 'mst_statuses.id', 'appointments.statusID')
                ->select('appointments.id', 'appointments.patientName', 'appointments.date', 'appointments.time', 'mst_statuses.status')
                ->orderBy('appointments.id', 'desc');

            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay();
                $data->whereBetween('date', [$startDate, $endDate]);
                $data->when(Auth::user()->roleID != 1, function ($query) {
                    $query->where('userID', Auth::user()->id);
                });
            }

            $data = $data->paginate(10);
            $status = MstStatus::get();
            return view('home', compact('data', 'status'));
        } catch (\Illuminate\Session\TokenMismatchException $e) {

            session()->flash('error', 'CSRF token mismatch. Please try again.');
            return redirect('/home');
        }
    }
    // --------------------------------------------- STATUS UPDATE ---------------------------------
    public function updateStatus(Request $request)
    {
        try {

            Appointment::where('id', $request->clicked_row_id)->update([
                'statusID' => $request->selectedStatus,
                'updated_at' => now(),
            ]);

            session()->flash('status', 'Your Appointment Status Changed Successfully');
            return redirect('/home');

        } catch (\Illuminate\Session\TokenMismatchException $e) {
            session()->flash('error', 'CSRF token mismatch. Please try again.');
            return redirect('/home');
        }

    }
}
