@extends('layouts.admin.app')

@section('title','Subject')

@section('content')

<div class="below_header">
    <h1>Subject</h1>
    @include('layouts.admin.formTabs')
</div>
@include('layouts.basic.tableHead',["table_title" => "Create Subject"])

<div class="container1">
    <form action="{{ route('subject.store')}}" method='POST' name="myform" class="form-group">
        @csrf
       @include('admin.subject._form')
        <div class="d-grid col-md-1 button">
            <button class="btn btn-primary" type="submit">Add</button>
        </div>
    </form>
</div>
@endsection