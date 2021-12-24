@extends('layouts.hr.app')

@section('title','Designation')

@section('content')
<!-- page title start -->
<section class="my-3 pt-3">
    <div class="text-center">
        <h1 class="fs-2 title">Create Designation</h1>
    </div>
    <div class="underline mx-auto"></div>
</section>
<!-- page title end -->

<!-- form start -->
<section class="form_container mx-auto">
    <div class="row mx-auto">
        <form class="main_form p-4" method="POST" action="/designation">
            @csrf
            @include('admin.designation._form')
            <center><button type="submit" class="btn btn-primary mt-2">Add</button></center>   
        </form>
    </div>
</section>
<!-- form end -->
@endsection