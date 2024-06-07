@php
    $types = ['main','elective', 'credit']
@endphp

<div class="row gx-5 align-items-center">
    <div class="col-md-6 col-name">
        <div class="row align-items-center">
            <label for="code" class=" col-md-4 form-label " >Subject Code<span class="red_text"><b>*</b></span></label>
            <input type="text" class=" col-md-4 form-control" id="code" name="code" required value="{{!empty(old('code')) ? old('code') : $subject->code ?? ''}}">
            @error('code')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
 


    <div class="col-md-6 col-name" >
        <div class="row align-items-center">
            <label for="name" class=" col-md-4 form-label " >Subject Name<span class="red_text"><b>*</b></span></label>
            <input type="text" class=" col-md-4 form-control" id="name" name="name" required value="{{!empty(old('name')) ? old('name') : $subject->name ?? ''}}">
            @error('name')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

<div class="row gx-5 align-items-center ">
    <div class="col-md-6 mt-5">
        <div class="row align-items-center">
            <label for="subject_type" class=" col-md-4 form-label" >Subject Type<span class="red_text"><b>*</b></span></label>
            
            <select id="subject_type" name="type" class="col-md-4 form-control form-select  form-select-sm " >
               <option  disabled selected>--Choose Subject Type--</option>
                @foreach($types as $type)
                <option 
                    value="{{ $type }}" 
                    {{ (!empty(old('type')) && old('type') == $type) ? 'selected': ''}}
                    {{ (isset($subject) && $subject->type == $type && empty(old('type'))) ? 'selected' : '' }} 
                    >
                    {{ ucfirst($type)}}
                </option>
                @endforeach
            </select>
            @error('type')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
    
</div>

