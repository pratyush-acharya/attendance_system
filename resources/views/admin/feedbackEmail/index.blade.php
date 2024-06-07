@extends('layouts.admin.app')

@section('title','Feedback Email')

@section('content')

<div class="below_header">
    <h1>Feedback Email</h1>
    @include('layouts.admin.formTabs')
</div>

@if($count >= 1)
@include('layouts.basic.tableHead',["table_title" => "Feedback Email List"])
@else
@include('layouts.basic.tableHead',["table_title" => "Feedback Email List", "url" => "feedbackEmail.create"])
@endif

<div class="table_container mt-3">    
    <table class="_table mx-auto amsTable">
        <thead>
            <tr class="table_title">
                <th>S.N</th>
                <th>Email To</th>
                <th>Email CC</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($feedbackEmails as $feedbackEmail)
            <tr>
                <td>{{ $loop->iteration }}</td>
                
                @php
                    $email_to = explode(",",$feedbackEmail->email_to);
                    $email_cc = explode(",",$feedbackEmail->email_cc);
                @endphp

                <td>
                @foreach($email_to as $email)
                    {{ $email }}
                    <br>
                @endforeach
                </td>

                <td>
                @forelse($email_cc as $email)
                    {{ $email }} 
                    <br>
                @empty
                    <td>No Email CC</td>
                @endforelse
                </td>

                <td class="text-center">
                    <a href="{{route('feedbackEmail.edit',$feedbackEmail->id)}}"><i class="far fa-edit"></i></a> 
                    | 
                    <form action="{{ route('feedbackEmail.delete',$feedbackEmail->id)}}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete action border-0"><i class="fas fa-trash-alt action"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <td colspan='4' class="text-center">No feedback emails available</td>
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