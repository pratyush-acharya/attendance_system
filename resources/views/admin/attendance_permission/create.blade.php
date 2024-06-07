@extends('layouts.admin.app')

@section('title','Attendance Permission')

@section('content')

@include('layouts.basic.tableHead',["table_title" => "Create Attendance Permission"])

<div class="container1">
    <form action="{{ route('attendance-permission.store')}}" method='POST' name="myform" class="form-group" enctype="multipart/form-data">
        @csrf
       @include('admin.attendance_permission._form')
        <div class="d-grid col-md-1 button">
            <button class="btn btn-primary" type="submit">Add</button>
        </div>
    </form>
</div>
@endsection