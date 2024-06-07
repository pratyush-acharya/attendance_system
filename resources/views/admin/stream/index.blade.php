@extends('layouts.admin.app')

@section('title','Stream')

@section('content')

<div class="below_header">
    <h1>Stream</h1>
    @include('layouts.admin.formTabs')
</div>

@include('layouts.basic.tableHead',["table_title" => "Stream List", "url" => "stream.create"])

<!-- view streams section -->

<div class="table_container mt-3">
    <table class="_table mx-auto amsTable">
        <thead>
            <tr class="table_title">
                <th>S.N</th>
                <th>Stream Name</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($streams as $stream)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $stream->name }}</td>
                <td class="text-center">
                    <a href="{{route('stream.edit',$stream->id)}}"><i class="far fa-edit"></i></a> 
                    | 
                    <form action="{{ route('stream.delete',$stream->id)}}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete action border-0"><i class="fas fa-trash-alt action"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <td colspan='4' class="text-center">No streams available</td>
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