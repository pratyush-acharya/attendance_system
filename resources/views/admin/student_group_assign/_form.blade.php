<div class="row gx-5 align-items-center">
    <div class="col-md-6 mt-5">
        <div class="row align-items-center">
            <label for="stream_id" class=" col-md-4 form-label" >Stream<span class="red_text"><b>*</b></span></label>
            
            <select id="stream_id" name="stream_id" class="col-md-4 form-control form-select  form-select-sm " >
               <option value="" selected disabled >--Choose Stream--</option>
                @forelse($streams as $stream)
                <option 
                    value="{{ $stream->id}}" 
                    {{ (!empty(old('stream_id')) && old('stream_id') == $stream->id) ? 'selected': ''}}
                    {{ (isset($assignedGroup) && $assignedGroup->batch->stream_id == $stream->id && empty(old('stream_id'))) ? 'selected' : '' }}
                    >                    
                    {{ $stream->name }}
                </option>
                @empty
                    <option value="" disabled>No options available</option>
                @endforelse
            </select>
            @error('stream_id')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="col-md-6 mt-5">
        <div class="align-items-center">
            <label for="batch_id" class=" col-md-4 form-label" >Batch<span class="red_text"><b>*</b></span></label>
            <select id="batch_id" name="batch_id" class="batch-livesearch col-md-4 form-control form-select  form-select-sm " data-placeholder="-- Choose Batch --">
               <option value="" selected disabled >--Choose Batch--</option>
                @foreach($batches as $batch)
                <option value="{{ $batch->id }}" 
                    {{ (!empty(old('batch_id')) && old('batch_id') == $batch->id) ? 'selected': ''}}
                    {{ (isset($assignedGroup) && $assignedGroup->students[0]->batch->id == $batch->id && empty(old('batch_id'))) ? 'selected' : '' }}
                > {{ ucfirst($batch->name) }} - {{ ucfirst($batch->stream->name) }}
                </option>    
                @endforeach 
            </select>
            @error('batch_id')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

<div class="row gx-5 align-items-center">
    
    <div class="col-md-6 mt-5">
        <div class="align-items-center">
            <label for="student_id" class=" col-md-4 form-label" >Student<span class="red_text"><b>*</b></span></label>
            {{-- select starts here --}}
             <select class="js-example-basic-multiple" name="students[]" multiple="multiple" data-placeholder="--Choose Student--">
                @foreach($students as $student)
                <option
                value="{{ $student->id }}"
                    {{ (!empty(old('student_id')) && old('student_id') == $student->id) ? 'selected': ''}}
                    {{ (isset($assignedGroup) && $assignedGroup->students->contains($student->id) == $student->id && empty(old('students'))) ? 'selected' : '' }}
                    >
                    {{ ucfirst($student->name)}}
                    </option>
                @endforeach
              </select>

{{--           <select multiple id="student_id" name="students[]" class="student-livesearch col-md-4 form-control form-select  form-select-sm " data-placeholder="--Choose Student--">--}}
{{--                @foreach($students as $student)--}}
{{--                <option --}}
{{--                    value="{{ $student->id }}" --}}
{{--                    {{ (!empty(old('student_id')) && old('student_id') == $student->id) ? 'selected': ''}}--}}
{{--                    {{ (isset($assignedGroup) && $assignedGroup->students->contains($student->id) == $student->id && empty(old('students'))) ? 'selected' : '' }}--}}
{{--                    >--}}
{{--                    {{ ucfirst($student->name)}}--}}
{{--                </option>--}}
{{--                @endforeach--}}
{{--            </select>--}}

            {{-- select ends here --}}
            @error('students')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
    
    <div class="col-md-6 mt-5">
        <div class="align-items-center">
            <label for="group_id" class=" col-md-4 form-label" >Section<span class="red_text"><b>*</b></span></label>
            
            <select id="group_id" name="group_id" class="group-livesearch col-md-4 form-control form-select form-select-sm" >
               <option value="" disabled selected class="text-center">--Choose Section--</option>
                    @foreach($groups as $group)
                    <option 
                        value="{{ $group->id}}" 
                        {{ (!empty(old('group_id')) && old('group_id') == $group->id) ? 'selected': ''}}
                        {{ (isset($assignedGroup) && $assignedGroup->id == $group->id && empty(old('group_id'))) ? 'selected' : '' }}
                        >
                        {{ $group->name }}
                    </option>
                    @endforeach
            </select>
            @error('group_id')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

