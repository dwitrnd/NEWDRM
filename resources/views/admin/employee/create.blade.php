@extends('layouts.hr.app')

@section('title','Form')

@section('content')


<!-- page title start -->
<section class="my-3 pt-3">
    <div class="text-center">
        <h1 class="fs-2 title">Create Employee</h1>
    </div>
    <div class="underline mx-auto"></div>
</section>
<!-- page title end -->

<!-- form start -->
<section class="form_container mx-auto">
    <div class="row mx-auto">
        <div class="col-md-10 col-sm-8 mx-auto" style="background-color:aliceblue; padding: 20px 40px;">
            <form class="main_form p-4" method="POST" action="/employee" enctype='multipart/form-data'>
                <legend>
                    <center>Personal Detail</center>
                </legend>    
                @csrf
                @include('admin.employee._form')
                <center><button type="submit" class="btn btn-primary mt-2">Add</button></center>
            </form>
        </div>
    </div>
</section>
<!-- form end -->
@endsection

@section('scripts')
<script>
    function shiftTime(){
        var ddl = document.getElementById("shift_id");
        // console.log(ddl.options[ddl.selectedIndex]);
        var selectedValue = ddl.options[ddl.selectedIndex].value;
        // console.log('selected id: ',selectedValue,selectedValue-1);
        var shifts =document.getElementsByClassName('requireTime');
        // console.log(shifts[0].value);
        var shift_timings = document.getElementById('shift_time');
        if(shifts.length > 0){
            for(var i=0;i<=shifts.length;i++){
                // console.log(shifts[i].value);
                if(shifts[i].value == selectedValue){
                    console.log(shifts[i].value);
                    $('#shift_time').show();
                    break;
                }else{
                    $('#shift_time').hide();
                }
            }
        }else{
            $('#shift_time').hide();
        }
    }
    $('.manager-livesearch').select2({    
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

    $('.district-livesearch').select2({
        ajax: {
            url: '/district/search',
            data: function (params) {
                var query = {
                    q: params.term,
                    p: $('#permanent_address').val() 
                    // t: $('#temporary_address').val() 
                }
                    // Query parameters will be ?search=[term]
                return query;
            },
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.district_name ,
                            id: item.id
                        }
                    })
                };
                
                // console.log(query);
            },
            cache: false
        }
    });
    
    $('.temp-district-livesearch').select2({
        ajax: {
            url: '/district/search',
            data: function (params) {
                var query = {
                    q: params.term,
                    p: $('#temporary_address').val() 
                }
                    // Query parameters will be ?search=[term]
                return query;
            },
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.district_name ,
                            id: item.id
                        }
                    })
                };
                
                // console.log(query);
            },
            cache: false
        }
    });

</script>
@endsection