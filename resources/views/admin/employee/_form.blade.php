@if($errors->any())
    <div class="alert alert-danger">
        <p><strong>Opps Something went wrong</strong></p>
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
@endif

<!-- Form Starts -->
<div class="row">
    <div class="col-md-6">
        <div class="mb-4">
            <label class="form-label" for="employee_id">Employee ID*</label>
            <input type="number" class="form-control" id="employee_id" placeholder="Enter Employee ID" name="employee_id" value="{{ !empty(old('employee_id')) ? old('employee_id') : $employee->employee_id ?? '' }}">
            @error('employee_id')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
<!-- Employee Id -->

     <div class="col-md-6">
        <div class="mb-4">
            <label class="form-label" for="first_name">First Name*</label>
            <input type="text" class="form-control" id="first_name" placeholder="Enter Employee's First Name" name="first_name" value="{{ !empty(old('first_name')) ? old('first_name') : $employee->first_name ?? '' }}">
            @error('first_name')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
<!-- firstname -->
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-4">
            <label class="form-label" for="middle_name">Middle Name</label>
            <input type="text" class="form-control" id="middle_name" placeholder="Enter Employee's Middle Name" name="middle_name" value="{{ !empty(old('middle_name')) ? old('middle_name') : $employee->middle_name ?? '' }}">
            @error('middle_name')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
<!-- middlename -->
    <div class="col-md-6">
        <div class="mb-4">
            <label class="form-label" for="last_name">Last Name*</label>
            <input type="text" class="form-control" id="last_name" placeholder="Enter Employee's Last Name" name="last_name" value="{{ !empty(old('last_name')) ? old('last_name') : $employee->last_name ?? '' }}">
            @error('last_name')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
<!-- lastname -->
    
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-4">
            <label for="date_of_birth" class="form-label">DOB*</label>
            <input type="date" class="form-control" id="date_of_birth" placeholder="Enter Employee's DOB" name="date_of_birth" value="{{ !empty(old('date_of_birth')) ? old('date_of_birth') : $employee->date_of_birth ?? '' }}">
            @error('date_of_birth')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
<!-- dob -->

     <div class="col-md-6">
        <div class="mb-4">
            <label  class="form-label" for="gender">Gender*</label>
            <br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="gender0" value="female" 
                {{ (isset($employee) && $employee->gender == 'female') ? 'checked':''}}
                {{ old('gender') == 'female' ? 'checked':'' }}>
                <label class="form-check-label" for="gender0">female</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="gender1" value="male" 
                {{ (isset($employee) && $employee->gender == 'male') ? 'checked':''}}
                {{ old('gender') == 'male' ? 'checked':''}}>
                <label class="form-check-label" for="gender1">male</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="gender2" value="other" 
                {{ (isset($employee) && $employee->gender == 'other') ? 'checked':'' }}
                {{ old('gender') == 'other' ? 'checked':'' }}>
                <label class="form-check-label" for="gender2">other</label>
            </div>
            @error('gender')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
<!-- gender -->
</div>


<div class="row">
     <div class="col-md-6">
        <div class="mb-4">
            <label class="form-label" for="marital_status">Marital Status*</label>
            <select class="form-control" id="marital_status" name="marital_status">
                <option value="" disabled="disabled" selected="selected">-- Choose Status --</option>
                <option 
                    value="Single" 
                    {{ (!empty(old('marital_status')) && old('marital_status') == 'Single') ? 'selected': ''}}
                    {{ (isset($employee) && strtolower($employee->marital_status) == 'single' && empty(old('marital_status'))) ? 'selected' : '' }} 
                    >
                    Single
                </option>
                <option 
                    value="Married" 
                    {{ (!empty(old('marital_status')) && old('marital_status') == 'Married') ? 'selected': ''}}
                    {{ (isset($employee) &&  strtolower($employee->marital_status) == 'married' && empty(old('marital_status'))) ? 'selected' : '' }} 
                    >
                    Married
                </option>
                <option 
                    value="Divorced" 
                    {{ (!empty(old('marital_status')) && old('marital_status') == 'Divorced') ? 'selected': ''}}
                    {{ (isset($employee) &&  strtolower($employee->marital_status) == 'divorced' && empty(old('marital_status'))) ? 'selected' : '' }} 
                   >
                    Divorced
                </option>
            </select>
            @error('marital_status')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
