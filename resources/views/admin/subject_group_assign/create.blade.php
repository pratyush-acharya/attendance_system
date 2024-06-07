@extends('layouts.admin.app')

@section('title','Assign Subject to Section')

@section('content')

<div class="below_header">
    <h1>Assign Subject to Section</h1>
    @include('layouts.admin.formTabs')
</div>

@include('layouts.basic.tableHead',["table_title" => "Assign Subject to Section"])

<div class="container1">
    <form action="{{ route('subject_group_assign.store')}}" method='POST' name="myform" class="form-group">
        @csrf
       @include('admin.subject_group_assign._form')
        <div class="d-grid col-md-1 button">
            <button class="btn btn-primary" type="submit">Add</button>
        </div>
    </form>
</div>
@endsection
@section('scripts')
<script type='text/javascript'>
    $(document).ready( function(){
        $('#group').select2();

        $('#subject').select2();

    });
</script>
@endsection
