@extends('layouts.teacher.app')

@section('title','Dashboard')

@section('content')

@include('teacher.dashboard.midsection')
<!-- upper cards section -->

@forelse ($subjects as $subject)
<div class="cardclass2" >
    <div class="row gx-2 shadow p-3 mb-3 bg-body rounded d-flex justify-content-center">
        <div class="card-icon col-sm-1">
            <i class='bookicon bx bxs-book-reader bx-md'></i>
        </div>
        
        <div class="col-sm-3">
            <div class="card-body">
                <h5 class="card-title"><b>{{ ucfirst($subject->code) }} - {{ ucfirst($subject->name) }}</b></h5><br>
                <div class="card-batchsem d-flex " >
                <span class="card-batch">
                    <h6>
                        <b>Batch: 
                            {{ $subject->groups->first()->batch->name}} {{ $subject->groups->first()->batch->stream->name}}
                            -Section {{ count($subject->groups) > 0 ? ucfirst($subject->groups->first()->name) : 'N/A'}}
                        </b>
                    </h6>
                </span>
                <span class="card-sem ps-4">
                    <h6><b>Semester: {{ $subject->groups->first()->batch->semester}}</b></h6>
                </span>
            </div>
               
            </div>
        </div>
        <div class="col-sm-2">
            <div class="takeAttendancebtn">
                <a class="btn btn-primary" href="{{route('attendance.create',$subject->teacherSubjectGroup)}}"> </i> Take Attendance</a>
               
            </div>
           
        </div>

        <div class="col-sm-2">
            <div class="card-present d-flex">
                <span class="present">
                    <h5><b>Present :</b></h5>
                </span>
                <span class="presentnum ps-2">
                    <h5><b>{{ $subject->getPresentCount($subject->groups->first()->batch)}}</b></h5>
                </span>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="card-absent d-flex">
                <span class="absent">
                    <h5><b>Absent :</b></h5>
                </span>
                <span class="absentnum ps-2">
                    <h5><b>{{ $subject->getAbsentCount($subject->groups->first()->batch) }}</b></h5>
                </span>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="card-leave d-flex">
                <span class="leave">
                    <h5><b>Leave :</b></h5>
                </span>
                <span class="leavenum ps-2">
                    <h5><b>{{$subject->getLeaveCount($subject->groups->first()->batch) }}</b></h5>
                </span>
            </div>
        </div>


    </div>

   
</div>

@empty
<div class="cardclass2" >
    <div class="row gx-2 shadow p-3 mb-3 bg-body rounded d-flex justify-content-center">

        You have not been assigned any subject.

    </div>   
</div>

@endforelse


@endsection