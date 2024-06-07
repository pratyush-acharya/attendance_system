@extends('layouts.admin.app')

@section('title','Users')

@section('content')

<div class="below_header">
    <h1>User</h1>
    @include('layouts.admin.formTabs')
</div>
@include('layouts.basic.tableHead',["table_title" => "Create Users"])

<div class="container1">
    <form action="{{ route('user.store.bulk')}}" method='POST' name="myform" class="form-group">
       @csrf

        @if ($errors->any())
        <div class="alert alert-danger">
        <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
        </ul>
        </div>
        @endif
    
        <table class="table table-bordered" id="dynamicAddRemove">  
            <tr>
                <th class="text-center">Full Name<span class="red_text"><b>*</b></span></th>
                <th class="text-center">Email<span class="red_text"><b>*</b></span></th>
                <th class="text-center">Role<span class="red_text"><b>*</b></span></th>
                <th class="text-center">Action</th>
            </tr>
            <tr>  
                <td><input type="text" name="users[0][name]" value="{{!empty(old('users[0][name]')) ? old('users[0][name]') : ''}}" placeholder="Enter User Full Name" class="form-control" style="width:100% !important"  /></td>  
                <td><input type="text" name="users[0][email]" value="{{!empty(old('users[0][email]')) ? old('users[0][email]') : ''}}" placeholder="Enter User email" class="form-control" style="width:100% !important" /></td>  
                <td>
                    <div class="col-md-3 form-check form-check-inline ms-2">
                        <input class="form-check-input" id="admin" type="checkbox" name="users[0][role][]" value="1" {{(isset($roles) && $roles->contains(1)) ? 'checked':''}}
                        {{ old('role') == '1' ? 'checked':'' }}>
                        <label class="form-check-label" for="admin">
                            Admin
                        </label>
                    </div>
                    <div class="col-md-5 form-check form-check-inline ms-1">
                        <input class="form-check-input" id="superadmin" type="checkbox" name="users[0][role][]" value="2" {{(isset($roles) && $roles->contains(2)) ? 'checked':''}}
                            {{ old('role') == '2' ? 'checked':'' }}>
                        <label class="form-check-label" for="superadmin">
                            Super Admin
                        </label>
                    </div>
                    <div class="col-md-2 form-check form-check-inline me-2">
                        <input class="form-check-input" id="teacher" type="checkbox" name="users[0][role][]" value="3" {{(isset($roles) && $roles->contains(3)) ? 'checked':''}}
                            {{ old('role') == '3' ? 'checked':'' }}>
                        <label class="form-check-label" for="teacher">
                            Teacher
                        </label>
                    </div>
                    
                </td>  
                <td><button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></td>
            </tr>  
        </table> 
        <div class=" col-md-2 button">
            <button type="button" name="add" id="add-btn" class="btn btn-success">Add More</button>
            <button class="btn btn-primary" type="submit">Submit</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
   var i = 0;
   var types = ['main','elective', 'credit'];
   
    $("#add-btn").click(function () {
        ++i;
   
        $("#dynamicAddRemove").append('<tr>\
        <td><input type="text" name="users[' + i + '][name]" placeholder="Enter User Name" class="form-control" style="width:100% !important"  /></td>  \
        <td><input type="text" name="users[' + i + '][email]" placeholder="Enter User Email" class="form-control" style="width:100% !important" /></td>  \
        <td>\
             <div class="col-md-3 form-check form-check-inline ms-2">\
                <input class="form-check-input" id="admin'+i+'" type="checkbox" name="users[' + i + '][role][]" value="1">\
                <label class="form-check-label" for="admin'+i+'"> Admin </label> \
            </div>\
            <div class="col-md-5 form-check form-check-inline ms-1"> \
                <input class="form-check-input" id="superadmin'+i+'" type="checkbox" name="users[' + i + '][role][]" value="2"> \
                <label class="form-check-label" for="superadmin'+i+'">Super Admin</label>\
            </div>\
            <div class="col-md-2 form-check form-check-inline me-2">\
                <input class="form-check-input" id="teacher'+i+'" type="checkbox" name="users[' + i + '][role][]" value="3"> \
                <label class="form-check-label" for="teacher'+i+'">Teacher</label>\
            </div>\
        </td>  \
        <td><button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></td> \
        </tr>'
        );
            
    });

    $(document).on('click', '.remove-input-field', function () {
        $(this).parents('tr').remove();
    });
</script>
@endsection