<!-- marital-status -->
   
    <div class="col-md-6" id="spouseNameBlock" style="display:none;">
        <div class="mb-4">
            <label  class="form-label" for="spouse_name">Spouse Name</label>
            <input type="text" class="form-control" id="spouse_name" placeholder="Enter Employee's Spouse Name" name="spouse_name" value="{{ !empty(old('spouse_name')) ? old('spouse_name') : $employee->spouse_name ?? '' }}">
            @error('spouse_name')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
<!-- spouse_name -->

</div>


<div class="row">
    <div class="col-md-6">
        <div class="mb-4">
            <label  class="form-label" for="grand_father">Grandfather Name</label>
            <input type="text" class="form-control" id="grand_father" placeholder="Enter Employee's Grandfather Name" name="grand_father" value="{{ !empty(old('grand_father')) ? old('grand_father') : $employee->grand_father ?? '' }}">
            @error('grand_father')
                <p class="text-danger">{{ $message }}</p>
            @enderror
           
        </div>
    </div>
<!--Grandfather -->

    <div class="col-md-6">
        <div class="mb-4">
            <label  class="form-label" for="father_name">Father Name</label>
            <input type="text" class="form-control" id="father_name" placeholder="Enter Employee Father Name" name="father_name" value="{{ !empty(old('father_name')) ? old('father_name') : $employee->father_name ?? '' }}">
            @error('father_name')
                <p class="text-danger">{{ $message }}</p>
            @enderror
            
        </div>
    </div>
<!-- father_name -->
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-4">
            <label  class="form-label" for="mother_name">Mother Name</label>
            <input type="text" class="form-control" id="mother_name" placeholder="Enter Employee's Mother Name" name="mother_name" value="{{ !empty(old('mother_name')) ? old('mother_name') : $employee->mother_name ?? '' }}">
            @error('mother_name')
                <p class="text-danger">{{ $message }}</p>
            @enderror
            
        </div>
    </div>
<!-- mother_name -->

    <div class="col-md-6">
        <div class="mb-4">
            <label class="form-label" for="mobile">Mobile*</label>
            <input type="text" class="form-control" id="mobile" placeholder="Enter Employee's mobile" name="mobile" value="{{ !empty(old('mobile')) ? old('mobile') : $employee->mobile ?? '' }}">
            @error('mobile')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
<!-- mobile -->
</div>


<div class="row">
    <div class="col-md-6">
        <div class="mb-4">
            <label class="form-label" class="form-label" for="alternative_mobile">Alternative Mobile</label>
            <input type="text" class="form-control" id="alternative_mobile" placeholder="Enter Employee's Alternative Mobile Number" name="alternative_mobile" value="{{ !empty(old('alternative_mobile')) ? old('alternative_mobile') : $employee->alternative_mobile ?? '' }}">
            @error('alternative_mobile')
                <p class="text-danger">{{ $message }}</p>
            @enderror
            
        </div>
    </div>
<!-- alternative_mobile -->

    <div class="col-md-6">
        <div class="mb-4">
            <label class="form-label" for="home_phone">Home Mobile</label>
            <input type="text" class="form-control" id="home_phone" placeholder="Enter Employee's Home Phone Number" name="home_phone" value="{{ !empty(old('home_phone')) ? old('home_phone') : $employee->home_phone ?? '' }}">
            @error('home_phone')
                <p class="text-danger">{{ $message }}</p>
            @enderror
            
        </div>
    </div>
<!-- home_phone -->
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-4">
            <label class="form-label" for="image">Image</label>
            <input type="file" class="form-control" id="image" name="image">
            @error('image')
                <p class="text-danger">{{ $message }}</p>
            @enderror
            
        </div>
    </div>
<!-- image -->

    <div class="col-md-6">
        <div class="mb-4">
            <label class="form-label" for="alter_email">Personal Email*</label>
            <input type="text" class="form-control" id="alter_email" placeholder="Enter Employee's Alternative Email" name="alter_email" value="{{ !empty(old('alter_email')) ? old('alter_email') : $employee->alter_email ?? '' }}">
            @error('alter_email')
                <p class="text-danger">{{ $message }}</p>
            @enderror
           
        </div>
    </div>
