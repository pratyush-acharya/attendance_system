@extends('layouts.admin.app')

@section('title','Section')

@section('content')

<div class="below_header">
    <h1>Section</h1>
    @include('layouts.admin.formTabs')
</div>
@include('layouts.basic.tableHead',["table_title" => "Edit Section"])
<div class="container1">
    <form action="{{ route('group.update',$group->id)}}" method='POST' name="myform" class="form-group">
        @csrf
        @method('PUT')
        @include('admin.group._form')
        <div class="d-grid col-md-1 button">
            <button class="btn btn-primary" type="submit">Update</button>
        </div>
    </form>
</div>
@endsection