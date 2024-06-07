@php
    $types = ['optional', 'compulsory']
@endphp
<div class="row gx-5 align-items-center">
    <div class="col-md-6 col-name">
        <div class="row align-items-center">
            <label for="name" class=" col-md-4 form-label " >Section Name<span class="red_text"><b>*</b></span></label>
            <input type="text" class=" col-md-4 form-control" id="name" name="name" required value="{{!empty(old('name')) ? old('name') : $group->name ?? ''}}">
            @error('name')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>


    <div class="col-md-6">
        <div class="row align-items-center">
            <label for="type" class=" col-md-4 form-label" >Section Type<span class="red_text"><b>*</b></span></label>
            
            <select id="type" name="type" class="col-md-4 form-control form-select  form-select-sm " >
               <option  disabled selected>--Choose Section Type--</option>
                @foreach($types as $type)
                <option 
                    value="{{ $type }}" 
                    {{ (!empty(old('type')) && old('type') == $type) ? 'selected': ''}}
                    {{ (isset($group) && $group->type == $type && empty(old('type'))) ? 'selected' : '' }} 
                    >
                    {{ ucfirst($type) }}
                </option>
                @endforeach
            </select>
            @error('type')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="col-md-6 mt-5">
        <div class="row align-items-center">
            <label for="batch" class=" col-md-4 form-label" >Batch<span class="red_text"><b>*</b></span></label>
            
            <select id="batch" name="batch_id" class="col-md-4 form-control form-select  form-select-sm " >
               <option  disabled selected>--Choose Batches--</option>
                @foreach($batches as $batch)
                <option 
                    value="{{ $batch->id }}" 
                    {{ (!empty(old('batch_id')) && old('batch_id') == $batch->id) ? 'selected': ''}}
                    {{ (isset($group) && $group->batch_id == $batch->id && empty(old('batch_id'))) ? 'selected' : '' }} 
                    >
                    {{ $batch->name }} - {{$batch->stream->name }}
                </option>
                @endforeach
            </select>
            @error('batch_id')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>

</div>

