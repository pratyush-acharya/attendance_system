@extends('layouts.admin.app')

@section('title','Exam')

@section('content')

<div class="below_header">
    <h1>Exam</h1>
    @include('layouts.admin.formTabs')
</div>

@include('layouts.basic.tableHead',["table_title" => "Exam List", "url" => "exam.create"])

<!-- view exams section -->

<div class="table_container mt-3">
    <table class="_table mx-auto amsTable">
        <thead>
            <tr class="table_title">
                <th>S.N</th>
                <th>Batch</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Exam Type</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($exams as $exam)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $exam->batch->name }} - {{ $exam->batch->stream->name}}</td>
                <td>{{ $exam->start_date }}</td>
                <td>{{ $exam->end_date }}</td>
                <td>{{ $exam->type }}</td>
                <td class="text-center">
                    <a href="{{route('exam.edit',$exam->id)}}"><i class="far fa-edit"></i></a> 
                    | 
                    <form action="{{ route('exam.delete',$exam->id)}}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete action border-0"><i class="fas fa-trash-alt action"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <td colspan='6' class="text-center">No Exams available</td>
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