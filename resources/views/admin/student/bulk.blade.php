@extends('layouts.admin.app')

@section('title','Student')

@section('content')

<div class="below_header">
    <h1>Student</h1>
    @include('layouts.admin.formTabs')
</div>
@include('layouts.basic.tableHead',["table_title" => "Create Student"])

<div class="container1">
    <form action="{{ route('student.store.bulk')}}" method='POST' name="myform" class="form-group">
       @csrf
    <div class="row gx-5 align-items-center">
        <div class="col-md-6 mb-5">
            <div class="row align-items-center">
                <label for="batch_id" class=" col-md-4 form-label" >Batch<span class="red_text"><b>*</b></span></label>
                <select id="batch_id" name="batch_id" class="form-control form-select form-select-md ">
                <option value="" disabled selected class="text-center">--Choose Batch--</option>
                    @forelse($batches as $batch)
                    <option 
                        value="{{ $batch->id}}" 
                        {{ (!empty(old('batch_id')) && old('batch_id') == $batch->id) ? 'selected': ''}}
                        {{ (isset($student) && $student->batch_id == $batch->id && empty(old('batch_id'))) ? 'selected' : '' }} 
                        >
                        {{ $batch->name }} - {{ $batch->stream->name}}
                    </option>
                    @empty
                        <option value="" disabled>No options available</option>
                    @endforelse
                </select>

                @error('batch_id')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

        <table class="table table-bordered" id="dynamicAddRemove">  
            <tr>
                <th class="text-center">Full Name<span class="red_text"><b>*</b></th>
                <th class="text-center">Email<span class="red_text"><b>*</b></th>
                <th class="text-center">Roll No.<span class="red_text"><b>*</b></th>
                <th class="text-center">Action</th>
            </tr>
            <tr>  
                <td><input type="text" name="students[0][name]" value="{{!empty(old('students[0][name]')) ? old('students[0][name]') : ''}}" placeholder="Enter Full Name" class="form-control" style="width:100% !important" /></td>  
                <td><input type="text" name="students[0][email]" value="{{!empty(old('students[0][email]')) ? old('students[0][email]') : ''}}" placeholder="Enter Email" class="form-control" style="width:100% !important" /></td>  
                <td><input type="text" name="students[0][roll_no]" value="{{!empty(old('students[0][roll_no]')) ? old('students[0][roll_no]') : ''}}" placeholder="Enter Roll No." class="form-control" style="width:100% !important"  /></td>  
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
    $("#add-btn").click(function () {
        ++i;
   
        $("#dynamicAddRemove").append('<tr>\
        <td><input type="text" name="students[' + i + '][name]" placeholder="Enter Full Name" class="form-control" style="width:100% !important" /></td>  \
        <td><input type="text" name="students[' + i + '][email]" placeholder="Enter Email" class="form-control" style="width:100% !important" /></td>  \
        <td><input type="text" name="students[' + i + '][roll_no]" placeholder="Enter Roll No." class="form-control" style="width:100% !important"  /></td>  \
        <td><button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></td> \
        </tr>'
        );
            
    });

    $(document).on('click', '.remove-input-field', function () {
        $(this).parents('tr').remove();
    });
</script>
@endsection