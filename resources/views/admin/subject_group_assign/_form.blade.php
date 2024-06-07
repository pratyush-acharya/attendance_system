<div class="row gx-5 align-items-center">
    <div class="col-md-6 mt-5">
        <div class="align-items-center">
            <label for="group" class=" col-md-4 form-label" >Section<span class="red_text"><b>*</b></span></label>
            
            <select id="group" name="group" class="col-md-4 form-control form-select  form-select-sm " >
               <option  disabled selected>--Choose Section--</option>
                @foreach($groups as $group)
                <option 
                    value="{{ $group->id }}" 
                    {{ (!empty(old('group')) && old('group') == $group->id) ? 'selected': ''}}
                    {{ (isset($assignedGroup) && $assignedGroup->id == $group->id && empty(old('group'))) ? 'selected' : '' }} 
                    >
                    Section {{ $group->name}} of {{$group->batch->name}}-{{$group->batch->stream->name}}
                </option>
                @endforeach
            </select>
            @error('group')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="col-md-6 mt-5">
        <div class="align-items-center">
            <label for="subject" class=" col-md-4 form-label" >Subject<span class="red_text"><b>*</b></span></label>
            
            <select id="subject" name="subjects[]" class="col-md-4 form-control form-select  form-select-sm " multiple data-placeholder="--Choose Subject--">
                @foreach($subjects as $subject)
                <option 
                    value="{{ $subject->id }}" 
                    {{ (!empty(old('subject')) && in_array($subject->id, old('subjects'))) ? 'selected': ''}}
                    {{ (isset($assignedGroup) && $assignedGroup->subjects->contains($subject->id) && empty(old('subjects'))) ? 'selected' : '' }} 
                    >
                    {{$subject->code}} {{ $subject->name}}
                </option>
                @endforeach
            </select>
            @error('subjects')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>

</div>

