@extends('layouts.hr.app')

@section('title','Leave Type')

@section('content')
<!-- page title start -->
<section class="my-3 pt-3">
    <div class="text-center">
        <h1 class="fs-2 title">Edit Leave Request</h1>
    </div>
    <div class="underline mx-auto"></div>
</section>
<!-- page title end -->

<!-- form start -->
<section class="form_container mx-auto">
    <div class="row mx-auto">
        <div class="col-md-0 col-sm-0">
            <form class="main_form p-4" method="POST" action="/leave-request/{{$leaveRequest->id}}">
                @csrf
                @method('PUT')
                @include('admin.leaveRequest._form')
                <center><button type="submit" class="btn btn-primary mt-2">Update</button></center>   
            </form>
        </div>
    </div>
</section>
<!-- form end -->
@endsection


@section('scripts')
<script>
    //calculate leave days
    function calculateLeaveDays(){
        var start_date = document.getElementById('start_date').value;
        var end_date = document.getElementById('end_date').value;
        var leave_type_id = document.getElementById('leave_type_id').value;
        var leave_time = document.getElementsByName('leave_time').value;
        var reason = document.getElementById('reason');
        reason.style.visibility = "hidden";
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:'POST',
            url: "/calculate-leave-days",
            data: {
                "_token": "{{ csrf_token() }}",
                "start_date":start_date, 
                "end_date":end_date, 
                "leave_type_id":leave_type_id,
                "leave_time":leave_time,
                },
            dataType:'json',
            success: function(data) {
                document.getElementById('days').setAttribute('value',data.days);
                 if(data.reason){
                    reason.innerHTML = data.reason;
                    reason.style.visibility = "visible";
                }
                console.log(data.days,data.reason);
            },
             error: function (data) {
                console.log(data);
            }
        });
    }

</script>
@endsection