@extends('layouts.admin.app')

@section('title','User')

@section('content')

<div class="below_header">
    <h1>User</h1>
    @include('layouts.admin.formTabs')
</div>
@include('layouts.basic.tableHead',["table_title" => "User List", "url" => "user.create.bulk"])

<div class="table_container mt-3">
    <table class="_table mx-auto amsTable">
        <thead>
            <tr class="table_title">
                <th>S.N</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>@foreach($user->roles as $role)
                        {{ucfirst($role->roles)}}
                    @endforeach
                </td>
                <td class="text-center">
                    <a href="{{route('user.edit',$user->id)}}"><i class="far fa-edit"></i></a> 
                    | 
                    <form action="{{ route('user.delete',$user->id)}}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete action border-0"><i class="fas fa-trash-alt action"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <td colspan='4' class="text-center">No users available</td>
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