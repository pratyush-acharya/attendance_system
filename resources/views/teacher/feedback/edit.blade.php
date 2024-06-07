@extends('layouts.teacher.app')

@section('title','Feedback')

@section('content')

@include('layouts.basic.tableHead',["table_title" => "Edit Feedback"])

<div class="container1">
    <form action="{{ route('feedback.update',$feedback->id)}}" method='POST' name="myform" class="form-group">
        @csrf
        @method('PUT')
        @include('teacher.feedback._form')
        <div class="d-grid col-md-1 button">
            <button class="btn btn-primary" type="submit">Update</button>
        </div>
    </form>
</div>
@endsection