<!-- alter_email -->
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-4">
             <label class="form-label" for="cv">Resume (PDF)</label>
            <input type="file" class="form-control" id="cv" name="cv">
            @error('cv')
                <p class="text-danger">{{ $message }}</p>
            @enderror
            
        </div>
    </div>
<!-- cv -->

    <div class="col-md-6">
        <div class="mb-4">
            
            <label class="form-label" for="country">Country*</label>
            
            <select class="form-control" id="country" name="country">
                @if(isset($employee)) $country = $employee->country @endif
                @include('layouts.country_options')
            </select>
            @error('country')
                <p class="text-danger">{{ $message }}</p>
            @enderror
           
        </div>
    </div>
<!-- country -->
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-4">
            <label class="form-label" for="nationality">Nationality</label>
            <input type="text" class="form-control" id="nationality" placeholder="Enter Employee's nationality" name="nationality" value="{{ !empty(old('nationality')) ? old('nationality') : $employee->nationality ?? '' }}">
            @error('nationality')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
<!-- nationality -->

    <div class="col-md-6">
        <div class="mb-4">
            <label class="form-label" for="blood_group">Blood Group*</label>
            <input type="text" class="form-control" id="blood_group" placeholder="Enter Employee's blood group" name="blood_group" value="{{ !empty(old('blood_group')) ? old('blood_group') : $employee->blood_group ?? '' }}">
            @error('blood_group')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- blood_group -->

<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label  class="form-label" for="profile">Profile</label>
            <textarea class="form-control" id="profile" placeholder="Enter Employee's profile" name="profile">{{ !empty(old('profile')) ? old('profile') : $employee->profile ?? '' }}</textarea>
            @error('profile')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
<!-- profile -->
</div>

<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="permanent_address">Province*</label>
            <select class="form-control" id="permanent_address" name="permanent_address">
                <option value="" disabled="disabled" selected="selected">-- Choose Province --</option>
                @foreach($provinces as $province)
                <option 
                    value="{{ $province->id }}" 
                    {{ (!empty(old('permanent_address')) && old('permanent_address') == $province->id) ? 'selected': ''}}
                    {{ (isset($employee) && $employee->permanent_address == $province->id && empty(old('permanent_address'))) ? 'selected' : '' }} 
                    >{{$province->province_name}}</option>
                @endforeach
            </select>
            @error('permanent_address')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

<!-- province -->


<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="permanent_district">Permanent District*</label>
            <select class="district-livesearch form-control p-3" name="permanent_district" id="permanent_district" data-placeholder="-- Choose District --">
            <option value="" selected disabled>--Select District--</option>
            @foreach($districts as $district)
            <option value="{{ $district->id }}" 
                {{ (!empty(old('permanent_district')) && old('permanent_district') == $district->id) ? 'selected': ''}}
                    {{ (isset($employee) && $employee->permanent_district == $district->id && empty(old('permanent_district'))) ? 'selected' : '' }} 
                    >{{$district->district_name}}
            </option>    
            @endforeach
            </select>
            @error('permanent_district')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- permanent_district -->

<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="permanent_municipality">Permanent Municipality*</label>
            <input type="text" class="form-control" id="permanent_municipality" placeholder="Enter Employee's permanent_municipality" name="permanent_municipality" value="{{ !empty(old('permanent_municipality')) ? old('permanent_municipality') : $employee->permanent_municipality ?? '' }}">
            @error('permanent_municipality')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- permanent_municipality -->

<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="permanent_ward_no">Permanent Ward_no*</label>
            <input type="text" class="form-control" id="permanent_ward_no" placeholder="Enter Employee's permanent_ward_no" name="permanent_ward_no" value="{{ !empty(old('permanent_ward_no')) ? old('permanent_ward_no') : $employee->permanent_ward_no ?? '' }}">
            @error('permanent_ward_no')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- permanent_ward_no -->

<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="permanent_tole">Permanent Tole*</label>
            <input type="text" class="form-control" id="permanent_tole" placeholder="Enter Employee's permanent_tole" name="permanent_tole" value="{{ !empty(old('permanent_tole')) ? old('permanent_tole') : $employee->permanent_tole ?? '' }}">
            @error('permanent_tole')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- permanent_tole -->

