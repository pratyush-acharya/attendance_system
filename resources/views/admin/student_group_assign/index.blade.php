@extends('layouts.admin.app')

@section('title','Assign Students to Section')

@section('content')

<div class="below_header">
    <h1>Students Assigned to Section</h1>
    @include('layouts.admin.formTabs')
</div>
@include('layouts.basic.tableHead',["table_title" => "Assign Students to Section","url"=>"student-group-assign.create"])

<div class="table_container mt-3">    
    <table class="_table mx-auto amsTable">
        <thead>
            <tr class="table_title">
                <th>S.N</th>
                <th>Section Name</th>
                <th>Student Name</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($groups as $group)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>Class of {{ $group->batch->name}} {{ $group->batch->stream->name }} - {{ $group->name }}</td>
                        <td>
                            @foreach ($group->students as $student)
                                {{ $student->name }}
                                <br>
                            @endforeach
                        </td>
                        <td class="text-center">
                            <a href="{{route('student-group-assign.edit',$group->id)}}"><i class="far fa-edit"></i></a> 
                            | 
                            <form action="{{ route('student-group-assign.delete',$group->id)}}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete action border-0"><i class="fas fa-trash-alt action"></i></button>
                            </form>
                        </td>
                    </tr>
            @empty
                <td colspan='5' class="text-center">No Groups Created / Students Assigned to Group</td>
            @endforelse
        </tbody> 
    </table>    
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.amsTable').DataTable();
    })
</script>
@endsection