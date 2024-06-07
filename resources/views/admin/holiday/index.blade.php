@extends('layouts.admin.app')

@section('title','Holiday')

@section('content')

<div class="below_header">
    <h1>Holiday</h1>
    @include('layouts.admin.formTabs')
</div>

@include('layouts.basic.tableHead',["table_title" => "Holiday List", "url" => "holiday.create"])

<div class="import-ics-btn">
<a href="{{route('ics.create')}}">
    <button class="btn btn-success">
        <i class='bx bx-add-to-queue'></i>
            Import ICS
    </button>
</a>
</div>

<div class="table_container mt-3">
    <table class="_table mx-auto amsTable">
        <thead>
            <tr class="table_title">
                <th>S.N</th>
                <th>Holiday Name</th>
                <th>Date</th>
                <td>Batch</td>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($holidays as $holiday)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $holiday->name }}</td>
                <td>{{ $holiday->date }}</td>
                 @if($holiday->batch)
                    <td>{{ $holiday->batch->name }} - {{ $holiday->batch->stream->name}}</td>
                @else
                    <td>All</td>
                @endif

                <td class="text-center">
                    <a href="{{route('holiday.edit',$holiday->id)}}"><i class="far fa-edit"></i></a> 
                    | 
                    <form action="{{ route('holiday.delete',$holiday->id)}}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete action border-0"><i class="fas fa-trash-alt action"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <td colspan='5' class="text-center">No holidays available</td>
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