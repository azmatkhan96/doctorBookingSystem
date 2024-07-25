<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    public static function getListofAppointment()
    {
        $data = Appointment::join('mst_statuses', 'mst_statuses.id', 'appointments.statusID')
            ->select('appointments.id', 'appointments.patientName', 'appointments.date', 'appointments.time', 'mst_statuses.status')
            ->orderBy('appointments.id', 'desc')
            ->paginate(10);
        return $data;
    }
}
