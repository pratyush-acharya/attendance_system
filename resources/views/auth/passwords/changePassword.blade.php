@extends(strtolower(Session('role')) == 'admin'?'layouts.admin.app':'layouts.teacher.app')
@section('title','Change Password')
@section('content')
<!-- page title start -->
<section class="my-3 pt-3">
    <div class="text-center">
        <h1 class="fs-2 title">Change Password</h1>
    </div>
    <div class="underline mx-auto"></div>
</section>
<!-- page title end -->

<!-- form start -->
<section class="change_password_form_container mx-auto">
    <div class="row mx-auto">
        <form class="main_form p-4" method="POST" action="/user/password">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-4">
                        <label class="form-label" for="current_password">Current Password*{{ \Auth::user()->role }}</label>
                        <input type="password" style="width:100% !important;" class="form-control" id="current_password" placeholder="Enter Old Password" name="current_password">
                            <p class="text-danger">{{ $errors->updatePassword->first('current_password') }}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-4">
                        <label class="form-label" for="password">New Password*</label>
                        <input type="password" style="width:100% !important;" class="form-control" id="password" placeholder="Enter New Password" name="password">
                        <p class="text-danger">{{ $errors->updatePassword->first('password') }}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-4">
                        <label class="form-label" for="password_confirmation">Confirm password*</label>
                        <input type="password" style="width:100% !important;" class="form-control" id="password_confirmation" placeholder="Re-enter New Password" name="password_confirmation">
                    </div>
                </div>
            </div>
            <center><button type="submit" class="btn btn-primary mt-2">Change</button></center>   
        </form>
    </div>
</section>
<!-- form end -->
@endsection