@extends('layouts.admin.app')

@section('title','Subject')

@section('content')

<div class="below_header">
    <h1>Subject</h1>
    @include('layouts.admin.formTabs')
</div>
@include('layouts.basic.tableHead',["table_title" => "Edit Subject"])

<div class="container1">
    <form action="{{ route('subject.update',$subject->id)}}" method='POST' name="myform" class="form-group">
        @csrf
        @method('PUT')
        @include('admin.subject._form')
        <div class="d-grid col-md-1 button">
            <button class="btn btn-primary" type="submit">Update</button>
        </div>
    </form>
</div>
@endsection