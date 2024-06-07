<div class="row gx-5 align-items-center">
    <div class="col-md-6 col-name" >
        <div class="row align-items-center">
            <label for="name" class=" col-md-4 form-label " >Attendance Permission Name<span class="red_text"><b>*</b></span></label>
            <input type="text" class=" col-md-4 form-control" id="name" name="name" required value="{{!empty(old('name')) ? old('name') : $attendancePermission->name ?? ''}}">
            @error('name')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="col-md-6 col-name" >
        <div class="row align-items-center">
            <label for="status" class="form-label col-md-5" >Attendance Permission to Teachers<span class="red_text"><b>*</b></span></label>
            <!-- <div class="row">
                <div class="col-md-5"></div>-->
                <div class="form-check col-md-2"> 
                    <input class="form-check-input" type="radio" name="status" id="status0" value="1" checked
                    {{ (isset($attendancePermission) && $attendancePermission->status == '1') ? 'checked':''}}
                    {{ old('status') == '1' ? 'checked':'' }}>
                    <label class="form-check-label" for="status0">Grant</label>
                 </div>
                <div class="form-check col-md-2">
                    <input class="form-check-input" type="radio" name="status" id="status1" value="0" 
                    {{ (isset($attendancePermission) && $attendancePermission->status == '0') ? 'checked':''}}
                    {{ old('status') == '0' ? 'checked':''}}>
                    <label class="form-check-label" for="status1">Discard</label>
                </div>     
            <!-- </div>           -->
            @error('status')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
