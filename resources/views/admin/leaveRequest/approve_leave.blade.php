@extends('layouts.hr.app')

@section('title','Leave Request')

@section('content')
@include('layouts.basic.tableHead',["table_title" => 'Leave Applications'])

<table class="unit_table mx-auto drmDataTable">
    <thead>
        <tr class="table_title" style="background-color: #0f5288;">
            <th scope="col" class="ps-4">S.N</th>
                <th scope="col">Employee</th>
                <th scope="col">Leave Type</th>
                <th scope="col">Year</th>
                <th scope="col">Start Date</th>
                <th scope="col">End Date</th>
                <th scope="col">Days</th>
                <th scope="col">Reason</th>
                <th scope="col">Manager</th>
                <th scope="col">State</th>
                <th scope="col" class="text-center">Action</th>    
        </tr>
    </thead>
    <tbody>
        @forelse($leaveRequests as $leaveRequest)
        <tr>
            <th scope="row" class="ps-4 text-dark">{{ $loop->iteration }}</th>
            <td>{{ $leaveRequest->employee->first_name.' '.$leaveRequest->employee->last_name }}</td>
            <td>{{ $leaveRequest->leaveType->name }}</td>
            <td>{{ $leaveRequest->year }}</td>
            <td>{{ $leaveRequest->start_date }}</td>
            <td>{{ $leaveRequest->end_date }}</td>
            <td>{{ $leaveRequest->days * ($leaveRequest->full_leave == 1 ? 1 : 0.5) }}</td>
            <td style="width:14rem;">{{ $leaveRequest->reason }}</td>
            <td>{{ $leaveRequest->employee->manager ? $leaveRequest->employee->manager->first_name.' '.$leaveRequest->employee->manager->last_name:''}}</td>
            <td>{{ $leaveRequest->acceptance }}</td>
            <td>
                @if($leaveRequest->acceptance == 'pending')
                <form action="/leave-request/{{ $leaveRequest->id }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger text-white">Delete</button>
                </form>
                |
                <form action="/leave-request/accept/{{ $leaveRequest->id }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-sm btn-success">Accept</button>
                </form>
                |
                <form action="/leave-request/reject/{{ $leaveRequest->id }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-sm btn-secondary text-white">Reject</button>
                </form>
                <!-- |
                <a href="/leave-request/subordinate-leave/edit/{{ $leaveRequest->id }}">
                    <button type="submit" class="btn btn-sm btn-primary text-white">Edit</button>
                </a>  -->
                
                @else
                <div class="dropdown">
                    <button class="btn btn-{{ $leaveRequest->acceptance == 'accepted' ? 'success' : 'danger' }} dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                    {{ $leaveRequest->acceptance }}
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <form action="/leave-request/accept/{{ $leaveRequest->id }}" method="POST" class="dropdown-item">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="delete" style="width:100%;">Accept</button>
                        </form>
                        <!-- accept -->
                        <form action="/leave-request/reject/{{ $leaveRequest->id }}" method="POST" class="dropdown-item">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="delete" style="width:100%;">Reject</button>
                        </form>
                        <!-- reject -->
                    </div>
                </div>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <th colspan=11 class="text-center text-dark">No LeaveRequest Found</th>
        </tr>
        @endforelse
    </tbody>
</table>
{{-- $leaveRequests->links() --}}

@include('layouts.basic.tableFoot')
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.drmDataTable').DataTable();
    })

     //Search leave by date 
    function search(){
        let date = $('#date').val();
        if(date)
            $(location).attr('href','/leave-request/approve?d='+date);
    }

    function reset(){
        $(location).attr('href','/leave-request/approve');
    }


    //Search by date or Employee
    // function search(){
    //     let date = $('#punch_date').val();
    //     let employee_id = $('#employee_id').val();
    //     console.log(employee_id);
    //     if(date)
    //         $(location).attr('href','/punch-in-detail?d='+date);
    //     if(employee_id)
    //         $(location).attr('href','/punch-in-detail?e='+employee_id);

    // }

    $('.employee-livesearch').select2({    
        ajax: {
            url: '/employee/search',
            data: function (params) {
                var query = {
                    q: params.term,
                }
                    // Query parameters will be ?search=[term]
                return query;
            },
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        let full_name = (item.middle_name === null) ? item.first_name + " " + item.last_name : item.first_name + " " + item.middle_name + " " + item.last_name;
                        return {
                            text: full_name,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        }
    });
</script>
@endsection