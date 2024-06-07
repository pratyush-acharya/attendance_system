@extends('layouts.admin.app')

@section('title','Attendance Permission')

@section('content')

@include('layouts.basic.tableHead',["table_title" => "Attendance Permissions to Teachers", "url" => "attendance-permission.create"])

<div class="table_container mt-3">    
    <table class="_table mx-auto amsTable">
        <thead>
            <tr class="table_title">
                <th>S.N</th>
                <th>Permission Name</th>
                <th>Permission Status</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendancePermissions as $attendancePermission)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ ucfirst($attendancePermission->name) }}</td>
                <td>{{ $attendancePermission->status == 1? 'Granted':'Discarded' }}</td>
                <td class="text-center">
                    <a href="{{route('attendance-permission.edit',$attendancePermission->id)}}"><i class="far fa-edit"></i></a> 
                     | 
                    <form action="{{ route('attendance-permission.delete',$attendancePermission->id)}}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete action border-0"><i class="fas fa-trash-alt action"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <td colspan='4' class="text-center">No Attendance Permissions available</td>
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