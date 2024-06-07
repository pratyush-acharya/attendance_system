@extends('layouts.admin.app')

@section('title','Assign Students to Section')

@section('content')

<div class="below_header">
    <h1>Assign Students to Section</h1>
    @include('layouts.admin.formTabs')
</div>
@include('layouts.basic.tableHead',["table_title" => "Edit Assignment of Students to Section"])

<div class="container1">
    <form action="{{ route('student-group-assign.update',$assignedGroup->id)}}" method='POST' name="myform" class="form-group">
        @csrf
        @method('PUT')
        @include('admin.student_group_assign._form')
        <div class="d-grid col-md-1 button">
            <button class="btn btn-primary" type="submit">Update</button>
        </div>
    </form>
</div>
@endsection


@section('scripts')
<script type='text/javascript'>
    $('.batch-livesearch').select2({
        ajax: {
            url: '{{ route('batch.search') }}',
            data: function (params) {
                var query = {
                    "_token": "{{ csrf_token() }}",
                    batch: params.term,
                    stream: $('#stream_id').val(), 
                }
                // console.log(query);

                // Query parameters will be ?search=[term]
                return query;
            },
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name ,
                            id: item.id
                        }
                    })
                };
                
                // console.log(data);
            },
            cache: false
        }
    });


    $('.student-livesearch').select2({
        ajax: {
            url: '{{ route('student.search') }}',
            data: function (params) {
                var query = {
                    "_token": "{{ csrf_token() }}",
                    student: params.term,                
                    stream: $('#stream_id').val(),
                    batch: $('#batch_id').val(),
                }
                    // Query parameters will be ?search=[term]
                    console.log(query);
                return query;
            },
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name ,
                            id: item.id
                        }
                    })
                };
                
                console.log(data);
            },
            cache: false
        }
    });

    $('.group-livesearch').select2({
        ajax: {
            url: '{{ route('group.search') }}',
            data: function (params) {
                var query = {
                    "_token": "{{ csrf_token() }}",
                    group: params.term,                
                    stream: $('#stream_id').val(),
                    batch: $('#batch_id').val(),
                }
                    // Query parameters will be ?search=[term]
                    console.log(query);
                return query;
            },
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name ,
                            id: item.id
                        }
                    })
                };
                
                console.log(data);
            },
            cache: false
        }
    });

</script>
@endsection