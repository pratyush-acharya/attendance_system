@extends('layouts.admin.app')

@section('title','Subject')

@section('content')

<div class="below_header">
    <h1>Subject</h1>
    @include('layouts.admin.formTabs')
</div>
@include('layouts.basic.tableHead',["table_title" => "Subject List", "url" => "subject.create.bulk"])

<div class="table_container mt-3">    
    <table class="_table mx-auto amsTable">
        <thead>
            <tr class="table_title">
                <th>S.N</th>
                <th>Subject Code</th>
                <th>Subject Name</th>
                <th>Subject Type</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subjects as $subject)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ ucfirst($subject->code) }}</td>
                <td>{{ ucfirst($subject->name) }}</td>
                <td>{{ ucfirst($subject->type) }}</td>
                <td class="text-center">
                    <a href="{{route('subject.edit',$subject->id)}}"><i class="far fa-edit"></i></a> 
                    | 
                    <form action="{{ route('subject.delete',$subject->id)}}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete action border-0"><i class="fas fa-trash-alt action"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <td colspan='6' class="text-center">No sections available</td>
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