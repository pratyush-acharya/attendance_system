@extends('layouts.admin.app')

@section('title','Stream')

@section('content')


<div class="below_header">
    <h1>Stream</h1>
    @include('layouts.admin.formTabs')
</div>

@include('layouts.basic.tableHead',["table_title" => "Edit Stream"])

<div class="container1">
    <form action="{{ route('stream.update',$stream->id) }}" method='POST' name="myform" class="form-group">
        @csrf
        @method('PUT')
        @include('admin.stream._form')
            <div class="d-grid col-md-1 button">
                <button class="btn btn-primary" type="submit">Update</button>
            </div>
    </form>
</div>
@endsection