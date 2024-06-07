@extends('layouts.admin.app')

@section('title','Assign Teacher to Section Subject')

@section('content')

<div class="below_header">
    <h1>Assign Teacher to Section Subject</h1>
    @include('layouts.admin.formTabs')
</div>

@include('layouts.basic.tableHead',["table_title" => "Assign Teacher to Section Subject"])

<div class="container1">
    <form action="{{ route('teacher_subject_group_assign.store')}}" method='POST' name="myform" class="form-group">
        @csrf
       @include('admin.teacher_subject_group_assign._form')
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

            $('#teacher').select2();
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#group').change(function () {
                $('#subject').empty();
                var $subject = $('#subject');
                $.ajax({
                    url: "{{ route('subject.search') }}",
                    data: {
                        group: $(this).val()
                    },
                    success: function (data) {
                        if(jQuery.isEmptyObject(data)){
                            $subject.html('<option selected disabled>---No Subject assigned to the Group---</option>')

                        }else{
                            $subject.html('<option value="" selected disabled>---Choose Subject---</option>');
                            $.each(data, function (id, value) {
                                $subject.append('<option value="' + id + '">' + value + '</option>');
                            });
                        }
                    }
                });
                $('#subject').val("");
            });

        });
    </script>
@endsection