@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @elseif(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="ibox">
                    <div class="card-body card card-custom shadow-sm my-8">
                        <div class="d-flex text-right justify-content-between">
                            <!--begin::Page Heading-->
                            <div class="d-flex align-items-baseline flex-wrap mr-2 mt-2">
                                <!--begin::Page Title-->
                                <h3 class="pr-3 font-weight-bold">List of Appointments</h3>
                                <!--end::Page Title-->
                            </div>
                            <div class="d-flex text-right mr-4">
                                <!-- Button trigger modal-->
                                @if (Auth::user()->roleID == 2)
                                    <button class="btn btn-sm btn-primary mr-3" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop"> <i class="fa fa-plus icon-sm"></i>Create
                                        Appointment</button>
                                @endif
                            </div>
                            <!--end::Page Heading-->
                        </div>
                    </div>

                    <div class="ibox-content mt-3">
                        <div class="card-body card card-custom shadow-sm">
                            <div>
                                <form action="{{ route('appointments.index') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="start_date">Start Date:</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control"
                                            value="{{ request('start_date') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="end_date">End Date:</label>
                                        <input type="date" id="end_date" name="end_date" class="form-control"
                                            value="{{ request('end_date') }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-2">Apply Filter</button>
                                </form>
                            </div>
                            <div class="table-responsive mt-4" id="tableContent">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Name of Patient</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $item->patientName }}</td>
                                                <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $item->date)->format('d-m-Y') }}
                                                </td>
                                                <td>{{ Carbon\Carbon::createFromFormat('H:i', $item->time)->format('g:i A') }}
                                                </td>
                                                <td>{{ $item->status }}</td>
                                                <td>

                                                    <button type="button" class="btn btn-warning mr-5 statusChange"
                                                        onclick="handleButtonClick('{{ $item->id }}')">
                                                        Update Status</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-------------------------------------------------- Create Appointment model ----------------------------------->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Create Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('make.appointment') }}">
                        @csrf
                        <div class="form-group">
                            <label for="fullName">Full Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter your full name"
                                required>
                        </div>
                        <div class="form-group mt-2">
                            <label for="date">Preferred Date</label>
                            <input type="date" class="form-control" name="date" required>
                        </div>
                        <div class="form-group mt-2">
                            <label for="time">Preferred Time</label>
                            <input type="time" class="form-control" name="time" required>
                        </div>
                        <button type="submit" class="btn btn-success btn-block mt-3">Submit Appointment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!---------------------------------------- Status Change model ------------------------------------------------->
    <div class="modal fade" id="statusModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Status </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        @csrf
                        <input type="hidden" name="clicked_row_id" id="clicked_row_id" value="">
                        <select class="form-control">
                            @foreach ($status as $val)
                                @if (Auth::user()->roleID == 2 && $val->status != 'approve' && $val->status != 'pending')
                                    <option value="{{ $val->id }}">{{ $val->status }}</option>
                                @elseif(Auth::user()->roleID == 1 && $val->status != 'postpone' && $val->status != 'pending')
                                    <option value="{{ $val->id }}">{{ $val->status }}</option>
                                @endif
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-success mt-4">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        function handleButtonClick(rowId) {
            document.getElementById('clicked_row_id').value = rowId;
            $('#statusModal').modal('show');
        }
    </script>
@endsection
