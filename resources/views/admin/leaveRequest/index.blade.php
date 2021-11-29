@extends('layouts.admin.app')

@section('content')
<div class="my-table">
    <a href="/leave-request/create"><button class="btn btn-primary float-right">Leave Request</button></a>
    <h3 class="text-success text-center">Leave Request List</h3>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" class="pl-4">S.N</th>
                    <th scope="col">Employee</th>
                    <th scope="col">Leave Type</th>
                    <th scope="col">Start Date</th>
                    <th scope="col">End Date</th>
                    <th scope="col">Days</th>
                    <th scope="col">Reason</th>
                    <th scope="col">State</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($leaveRequests as $leaveRequest)
                <tr>
                    <th scope="row" class="pl-4">{{ $loop->iteration }}</th>
                    <td>{{ $leaveRequest->employee_id }}</td>
                    <td>{{ $leaveRequest->leave_type }}</td>
                    <td>{{ $leaveRquest->start_date }}</td>
                    <td>{{ $leaveRquest->end_date }}</td>
                    <td>{{ $leaveRquest->days }}</td>
                    <td>{{ $leaveRquest->reason }}</td>
                    <td>{{ $leaveRquest->acceptance }}</td>
                    <td>
                        <form action="/leaveRequest/{{ $leaveRequest->id }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <th colspan=5 class="text-center">No LeaveRequest Found</th>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $leaveRequests->links() }}
    </div>
</div>
@endsection