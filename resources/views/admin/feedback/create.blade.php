@extends('layouts.admin.app')

@section('title','Feedback')

@section('content')

@include('layouts.basic.tableHead',["table_title" => "Fill Feedback"])

<div class="container1">
    <form action="{{ route('feedback.store')}}" method='POST' name="myform" class="form-group" enctype="multipart/form-data">
        @csrf
       @include('admin.feedback._form')
        <div class="d-grid col-md-1 button">
            <button class="btn btn-primary" type="submit">Add</button>
        </div>
    </form>
</div>
@endsection