@extends('layouts.admin.app')

@section('title','Holiday')

@section('content')

<div class="below_header">
    <h1>Holiday</h1>
    @include('layouts.admin.formTabs')
</div>
@include('layouts.basic.tableHead',["table_title" => "Add Ics Events"])

<div class="container1">
    <form action="{{ route('ics.store')}}" method='POST' name="myform" class="form-group"  enctype="multipart/form-data">
        @csrf
       <div class="row gx-5 align-items-center">
            <div class="col-md-6 col-name" >
                <div class="row align-items-center">
                    <label for="file" class=" col-md-4 form-label " >Upload File<span class="red_text"><b>*</b></span></label>
                    <input type="file" class=" col-md-4 form-control" id="file" name="file" required>
                    @error('file')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

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