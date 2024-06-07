<div class="row gx-5 align-items-center">
    <div class="col-md-6 mt-5">
        <div class="row align-items-center">
            <label for="batch" class=" col-md-4 form-label" >Batch<span class="red_text"><b>*</b></span></label>
            
            <select id="batch" name="batch_id" class="col-md-4 form-control form-select  form-select-sm " >
               <option  disabled selected>--Choose Batches--</option>
                @foreach($batches as $batch)
                <option 
                    value="{{ $batch->id }}" 
                    {{ (!empty(old('batch_id')) && old('batch_id') == $batch->id) ? 'selected': ''}}
                    {{ (isset($exam) && $exam->batch_id == $batch->id && empty(old('batch_id'))) ? 'selected' : '' }} 
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

    <div class="col-md-6 mt-4">
        <div class="row align-items-center">
            <label for="start_date" class=" col-md-4 form-label" >Start Date<span class="red_text"><b>*</b></span></label>
            <input type="date" class=" col-md-4 form-control" id="start_date" name="start_date" required value="{{!empty(old('start_date')) ? old('start_date') : $exam->start_date ?? ''}}">
            @error('start_date')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

<div class="row gx-5 align-items-center">
    <div class="col-md-6 col-name mt-4">
        <div class="row align-items-center">
            <label for="end_date" class=" col-md-4 form-label" >End Date<span class="red_text"><b>*</b></span></label>
            <input type="date" class=" col-md-4 form-control" id="end_date" name="end_date" required value="{{!empty(old('end_date')) ? old('end_date') : $exam->end_date ?? ''}}">
            @error('end_date')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="col-md-6 col-name mt-4" >
        <div class="row align-items-center">
            <label for="type" class=" col-md-4 form-label " >Exam Type<span class="red_text"><b>*</b></span></label>
            <select name="type" id="type" class="col-md-4 form-control form-select  form-select-sm " >
                <option value="" disabled>--Choose Exam Type--</option>
                @foreach($types as $type)
                <option value="{{$type}}"
                    {{ (!empty(old('type')) && old('type') == $type) ? 'selected': ''}}
                    {{ (isset($exam) && $exam->type == old('type') && empty(old('type'))) ? 'selected' : '' }} 
                    
                >{{ucfirst($type)}}
                </option>
                @endforeach
            </select>
            @error('type')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>


