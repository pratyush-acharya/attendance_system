@extends('layouts.teacher.app')

@section('title','Feedback')

@section('content')

@include('layouts.basic.tableHead',["table_title" => "Feedback List", "url" => "teacher.feedback.create"])

<div class="table_container mt-3">    
    <table class="teacher_table mx-auto amsTable">
        <thead>
            <tr class="table_title">
                <th>S.N</th>
                <th>Title</th>
                <th>Description</th>
                <th>Image</th>
                <th>Feedback From</th>
                <th>Status</th>
                <th>Issued Date</th>
                <th>Re-Issued Date</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($feedbacks as $feedback)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ ucfirst($feedback->title) }}</td>
                <td>{{ ucfirst($feedback->description) }}</td>

                @if($feedback->image != null)
                <td><a href="{{ route('teacher.feedback.download',$feedback->id) }}"><i class="fa fa-download" aria-hidden="true"></i></a> </td>
                @else
                <td>--</td>
                @endif

                <td>{{ ucfirst($feedback->user->name) }}</td>
                <td>{{ ucfirst($feedback->status) }}</td>
                <td>{{ date('Y-m-d',strtotime($feedback->created_at)) }}</td>

                @if($feedback->reissue_date != null)
                <td>{{ $feedback->reissue_date }}</td>
                @else
                <td>--</td>
                @endif

                <td>
                    @if($feedback->status == 'pending')
                    <form action="{{ route('teacher.feedback.delete',$feedback->id)}}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete bg-danger text-white">Delete</button>
                    </form>
                    
                    @elseif($feedback->status == 'rejected') 
                    <form action="{{ route('teacher.feedback.reissue',$feedback->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="delete bg-success text-white">Reissue</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <td colspan='9' class="text-center">No Feedbacks available</td>
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