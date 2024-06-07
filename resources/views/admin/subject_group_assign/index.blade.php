@extends('layouts.admin.app')

@section('title','Section Subject')

@section('content')

<div class="below_header">
    <h1>Section Subject</h1>
    @include('layouts.admin.formTabs')
</div>

@include('layouts.basic.tableHead',["table_title" => "Assigned Subject to Section List", "url" => "subject_group_assign.create"])

<div class="table_container mt-3">    
    <table class="_table mx-auto amsTable">
        <thead>
            <tr class="table_title">
                <th>S.N</th>
                <th>Group</th>
                <th>Batch</th>
                <th>Stream</th>
                <th>Subject</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($groups as $group)

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $group->name }}</td>
                        <td>{{ $group->batch->name }}</td>
                        <td>{{ $group->batch->stream->name }}</td>
                        <td>
                            @foreach ($group->subjects as $subject)
                                {{ $subject->name }}
                                <br>
                            @endforeach
                        </td>
                        <td class="text-center">
                            <a href="{{route('subject_group_assign.edit',$group->id)}}"><i class="far fa-edit"></i></a> 
                            | 
                            <form action="{{ route('subject_group_assign.delete',$group->id)}}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete action border-0"><i class="fas fa-trash-alt action"></i></button>
                            </form>
                        </td>
                    </tr>
            @empty
                <td colspan='6' class="text-center">No Groups Created / Subjects Assigned to Group</td>
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