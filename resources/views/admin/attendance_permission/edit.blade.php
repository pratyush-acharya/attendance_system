@extends('layouts.admin.app')

@section('title','Attendance Permission')

@section('content')


@include('layouts.basic.tableHead',["table_title" => "Edit Attendance Permission"])

<div class="container1">
    <form action="{{ route('attendance-permission.update',$attendancePermission->id)}}" method='POST' name="myform" class="form-group">
        @csrf
        @method('PUT')
        @include('admin.attendance_permission._form')
        <div class="d-grid col-md-1 button">
            <button class="btn btn-primary" type="submit">Update</button>
        </div>
    </form>
</div>
@endsection