<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="temp_add_same_as_per_add">Is Temporary Address Same As Permanent Address*</label>
            <br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="temp_add_same_as_per_add" id="yes" value="1" 
                {{ (isset($employee) && $employee->temp_add_same_as_per_add == '1') ? 'checked':'' }}
                {{ old('temp_add_same_as_per_add') == '1' ? 'checked':'' }}
                onclick="$('#tempBlock').hide()">
                <label class="form-check-label" for="yes">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="temp_add_same_as_per_add" id="no" value="0" 
                {{ (isset($employee) && $employee->temp_add_same_as_per_add == '0') ? 'checked':'' }}
                {{ old('temp_add_same_as_per_add') == '0' ? 'checked':'' }}
                onclick="$('#tempBlock').show()">
                <label class="form-check-label" for="no">No</label>
            </div>
            @error('permanent_toletemp_add_same_as_per_add')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- temp_add_same_as_per_add -->

<div id="tempBlock" style="display:none">
<hr>
<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="temporary_address">Temporary Province*</label>
            <select class="form-control" id="temporary_address" name="temporary_address">
                <option value="" disabled="disabled" selected="selected">-- Choose Province --</option>
                @foreach($provinces as $province)
                <option 
                    value="{{ $province->id }}" 
                    {{ (!empty(old('temporary_address')) && old('temporary_address') == $province->id) ? 'selected': ''}}
                    {{ (isset($employee) && $employee->temporary_address == $province->id && empty(old('temporary_address'))) ? 'selected' : '' }} 
                    >{{$province->province_name}}</option>
                @endforeach
            </select>
            @error('temporary_address')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

<!-- province -->


<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="temporary_district">Temporary District*</label>
            <select class="temp-district-livesearch form-control p-3" name="temporary_district" id="temporary_district" data-placeholder="-- Choose District --">
            <option value="" selected disabled>--Select District--</option>
            @foreach($districts as $district)
            <option value="{{ $district->id }}" 
                {{ (!empty(old('temporary_district')) && old('temporary_district') == $district->id) ? 'selected': ''}}
                    {{ (isset($employee) && $employee->temporary_district == $district->id && empty(old('temporary_district'))) ? 'selected' : '' }} 
                    >{{$district->district_name}}
            </option>    
            @endforeach
            </select>
            @error('temporary_district')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- temporary_district -->

<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="temporary_municipality">Temporary Municipality*</label>
            <input type="text" class="form-control" id="temporary_municipality" placeholder="Enter Employee's temporary_municipality" name="temporary_municipality" value="{{ !empty(old('temporary_municipality')) ? old('temporary_municipality') : $employee->temporary_municipality ?? '' }}">
            @error('temporary_municipality')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- temporary_municipality -->

<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="temporary_ward_no">Temporary Ward_no*</label>
            <input type="text" class="form-control" id="temporary_ward_no" placeholder="Enter Employee's temporary_ward_no" name="temporary_ward_no" value="{{ !empty(old('temporary_ward_no')) ? old('temporary_ward_no') : $employee->temporary_ward_no ?? '' }}">
            @error('temporary_ward_no')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- temporary_ward_no -->

<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="temporary_tole">Temporary Tole*</label>
            <input type="text" class="form-control" id="temporary_tole" placeholder="Enter Employee's temporary_tole" name="temporary_tole" value="{{ !empty(old('temporary_tole')) ? old('temporary_tole') : $employee->temporary_tole ?? '' }}">
            @error('temporary_tole')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- temporary_tole -->
</div>
<!-- tempBlock Ends -->
<hr>
<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="join_date">Join Date* (A.D)</label>
            <input type="date" class="form-control" id="join_date" placeholder="Enter Employee's join_date" name="join_date" value="{{ !empty(old('join_date')) ? old('join_date') : $employee->join_date ?? '' }}">
            @error('join_date')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- join_date -->

<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="intern_trainee_ship_date">Intern/TraineeShip Date</label>
            <input type="date" class="form-control" id="intern_trainee_ship_date" placeholder="Enter Employee's intern_trainee_ship_date" name="intern_trainee_ship_date" value="{{ !empty(old('intern_trainee_ship_date')) ? old('intern_trainee_ship_date') : $employee->intern_trainee_ship_date ?? '' }}">
            @error('intern_trainee_ship_date')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>  
    </div>
