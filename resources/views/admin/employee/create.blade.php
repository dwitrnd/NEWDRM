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
        <div class="col-md-2 col-sm-4 mb-4 mx-auto">
            <img src="{{ (isset($employee) && $employee->image != NULL)? $employee->image : '/assets/images/image.png' }}" class="img-thumbnail img-fluid" width="100%">
        </div>

        <div class="col-md-10 col-sm-8" style="background-color:aliceblue; padding: 20px 40px;">
            <form class="main_form p-4" method="POST" action="/employee" enctype='multipart/form-data'>
                <legend>
                    <center>Personal Details {{ $employee }}</center>
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