@extends('layouts.admin.app')

@section('title','Edit Assigned Subject to Section')

@section('content')

<div class="below_header">
    <h1>Edit Section Subject</h1>
    @include('layouts.admin.formTabs')
</div>
@include('layouts.basic.tableHead',["table_title" => "Edit Assigned Subject to Section"])

<div class="container1">
    <form action="{{ route('subject_group_assign.update',$assignedGroup->id)}}" method='POST' name="myform" class="form-group">
        @csrf
        @method('PUT')
        @include('admin.subject_group_assign._form')
        <div class="d-grid col-md-1 button">
            <button class="btn btn-primary" type="submit">Update</button>
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