</div>
<!-- intern_trainee_ship_date -->


<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="service_type">Service Type*</label>
            <select class="form-control" id="service_type" name="service_type">
                <option value="" disabled="disabled" selected="selected">-- Choose Service --</option>
                @forelse($serviceTypes as $serviceType)
                <option 
                    value="{{ $serviceType->id}}" 
                    {{ (!empty(old('service_type')) && old('service_type') == $serviceType->id) ? 'selected': ''}}
                    {{ (isset($employee) && $employee->service_type == $serviceType->id && empty(old('service_type'))) ? 'selected' : '' }} 
                    >
                    {{ $serviceType->service_type_name }}
                </option>
                @empty
                <!-- no options -->
                @endforelse
            </select>
            @error('serviceType_id')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- Service Type -->

<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="manager_id">Manager</label>
            <select class="form-control" id="manager_id" name="manager_id">
                <option value="" disabled="disabled" selected="selected">-- Choose Manager --</option>
                @forelse($managers as $manager)
                <option 
                    value="{{ $manager->employee_id }}" 
                    {{ (!empty(old('manager_id')) && old('manager_id') == $manager->employee->id) ? 'selected': ''}}
                    {{ (isset($employee) && $employee->manager_id == $manager->employee_id && empty(old('manager_id'))) ? 'selected' : '' }} 
                    >
                    {{ $manager->employee->first_name.' '.substr($manager->employee->middle_name,0,1).' '.$manager->employee->last_name }}
                </option>
                @empty
                <!-- no options -->
                @endforelse
            </select>
            @error('manager_id')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- manager_id -->

<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="designation_id">Designation*</label>
            <select class="form-control" id="designation_id" name="designation_id">
                <option value="" disabled="disabled" selected="selected">-- Choose Designation --</option>
                @forelse($designations as $designation)
                <option 
                    value="{{ $designation->id}}" 
                    {{ (!empty(old('designation_id')) && old('designation_id') == $designation->id) ? 'selected': ''}}
                    {{ (isset($employee) && $employee->designation_id == $designation->id && empty(old('designation_id'))) ? 'selected' : '' }} 
                    >
                    {{ $designation->job_title_name }}
                </option>
                @empty
                <!-- no options -->
                @endforelse
            </select>
            @error('designation_id')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- designation_id -->

<!-- <div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="designation_change_date">Designation Change Date</label>
            <input type="date" class="form-control" id="designation_change_date" placeholder="Enter Employee's designation_change_date" name="designation_change_date" value="{{ !empty(old('designation_change_date')) ? old('designation_change_date') : $employee->designation_change_date ?? '' }}">
            @error('designation_change_date')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div> -->
<!-- designation_change_date -->

<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="organization_id">Organization*</label>
            <select class="form-control" id="organization_id" name="organization_id">
                <option value="" disabled="disabled" selected="selected">-- Choose Organization --</option>
                @forelse($organizations as $organization)
                <option 
                    value="{{ $organization->id}}" 
                    {{ (!empty(old('organization_id')) && old('organization_id') == $organization->id) ? 'selected': ''}}
                    {{ (isset($employee) && $employee->organization_id == $organization->id && empty(old('organization_id'))) ? 'selected' : '' }} 
                    >
                    {{ $organization->name }}
                </option>
                @empty
                <!-- no options -->
                @endforelse
            </select>
            @error('organization_id')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- organization_id -->

<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="unit_id">Unit*</label>
            <select class="form-control" id="unit_id" name="unit_id">
                <option value="" disabled="disabled" selected="selected">-- Choose Unit --</option>
                @forelse($units as $unit)
                <option 
                    value="{{ $unit->id}}" 
                    {{ (!empty(old('unit_id')) && old('unit_id') == $unit->id) ? 'selected': ''}}
                    {{ (isset($employee) && $employee->unit_id == $unit->id && empty(old('unit_id'))) ? 'selected' : '' }} 
                    >
                    {{ $unit->unit_name }}
                </option>
                @empty
                <!-- no options -->
                @endforelse
            </select>
            @error('unit_id')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- unit_id -->


