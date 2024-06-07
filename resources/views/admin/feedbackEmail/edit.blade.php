@extends('layouts.admin.app')

@section('title','Feedback Email')

@section('content')

<div class="below_header">
    <h1>Feedback Email</h1>
    @include('layouts.admin.formTabs')
</div>

@include('layouts.basic.tableHead',["table_title" => "Edit Feedback Email"])

<div class="container1">
    <form action="{{ route('feedbackEmail.update',$feedbackEmail->id)}}" method='POST' name="myform" class="form-group">
        @csrf
        @method('PUT')
        @include('admin.feedbackEmail._form')
        <div class="d-grid col-md-1 button">
            <button class="btn btn-primary" type="submit">Update</button>
        </div>
    </form>
</div>
@endsection