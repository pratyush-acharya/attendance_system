<div class="row gx-5 align-items-center">
    <div class="col-md-6 mt-5">
        <div class=" align-items-center">
            <label for="group" class=" col-md-4 form-label" >Section<span class="red_text"><b>*</b></span></label>
            <select id="group" required name="group" class=" col-md-4 form-control form-select form-select-sm " >
                <option value="" disabled selected>--Choose Section--</option>
                @foreach($groups as $group)
                <option 
                    value="{{ $group->id }}" 
                    {{ ((isset($groupSubject) ? old('group', $groupSubject->getAttributes()['group_id']) : old('group')) == $group->id) ? 'selected': ''}}
                    {{-- {{ (isset($assignedGroup) && $assignedGroup->id == $group->id && empty(old('group'))) ? 'selected' : '' }}  --}}
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
        <div class=" align-items-center">
            <label for="subject" class=" col-md-4 form-label" >Subject<span class="red_text"><b>*</b></span></label>
            
            <select required id="subject" name="subject" class="col-md-4 form-control form-select  form-select-sm " >
               <option value="" disabled selected>--Choose Subject--</option>
            </select>
             @error('subject')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="col-md-6 mt-5">
        <div class="align-items-center">
            <label for="teacher" class=" col-md-4 form-label" >Teacher<span class="red_text"><b>*</b></span></label>
            
            <select required id="teacher" name="user" class="col-md-4 form-control">
               <option value="" disabled selected>--Choose Teacher --</option>
                @foreach($teachers as $teacher)
                <option 
                    value="{{ $teacher->id }}" 
                    {{ ((isset($groupSubject)?(old('teacher', $groupSubject->users->first()->id)): old('teacher')) == $teacher->id) ? 'selected': ''}}
                    >
                    {{ $teacher->name }}
                </option>
                @endforeach
            </select>
             @error('user')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
    
    <div class="col-md-6 mt-5">
        <div class="align-items-center">
            <div class="row">
                <label class="col-md-3" for="days" class="col-md-4 form-label" >Days<span class="red_text"><b>*</b></span></label>
                <label class="col-md-3"><input type="checkbox" name="days[]" value="Sun" {{(isset($groupSubject)?((in_array("Sun", explode(",", $groupSubject->users->first()->pivot->days)))?'checked':''): '')}}>Sunday</label>
                <label class="col-md-3"><input type="checkbox" name="days[]" value="Mon" {{(isset($groupSubject)?((in_array("Mon", explode(",", $groupSubject->users->first()->pivot->days)))?'checked':''): '')}}>Monday</label>
                <label class="col-md-3"><input type="checkbox" name="days[]" value="Tue" {{(isset($groupSubject)?((in_array("Tue", explode(",", $groupSubject->users->first()->pivot->days)))?'checked':''): '')}}>Tuesday</label>
            </div>
            <div class="row">
                <label class="col-md-3"></label>
                <label class="col-md-3"><input type="checkbox" name="days[]" value="Wed" {{(isset($groupSubject)?((in_array("Wed", explode(",", $groupSubject->users->first()->pivot->days)))?'checked':''): '')}}>Wednesday</label>
                <label class="col-md-3"><input type="checkbox" name="days[]" value="Thu" {{(isset($groupSubject)?((in_array("Thu", explode(",", $groupSubject->users->first()->pivot->days)))?'checked':''): '')}}>Thursday</label>
                <label class="col-md-3"><input type="checkbox" name="days[]" value="Fri" {{(isset($groupSubject)?((in_array("Fri", explode(",", $groupSubject->users->first()->pivot->days)))?'checked':''): '')}}>Friday</label>
                <label class="col-md-3"></label>
                <label class="col-md-3"><input type="checkbox" name="days[]" value="Sat" {{(isset($groupSubject)?((in_array("Sat", explode(",", $groupSubject->users->first()->pivot->days)))?'checked':''): '')}}>Saturday</label>
            </div>
        </div>
         @error('days')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>

    <div class="col-md-6 mt-5" >
        <div class="row align-items-center">
            <label for="max_class_per_day" class=" col-md-4 form-label " >Max Class Per Day<span class="red_text"><b>*</b></span></label>
            <input type="text" class=" col-md-4 form-control" id="max_class_per_day" name="max_class_per_day" required value="{{isset($groupSubject)?old('max_class_per_day', $groupSubject->users->first()->pivot->max_class_per_day):old('max_class_per_day')}}">
        </div>
         @error('max_class_per_day')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>

</div>