<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="department_id">Department*</label>
            <select class="department-livesearch form-control p-3" name="department_id" id="department_id" data-placeholder="-- Choose Department --">
            <option value="" selected disabled>--Select Department--</option>
            @foreach($departments as $department)
            <option value="{{ $department->id }}" 
                {{ (!empty(old('department_id')) && old('department_id') == $department->id) ? 'selected': ''}}
                    {{ (isset($employee) && $employee->department_id == $department->id && empty(old('department_id'))) ? 'selected' : '' }} 
                    >{{$department->name}}
            </option>    
            @endforeach
            </select>
            @error('department_id')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- Unit wise Department -->

<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="email">Email*</label>
            <input type="text" class="form-control" id="email" placeholder="Enter Employee's email" name="email" value="{{ !empty(old('email')) ? old('email') : $employee->email ?? '' }}">
            @error('email')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- email -->

<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="username">Username*</label>
            <input type="text" class="form-control" id="username" placeholder="Enter Employee's username" name="username" 
            value="{{ !empty(old('username')) ? old('username'): $employee->user->username ?? ''}}">
            @error('username')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- username -->

<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="role">Role*</label>
            <select class="form-control" id="role" name="role">
                @if(Route::current()->uri != 'employee/edit/{id}')
                <option value="" disabled="disabled" selected="selected">-- Choose Role --</option>
                @endif
                @foreach($roles as $role)
                <option 
                    value="{{$role->id}}" 
                    {{ (!empty(old('role')) && old('role') == $role->id) ? 'selected': ''}}
                    {{ (isset($employee) && $employee->user->role_id == $role->id && empty(old('role'))) ? 'selected' : '' }} 
                    >{{ucfirst($role->authority)}}</option>
                @endforeach
            </select>
            @error('role')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- role -->


<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="shift_id">Shift*</label>
            <select class="form-control" id="shift_id" name="shift_id" onchange="shiftTime()">
                <option value="" disabled="disabled" selected="selected">-- Choose Shift --</option>
                @forelse($shifts as $shift)
                <option 
                    value="{{ $shift->id}}" 
                    {{ (!empty(old('shift_id')) && old('shift_id') == $shift->id) ? 'selected': ''}}
                    {{ (isset($employee) && $employee->shift_id == $shift->id && empty(old('shift_id'))) ? 'selected' : '' }} 
                    @if($shift->time_required == 1)
                        class="requireTime"
                    @endif
                    >
                    {{ $shift->name }}
                </option>
                @empty
                <!-- no options -->
                @endforelse
            </select>
            @error('shift_id')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- Shift -->

<div class="row" style="display:none;" id="shift_time">
    <div class="col-md-6">
        <div class="mb-4">
            <label class="form-label" for="start_time">Start Time</label>
            <input type="time" class="form-control" id="start_time" placeholder="Enter Employee\'s Shift Start time" name="start_time" value="{{ !empty(old('start_time')) ? old('start_time') :(isset($employee->start_time)?date('H:i',strtotime($employee->start_time)):'')?? '' }}">
            @error('start_time')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
<!-- Start Time -->
    <div class="col-md-6">
        <div class="mb-4">
            <label class="form-label" for="end_time">End Time</label>
            <input type="time" class="form-control" id="end_time" placeholder="Enter Employee\'s Shift End time" name="end_time" value="{{ !empty(old('end_time')) ? old('end_time') : (isset($employee->end_time)?date('H:i',strtotime($employee->end_time)):'')?? '' }}">
            @error('end_time')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- End Time -->
<!-- Shift Timeif required -->

<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="remarks">Employee Remarks</label>
            <input type="text" class="form-control" id="remarks" placeholder="Enter Employee remarks" name="remarks" value="{{ !empty(old('remarks')) ? old('remarks') : $employee->remarks ?? '' }}">
            @error('remarks')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- remarks -->


<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="pan_number">Permanent Account Number (PAN)</label>
            <input type="text" class="form-control" id="pan_number" placeholder="Enter Employee's PAN Number" name="pan_number" value="{{ !empty(old('pan_number')) ? old('pan_number') : $employee->pan_number ?? '' }}">
            @error('pan_number')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- pan no. -->


