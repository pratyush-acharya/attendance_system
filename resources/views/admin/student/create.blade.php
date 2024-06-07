@extends('layouts.admin.app')

@section('title','Student')

@section('content')

<div class="below_header">
    <h1>Student</h1>
    @include('layouts.admin.formTabs')
</div>
@include('layouts.basic.tableHead',["table_title" => "Create Student"])

<div class="container1">
    <form action="{{ route('student.store')}}" method='POST' name="myform" class="form-group">
       @csrf
       @include('admin.student._form')
        <div class="d-grid col-md-1 button">
            <button class="btn btn-primary" type="submit">Add</button>
        </div>
    </form>
</div>
@endsection