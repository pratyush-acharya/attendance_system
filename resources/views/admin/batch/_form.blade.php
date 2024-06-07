<div class="row gx-5 align-items-center">
    <div class="col-md-6 col-name" >
        <div class="row align-items-center">
            <label for="name" class=" col-md-4 form-label " >Batch Name<span class="red_text"><b>*</b></span></label>
            <input type="text" class=" col-md-4 form-control" id="name" name="name" required value="{{!empty(old('name')) ? old('name') : $batch->name ?? ''}}">
             @error('name')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>

   <div class="col-md-6">
        <div class="row align-items-center">
            <label for="stream_id" class=" col-md-4 form-label" >Stream<span class="red_text"><b>*</b></span></label>
            
            <select id="stream_id" name="stream_id" class="col-md-4 form-control form-select  form-select-sm " >
               <option value="" disabled selected>--Choose Stream--</option>
                @forelse($streams as $stream)
                <option 
                    value="{{ $stream->id}}" 
                    {{ (!empty(old('stream_id')) && old('stream_id') == $stream->id) ? 'selected': ''}}
                    {{ (isset($batch) && $batch->stream_id == $stream->id && empty(old('stream_id'))) ? 'selected' : '' }} 
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
</div>
<div class="row gx-5 align-items-center">
    <div class="col-md-6 col-name mt-4">
        <div class="row align-items-center">
            <label for="semester" class=" col-md-4 form-label" >Semester<span class="red_text"><b>*</b></span></label>
            <select id="semester" name="semester" class="col-md-4 form-control form-select  form-select-sm " >
                <option  disabled selected class="text-center">--Choose Semester--</option>
                @for ($i=1; $i<=8; $i++)
                <option value={{$i}} 
                    {{!empty(old('semester'))
                        ?(old('semester') == $i ? 'selected' : '')
                        : (!isset($batch) ? '' : ($batch->semester == $i ? 'selected': ''))
                    }}
                    >{{$i}}
                </option>
                @endfor
            </select>
             @error('semester')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="col-md-6 mt-4">
        <div class="row align-items-center">
            <label for="start_date" class=" col-md-4 form-label" >Start Date<span class="red_text"><b>*</b></span></label>
            <input type="date" class=" col-md-4 form-control" id="start_date" name="start_date" required value="{{!empty(old('start_date')) ? old('start_date') : $batch->start_date ?? ''}}">
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
            <input type="date" class=" col-md-4 form-control" id="end_date" name="end_date" required value="{{!empty(old('end_date')) ? old('end_date') : $batch->end_date ?? ''}}">
            @error('end_date')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
