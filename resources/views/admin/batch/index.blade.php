@extends('layouts.admin.app')

@section('title','Batch')

@section('content')

<div class="below_header">
    <h1>Batch</h1>
    @include('layouts.admin.formTabs')
</div>

@include('layouts.basic.tableHead',["table_title" => "Batch List", "url" => "batch.create"])

<div class="table_container mt-3">    
    <table class="_table mx-auto amsTable">
        <thead>
            <tr class="table_title">
                <th>S.N</th>
                <th>Batch Name</th>
                <th>Stream</th>
                <th>Semester</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($batches as $batch)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $batch->name }}</td>
                <td>{{ $batch->stream->name }}</td>
                <td>{{ $batch->semester }}</td>
                <td>{{ $batch->start_date }}</td>
                <td>{{ $batch->end_date }}</td>
                <td class="text-center">
                    @if($batch->end_date > date('Y-m-d'))
                        <a href="{{route('batch.edit',$batch->id)}}"><i class="far fa-edit"></i></a> 
                        | 
  
                        <button type="submit" class="delete action border-0" onclick="warningConfirmNormalDelete({{$batch->id}}, event)">
                            <i class="fas fa-trash-alt action"></i>
                        </button>
                    @else
                        <a href="{{route('batch.edit',$batch->id)}}"><i class="far fa-edit"></i></a> 
                        |
                        <a href="{{route('batch.semesterEndReport', $batch->id)}}"><i class="fas fa-file-download"></i></a>
                        |
                        <button type="submit" class="delete action border-0" onclick="warningSemesterEndDelete({{$batch->id}},{{$batch->semester}}, event)">
                            <i class="fas fa-trash-alt action"></i>
                        </button>
                    @endif
                </td>
            </tr>
            @empty
            <td colspan='4' class="text-center">No batchs available</td>
            @endforelse
        </tbody> 
    </table>    
</div>
@endsection

@section('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('.amsTable').DataTable();
    })
</script>
<script>
    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });

    function warningConfirmNormalDelete(data,event)
    {
        event.preventDefault();
        Swal.fire({
            title: "Are you sure you want to delete this batch? Everything assosciated with this batch will be deleted!",
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
                //we need to put something or it will throw error
                var route = '{{route("batch.delete", 'data')}}';
                // replace data with actual dynamic data
                route = route.replace('data', data);
                $.ajaxSetup({                   
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });
                $.ajax({
                    url: route,
                    method:"DELETE",
                    success:function(data)
                    {
                        if(data.status == 'true'){
                            setTimeout(function(){
                                location.reload(); 
                            }, 3000); 
                            Toast.fire({
                                    icon: 'success',
                                    title: data.msg
                            })
                        }else{
                            Toast.fire({
                                icon: 'error',
                                title: data.msg
                            })
                        }

                    },

                    error: function(data)
                    {
                        Toast.fire({
                                icon: 'error',
                                title: 'Error Occured'
                        })
                    }
                })
            }
            })
    }

    function warningSemesterEndDelete(data, semester,event)
    {
        event.preventDefault();
        if(semester == 8)
        {
            title = "Are you sure you want to delete this batch? The batch will also be deleted!";
        }else
        {
            title = "Are you sure you want to delete this batch?";
        }
        Swal.fire({
            title: title,
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
                //we need to put something or it will throw error
                var route = '{{route("batch.forceDelete", 'data')}}';
                // replace data with actual dynamic data
                route = route.replace('data', data);
                $.ajaxSetup({                   
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });
                $.ajax({
                    url: route,
                    method:"DELETE",
                    success:function(data)
                    {
                            setTimeout(function(){
                                location.reload(); 
                            }, 3000); 
                            Toast.fire({
                                    icon: 'success',
                                    title: data.msg
                            })
                    },
                    error: function(data)
                    {
                        Toast.fire({
                                icon: 'error',
                                title: 'Error Occured'
                        })
                    }
                })
            }
            })
    }
</script>
@endsection