<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="cit_number">Citizen Investment Trust (CIT) Number</label>
            <input type="text" class="form-control" id="cit_number" placeholder="Enter Employee's CIT Number" name="cit_number" value="{{ !empty(old('cit_number')) ? old('cit_number') : $employee->cit_number ?? '' }}">
            @error('cit_number')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- CIT no. -->


<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="ssf_id">Social Security Fund (SSF) Id</label>
            <input type="text" class="form-control" id="ssf_id" placeholder="Enter Employee's SSF ID" name="ssf_id" value="{{ !empty(old('ssf_id')) ? old('ssf_id') : $employee->ssf_id ?? '' }}">
            @error('ssf_id')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- SSF ID -->


<div class="row">
    <div class="col-md-12">
        <div class="mb-4">
            <label class="form-label" for="nibl_account_number">NIBL Account Number</label>
            <input type="text" class="form-control" id="nibl_account_number" placeholder="Enter Employee's NIBL Account Number" name="nibl_account_number" value="{{ !empty(old('nibl_account_number')) ? old('nibl_account_number') : $employee->nibl_account_number ?? '' }}">
            @error('nibl_account_number')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
<!-- NIBL Account no. -->

<hr>
<!-- emergency_contact -->
<fieldset>
    <legend>Emergency Contact</legend>
    
    <div class="row">
        <div class="col-md-6">
            <div class="mb-4">
                <label class="form-label" for="emg_first_name">First Name*</label>
                <input type="text" class="form-control" id="emg_first_name" placeholder="Enter Emergency Contact's First Name" name="emg_first_name" value="{{ !empty(old('emg_first_name')) ? old('emg_first_name') : $employee->emergencyContact->first_name?? '' }}">
                @error('emg_first_name')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>

    <!-- emg_first_name -->

        <div class="col-md-6">
                <div class="mb-4">
                <label class="form-label" for="emg_middle_name">Middle Name</label>
                <input type="text" class="form-control" id="emg_middle_name" placeholder="Enter Emergency Contact's Middle Name" name="emg_middle_name" value="{{ !empty(old('emg_middle_name')) ? old('emg_middle_name') : $employee->emergencyContact->middle_name?? '' }}">
                @error('emg_middle_name')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    <!-- emg_middle_name -->

    <div class="row">
        <div class="col-md-6">
            <div class="mb-4">
                <label class="form-label" for="emg_last_name">Last Name*</label>
                <input type="text" class="form-control" id="emg_last_name" placeholder="Enter Emergency Contact's Last Name" name="emg_last_name" value="{{ !empty(old('emg_last_name')) ? old('emg_last_name') : $employee->emergencyContact->last_name?? '' }}">
                @error('emg_last_name')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>

    <!-- emg_last_name -->

        <div class="col-md-6">
            <div class="mb-4">
                <label class="form-label" for="emg_relationship">Relationship*</label>
                <input type="text" class="form-control" id="emg_relationship" placeholder="Enter Employee's Relationship" name="emg_relationship" value="{{ !empty(old('emg_relationship')) ? old('emg_relationship') : $employee->emergencyContact->relationship ?? '' }}">
                @error('emg_relationship')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    
    <!-- relationship -->

    <div class="row">
        <div class="col-md-6">
            <div class="mb-4">
                <label class="form-label" for="emg_contact">Emergency Contact*</label>
                <input type="text" class="form-control" id="emg_contact" placeholder="Enter Contact Number" name="emg_contact" value="{{ !empty(old('emg_contact')) ? old('emg_contact') : $employee->emergencyContact->phone_no?? '' }}">
                @error('emg_contact')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>

    <!-- contact -->

        <div class="col-md-6">
            <div class="mb-4">
                <label class="form-label" for="emg_alternate_contact">Alternate Contact</label>
                <input type="text" class="form-control" id="emg_alternate_contact" placeholder="Enter Alternate Contact Number" name="emg_alternate_contact" value="{{ !empty(old('emg_alternate_contact')) ? old('emg_alternate_contact') : $employee->emergencyContact->alternate_phone_no?? '' }}">
                @error('emg_alternate_contact')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    <!-- alternate_contact -->
</fieldset>
