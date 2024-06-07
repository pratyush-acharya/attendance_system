@extends('layouts.admin.app')

@section('title','Exam')

@section('content')


<div class="below_header">
    <h1>Exam</h1>
    @include('layouts.admin.formTabs')
</div>

@include('layouts.basic.tableHead',["table_title" => "Edit Exam"])

<div class="container1">
    <form action="{{ route('exam.update',$exam->id) }}" method='POST' name="myform" class="form-group">
        @csrf
        @method('PUT')
        @include('admin.exam._form')
            <div class="d-grid col-md-1 button">
                <button class="btn btn-primary" type="submit">Update</button>
            </div>
    </form>
</div>
@endsection