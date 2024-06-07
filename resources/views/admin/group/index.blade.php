@extends('layouts.admin.app')

@section('title','Section')

@section('content')

<div class="below_header">
    <h1>Section</h1>
    @include('layouts.admin.formTabs')
</div>
@include('layouts.basic.tableHead',["table_title" => "Section List", "url" => "group.create"])

<div class="table_container mt-3">    
    <table class="_table mx-auto amsTable">
        <thead>
            <tr class="table_title">
                <th>S.N</th>
                <th>Section Name</th>
                <th>Section Type</th>
                <th>Batch</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($groups as $group)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ ucfirst($group->name) }}</td>
                <td>{{ ucfirst($group->type) }}</td>
                <td>{{ $group->batch->name }} - {{ $group->batch->stream->name}}</td>
                <td class="text-center">
                    <a href="{{route('group.edit',$group->id)}}"><i class="far fa-edit"></i></a> 
                    | 
                    <form action="{{ route('group.delete',$group->id)}}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete action border-0"><i class="fas fa-trash-alt action"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <td colspan='5' class="text-center">No Students asigned to Section</td>
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