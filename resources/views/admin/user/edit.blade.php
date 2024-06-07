@extends('layouts.admin.app')

@section('title','User')

@section('content')

<div class="below_header">
    <h1>User</h1>
    @include('layouts.admin.formTabs')
</div>
@include('layouts.basic.tableHead',["table_title" => "Edit User"])

<div class="container1">
    <form action="{{ route('user.update',$user->id) }}" method='POST' name="myform" class="form-group">
        @csrf
        @method('PUT')
        @include('admin.user._form')
            <div class="d-grid col-md-1 button">
                <button class="btn btn-primary" type="submit">Update</button>
            </div>
    </form>
</div>
@endsection