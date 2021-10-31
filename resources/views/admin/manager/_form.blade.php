<div class="form-group">
    <label for="employee_id">Employee Name*</label>
    <select class="form-control" id="employee_id" name="employee_id">
        <option value="" disabled="disabled" selected="selected">-- Choose Employee --</option>
        @forelse($employees as $employee)
        <option 
            value="{{ $employee->id}}" 
            {{ (!empty(old('employee_id')) && old('employee_id') == $employee->id) ? 'selected': ''}}
            {{ (isset($manager) && $manager->employee_id == $employee->id && empty(old('employee_id'))) ? 'selected' : '' }} 
            >
            {{ $employee->first_name.' '.$employee->last_name}}
        </option>
        @empty
        <!-- no options -->
        @endforelse
    </select>
    @error('employee_id')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>


<div class="form-group">
    <label for="is_active">Manager Status*</label>
    <select class="form-control" id="is_active" name="is_active">
        <option value="" disabled="disabled" selected="selected">-- Choose Status --</option>
        <option 
            value="active" 
            {{ (!empty(old('is_active')) && old('is_active') == 'active') ? 'selected': ''}}
            {{ (isset($manager) && $manager->is_active == $manager->is_active && empty(old('is_active'))) ? 'selected' : '' }} 
            >
            Active
        </option>
        <option 
            value="inactive" 
            {{ (!empty(old('is_active')) && old('is_active') == 'inactive') ? 'selected': ''}}
            {{ (isset($manager) && $manager->is_active == $manager->is_active && empty(old('is_active'))) ? 'selected' : '' }} 
            >
            Inactive
        </option>
    </select>
    @error('is_active')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>
<!-- marital-status -->
