<div class="row gx-5 align-items-center">
    <div class="col-md-4 col-name" >
        <div class="row align-items-center">
            <label for="name" class=" col-md-4 form-label " >Stream Name<span class="red_text"><b>*</b></span></label>
            <input type="text" class=" col-md-4 form-control" id="name" name="name" required value="{{!empty(old('name')) ? old('name') : $stream->name ?? ''}}">
        </div>
    </div>
    @error('name')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>