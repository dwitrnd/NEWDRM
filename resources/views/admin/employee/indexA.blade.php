@extends('layouts.admin.app')
@if ($message = Session::get('warning'))
<div class="alert alert-warning alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>    
    <strong>{{ $message }}</strong>
</div>
@endif
@section('content')
<div class="my-table">
    <a href="/employee/create"><button class="btn btn-primary float-right">Add Employee</button></a>
    <h3 class="text-success text-center">Employee List</h3>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" class="pl-4">S.N</th>
                    <th scope="col">Name</th>
                    <th scope="col">Title</th>
                    <th scope="col">Manager</th>
                    <th scope="col">Organization</th>
                    <th scope="col">Unit</th>
                    <th scope="col">Internship/Trainee Date</th>
                    <th scope="col">Join Date</th>
                    <th scope="col">Y Since Date</th>
                    <th scope="col">Status</th>
                    <th scope="col">Tier Level</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($employees as $employee)
                <tr>
                    <th scope="row" class="pl-4">{{ $loop->iteration }}</th>
                    <td>{{ $employee->first_name.' '.substr($employee->middle_name,0,1).' '.$employee->last_name }}</td>
                    <td>Title</td>
                    <td>{{ $employee->manager_id }}</td>
                    <td>{{ $employee->organization_id }}</td>
                    <td>{{ $employee->unit_id }}</td>
                    <td>{{ $employee->intern_trainee_ship_date }}</td>
                    <td>{{ $employee->join_date }}</td>
                    <td>year</td>
                    <td>status</td>
                    <td>tier</td>
                    <td>
                        <a href="/employee/edit/{{ $employee->id }}">Edit</a> 
                        | 
                        <form action="/employee/{{ $employee->id }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <th colspan=11 class="text-center">No Employee Created</th>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $employees->links() }}
    </div>
</div>
@endsection