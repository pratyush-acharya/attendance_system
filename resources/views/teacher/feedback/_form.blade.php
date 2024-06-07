<div class="row gx-5 align-items-center">
    <div class="col-md-6 col-name" >
        <div class="row align-items-center">
            <label for="title" class=" col-md-4 form-label " >Title<span class="red_text"><b>*</b></span></label>
            <input type="text" class=" col-md-4 form-control" id="title" name="title" required value="{{!empty(old('title')) ? old('title') : $feedback->title ?? ''}}">
            @error('title')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="col-md-6 col-name" >
        <div class="row align-items-center">
            <label for="image" class=" col-md-4 form-label ">Upload Image:</label>
            <input type="file" id="image" name="image" class=" col-md-4 form-control">
            @error('image')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

<div class="row gx-5 align-items-center">
    <div class="col-md-6 col-name mt-5" >
        <div class="row align-items-center">
            <label for="description" class=" col-md-4 form-label " >Description<span class="red_text"><b>*</b></span></label>
            <textarea type="text" rows='5' class="col-md-4 flex-end form-control" id="description" name="description" required >{{!empty(old('description')) ? old('description') : $feedback->description ?? ''}}</textarea>
            @error('description')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
