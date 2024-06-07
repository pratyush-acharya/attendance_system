@extends('layouts.admin.app')

@section('title','Subject')

@section('content')

<div class="below_header">
    <h1>Subject</h1>
    @include('layouts.admin.formTabs')
</div>
@include('layouts.basic.tableHead',["table_title" => "Create Subject"])

<div class="container1">
    <form action="{{ route('subject.store.bulk')}}" method='POST' name="myform" class="form-group">
       @csrf
       
        @php
            $types = ['main','elective', 'credit']
        @endphp
        <table class="table table-bordered" id="dynamicAddRemove">  
            <tr>
                <th class="text-center">Subject Code<span class="red_text"><b>*</b></span></th>
                <th class="text-center">Subject Name<span class="red_text"><b>*</b></span></th>
                <th class="text-center">Subject Type<span class="red_text"><b>*</b></span></th>
                <th class="text-center">Action</th>
            </tr>
            <tr>  
                <td><input type="text" name="subjects[0][code]" value="{{!empty(old('subjects[0][code]')) ? old('subjects[0][code]') : ''}}" placeholder="Enter Subject Code" class="form-control" style="width:100% !important" /></td>  
                <td><input type="text" name="subjects[0][name]" value="{{!empty(old('subjects[0][name]')) ? old('subjects[0][name]') : ''}}" placeholder="Enter Subject Name" class="form-control" style="width:100% !important"  /></td>  
                <td>
                    <select id="subject_type" name="subjects[0][type]" class="col-md-4 form-control form-select  form-select-sm " style="width:100% !important">
                    <option  disabled selected>--Choose Subject Type--</option>
                        @foreach($types as $type)
                        <option 
                            value="{{ $type }}" 
                            {{ (!empty(old('type')) && old('type') == $type) ? 'selected': ''}}
                            {{ (isset($subject) && $subject->type == $type && empty(old('type'))) ? 'selected' : '' }} 
                            >
                            {{ ucfirst($type)}}
                        </option>
                        @endforeach
                    </select>
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
        <td><input type="text" name="subjects[' + i + '][code]" placeholder="Enter Subject Code" class="form-control" style="width:100% !important" /></td>  \
        <td><input type="text" name="subjects[' + i + '][name]" placeholder="Enter Subject Name" class="form-control" style="width:100% !important"  /></td>  \
        <td>\
            <select id="subject_type" name="subjects['+ i + '][type]" class="col-md-4 form-control form-select  form-select-sm" style="width:100% !important" >\
            <option  disabled selected>--Choose Subject Type--</option>\
            <option value="main">Main</option>\
            <option value="elective">Elective</option>\
            <option value="credit">Credit</option>\
            </select>\
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