<div class="row gx-5 align-items-center">
    <div class="col-md-6 col-name" >
        <div class="row align-items-center">
            <label for="name" class=" col-md-4 form-label " >Full Name<span class="red_text"><b>*</b></span></label>
            <input type="text" class=" col-md-4 form-control" id="name" name="name" required value="{{!empty(old('name')) ? old('name') : $user->name ?? ''}}">
            @error('name')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="row align-items-center">
            <label for="email" class=" col-md-4 form-label" style="width: 35%;">Email<span class="red_text"><b>*</b></span></label>
            <input class=" col-md-4 form-control"  type="email" id="email" name="email" required value="{{!empty(old('email')) ? old('email') : $user->email ?? ''}}" >
            @error('email')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>
    </div>

   <div class="col-md-8 mt-5">
        <div class="row align-items-center">
            <label for="role" class=" col-md-3 form-label">Role<span class="red_text"><b>*</b></span></label>

            <div class="col-md-2 form-check form-check-inline">
                <input class="form-check-input" id="admin" type="checkbox" name="role[]" value="1" {{(isset($roles) && $roles->contains(1)) ? 'checked':''}}
                {{ old('role') == '1' ? 'checked':'' }}>
                <label class="form-check-label" for="admin">
                    Admin
                </label>
            </div>

            <div class="col-md-3 form-check form-check-inline ms-1">
                <input class="form-check-input" id="superadmin" type="checkbox" name="role[]" value="2" {{(isset($roles) && $roles->contains(2)) ? 'checked':''}}
                    {{ old('role') == '2' ? 'checked':'' }}>
                <label class="form-check-label" for="superadmin">
                    Super Admin
                </label>
            </div>

            <div class="col-md-1 form-check form-check-inline">
                <input class="form-check-input" id="teacher" type="checkbox" name="role[]" value="3" {{(isset($roles) && $roles->contains(3)) ? 'checked':''}}
                    {{ old('role') == '3' ? 'checked':'' }}>
                <label class="form-check-label" for="teacher">
                    Teacher
                </label>
            </div>
        </div>
        @error('role')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div> 
</div>
