@extends('layouts.admin.app')

@section('title','User')

@section('content')

<div class="below_header">
    <h1>Assign Teacher to Section Subject</h1>
    @include('layouts.admin.formTabs')
</div>
@include('layouts.basic.tableHead',["table_title" => "Teacher Assigned to Section Subject List", "url" => "teacher_subject_group_assign.create"])

<div class="table_container mt-3">
    <table class="_table mx-auto amsTable">
        <thead>
            <tr class="table_title">
                <th>S.N</th>
                <th>Name</th>
                <th>Section</th>
                <th>Subject</th>
                <th>Days</th>
                <th>Max Class Per Day</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $count =1;
            @endphp
            @forelse($teachers as $teacher)
                @foreach ($teacher->groupSubjects as $groupSubject)
                    <tr>
                        <td>{{ $count }}</td>
                        <td>{{ $teacher->name }}</td>
                        <td>{{ $groupSubject->group_id }}</td>
                        <td>{{ $groupSubject->subject_id}}</td>
                        <td>{{ $groupSubject->pivot->days}}</td>
                        <td>{{ $groupSubject->pivot->max_class_per_day}}</td>
                        <td class="text-center">
                            <a href="{{route('teacher_subject_group_assign.edit', [$groupSubject->id, $teacher->id])}}"><i class="far fa-edit"></i></a> 
                            
                            {{-- <form action="{{ route('teacher_subject_group_assign.delete',$groupSubject->id)}}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="teacher" value="{{$teacher->id}}">
                                <button type="submit" class="delete action border-0"><i class="fas fa-trash-alt action"></i></button>
                            </form>
                            --}}
                        </td>
                        @php
                            $count++
                        @endphp
                    </tr>
                @endforeach
            @empty
            <td colspan='7' class="text-center">No users available</td>
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