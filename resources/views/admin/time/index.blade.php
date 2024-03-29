@extends('layouts.hr.app')

@section('title','Time Setting')

@section('content')
@include('layouts.basic.tableHead',["table_title" => "Time Setting"])

<table class="unit_table mx-auto drmDataTable">
    <thead>
    ` <tr class="table_title" style="background-color: #0f5288;">
        <th scope="col" class="ps-4">S.N</th>
            <th scope="col">Name</th>
            <th scope="col" class="text-center">Send Mail</th>    
        </tr>`
    </thead>
    <tbody>
        @forelse($times as $time)
        <tr>
            <th scope="row" class="ps-4 text-dark">{{ $loop->iteration }}</th>
            <td>{{ $time->name }}</td>       
            <td>
                <center>
                    <form method="POST" action="/time/{{$time->id}}">
                        @csrf
                        <label for="time"></label>
                        <input type="time" name="time" value="{{!empty(old('time')) ? old('time') : date('H:i',strtotime($time->time)) ?? ''}}">
                        <button type="submit" class="btn btn-primary p-2">Set</button>
                    </form>
                </center>   
            </td>    
            
        </tr>
        @empty
        <tr>
            <th colspan=11 class="text-center text-dark">No Time Setting Found</th>
        </tr>
        @endforelse
    </tbody>
</table>

@include('layouts.basic.tableFoot')
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.drmDataTable').DataTable();
    })
</script>
@endsection