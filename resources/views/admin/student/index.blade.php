@extends('layouts.admin.app')

@section('title','Student')

@section('content')

<div class="below_header">
    <h1>Student</h1>
    @include('layouts.admin.formTabs')
</div>
@include('layouts.basic.tableHead',["table_title" => "Student List", "url" => "student.create.bulk"])

<!-- <div class="row"> -->
<section class="bulkbtn col-md-3">
    <a href="{{route('student.bulkUpload')}}">
        <button class="btn btn-primary">
            <i class='bx bx-add-to-queue'></i>
                Bulk Upload
        </button>
    </a>
</section> 

<!-- <section class="bulkbtn col-md-3">
    <a href="{{route('student-group-assign.create')}}">
        <button class="btn btn-primary">
            <i class='bx bx-add-to-queue'></i>
                Assign Students to Group
        </button>
    </a>
</section>
</div> -->
<div class="table_container mt-3">    
    <table class="_table mx-auto amsTable">
        <thead>
            <tr class="table_title">
                <th>S.N</th>
                <td>Name</td>
                <td>Email</td>
                <td>Roll No</td>
                <td>Batch</td>
                <td>Stream</td>
                <td>Status</td>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ ucfirst($student->name) }}</td>
                <td>{{ $student->email }}</td>
                <td>{{ $student->roll_no }}</td>
                <td>{{ ucfirst($student->batch->name) }}</td>
                <td>{{ ucfirst($student->batch->stream->name) }}</td>
                <td>{{ is_null($student->deleted_at) ? 'Active' : 'Dropped' }}</td>
                <td class="text-center">
                    <a href="{{route('student.edit',$student->id)}}"><i class="far fa-edit"></i></a> 
                    | 
                    @if(is_null($student->deleted_at))
                    <form action="{{ route('student.delete',$student->id)}}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete action border-0"><i class="fas fa-trash-alt action"></i></button>
                    </form>
                    @else
                    <form action="{{ route('student.restore',$student->id)}}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete text-success border-0"><i class="fas fa-trash-restore"></i></button>
                    </form>
                    @endif

                </td>
            </tr>
            @empty
            <td colspan='6' class="text-center">No Students available</td>
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