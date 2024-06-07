<div class="row gx-5 align-items-center">
    <div class="col-md-6 col-name" >
        <div class="row align-items-center">
            <label for="full_name" class=" col-md-4 form-label " >Full Name<span class="red_text"><b>*</b></span></label>
            <input type="text" class=" col-md-4 form-control" id="full_name" name="name" required value="{{!empty(old('name')) ? old('name') : $student->name ?? ''}}">
            @error('name')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="col-md-6 col-name" >
        <div class="row align-items-center">
            <label for="full_name" class=" col-md-4 form-label " >Email<span class="red_text"><b>*</b></span></label>
            <input type="enmail" class=" col-md-4 form-control" id="email" name="email" required value="{{!empty(old('email')) ? old('email') : $student->email ?? ''}}">
            @error('email')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="col-md-6 col-name" >
        <div class="row align-items-center">
            <label for="roll_no" class=" col-md-4 form-label " >Roll No.<span class="red_text"><b>*</b></span></label>
            <input type="text" class=" col-md-4 form-control" id="roll_no" name="roll_no" required value="{{!empty(old('roll_no')) ? old('roll_no') : $student->roll_no ?? ''}}">
        </div>
    </div>

<!-- <div class="row gx-5 align-items-center"> -->
    <div class="col-md-6 col-name  mt-4">
        <div class="row align-items-center">
            <label for="batch_id" class=" col-md-4 form-label" >Batch<span class="red_text"><b>*</b></span></label>
            <select id="batch_id" name="batch_id" class="col-md-4 form-control form-select  form-select-sm " >
               <option value="" disabled selected class="text-center">--Choose Batch--</option>
                @forelse($batches as $batch)
                <option 
                    value="{{ $batch->id}}" 
                    {{ (!empty(old('batch_id')) && old('batch_id') == $batch->id) ? 'selected': ''}}
                    {{ (isset($student) && $student->batch_id == $batch->id && empty(old('batch_id'))) ? 'selected' : '' }} 
                    >
                    {{ $batch->name }} - {{ $batch->stream->name}}
                </option>
                @empty
                    <option value="" disabled>No options available</option>
                @endforelse
            </select>
        </div>
    </div>
<!-- </div> -->

<!-- <div class="row gx-5 align-items-center"> -->
</div>
