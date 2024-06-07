@extends('layouts.admin.app')

@section('title','Holiday')

@section('content')

<div class="below_header">
    <h1>Holiday</h1>
    @include('layouts.admin.formTabs')
</div>
@include('layouts.basic.tableHead',["table_title" => "Create Holiday"])

<div class="container1">
    <form action="{{ route('holiday.store')}}" method='POST' name="myform" class="form-group">
        @csrf
       @include('admin.holiday._form')
        <div class="d-grid col-md-1 button">
            <button class="btn btn-primary" type="submit">Add</button>
        </div>
    </form>
</div>
@endsection
@section('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $('#date').flatpickr({
            dateFormat: "Y-m-d",
            mode: "multiple"
        });
    </script>
@endsection