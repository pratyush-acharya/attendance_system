@extends('layouts.admin.app')

@section('title','Student')

@section('content')

<div class="below_header">
    <h1>Student</h1>
    @include('layouts.admin.formTabs')
</div>
@include('layouts.basic.tableHead',["table_title" => "Bulk Upload Student"])


<div class="container1">
    <form action="{{route('student.bulkUpload')}}" method="POST" name="myform" class="form-group" enctype="multipart/form-data">
    @csrf
        <div class="row">
            <div class="col-name  mt-4">
                <div class="row align-items-center">
                <label for="student_csv" class=" col-md-2 form-label" >CSV File<span class="red_text"><b>*</b></span></label>
                <input type="file" name="student_csv" class="form-control" id="student_csv" required>
                </div>
            </div>
        </div>
        <div class="d-grid col-md-1 button">
            <button class="btn btn-primary" type="submit">Add</button>
        </div>
    </form>
</div>
@endsection

    <!-- <form action="{{route('student.bulkUpload')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="student_csv">
        <button>Submit</button>
    </form> -->
