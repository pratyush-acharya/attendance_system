@extends('layouts.teacher.app')

@section('title','Attendance Page')

@section('content')
<div class="below_header">
    <!-- <h1>Attendance</h1> -->
    <h1>Daily Attendance :{{ ucfirst($subject->code) }} - {{ ucfirst($subject->name) }} |
        {{ ucfirst($subject->groups->first()->batch->name) }} {{ ucfirst($subject->groups->first()->batch->stream->name) }}
         - Sec  {{ strlen($subject->groups->first()->name) > 0 ?ucfirst($subject->groups->first()->name) : 'N/A'}}</h1>
</div>
<!-- table start -->
<div class="table_container mt-5">
    <form>
        <table class="_table mx-auto">
            <tr class="table_title">
                <th class="border-end">Roll</th>
                <th class="border-end">Name</th>
                <th colspan="{{ $attendanceDates->count()}}" class="text-center border-end">Status</th>
                    @if(!$attendanceDates->has(now()->format('M/d')))
                        <th class="border-end"><i class='bx bxs-down-arrow text-primary'></i></th>
                    @endif
                <th class="border-end">Absent Days</th>
                <th class="border-end">Leave Days</th>
            </tr>

            <tr class="table_date">
                <th colspan="2" class="border-end"></th>
                @foreach ($attendanceDates as $date=>$attendanceDate)
                    <th class="border-end"> {{$date}}</th>
                @endforeach
                
                @if($attendanceDates->isEmpty())
                    <th colspan="1"></th>
                @endif                    
                <th colspan="1">
                    @if(!$attendanceDates->has(now()->format('M/d')))
                        {{date('M/d')}}
                    @endif
                </th>
            </tr>
            @foreach ($students as $student)
            <tr>
                <td class="border-end roll_no">{{ $student->roll_no }}</td>
                <td class="border-end">{{ $student->name }}</td>

                @forelse ($student->getAttendanceByDays() as $dateOfAttendance)
                    <td class="{{--$dateOfAtendance--}} border-end">
                    @if($dateOfAttendance['present']>0)
                        @for($i=1;$i<=$dateOfAttendance['present'];$i++)
                        <span class="attendanceSymbol presentSymbol">P</span>
                        @endfor
                    @endif
                    @if($dateOfAttendance['absent']>0)
                        @for($j=1;$j<=$dateOfAttendance['absent'];$j++)
                        <span class="attendanceSymbol absentSymbol">A</span>
                        @endfor
                    @endif
                    @if($dateOfAttendance['leave']>0)
                        @for($k=1;$k<=$dateOfAttendance['leave'];$k++)
                        <span class="attendanceSymbol leaveSymbol">L</span>
                        @endfor
                    @endif

                    </td>
                @empty
                    <td  class="text-center border-end"> Attendance has not been taken. </td>
                @endforelse
                @if(!$attendanceDates->has(now()->format('M/d')))
                    <td class="border-end student_attendance_status">
                        <div onclick="toggleState(this)" class="attendance-state" id="attendance_{{$student->roll_no}}" data-attendance-state= "1">
                            <img class="attendance_img" src="{{asset('assets/images/P.svg')}}" id="r_{{$student->roll_no}}">
                        </div>
                    </td>
                @endif
                <td>{{ $student->getAbsentDays($groupSubjectTeacherId) }}</td>
                <td>{{ $student->getLeaveDays($groupSubjectTeacherId) }}</td>
            </tr>
            @endforeach
            
        </table>
        <div class="row justify-content-center">
            @if ( !$attendanceDates->has(now()->format('M/d')))
                <div class="justify-content-center text-end my-3 me-5">
                    <button id="attendance_add" class="btn btn-success me-3 ms-5">Add More</button>
                    <button id="attendance_remove" class="btn btn-danger me-3">Delete</button>
                    <button class="btn btn-primary my-2 me-5" id="attendance_submit" >Submit</button>
                </div>
            @endif
        </div>
    </form>

    <!-- table end -->
</div>
<!--Container Main end-->

@endsection
@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

        var count = 1;
        var maxCount = {{ $assosciatedGroupSubject->users->first()->pivot->max_class_per_day}}
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
                      

        let imageLink = {
            0: "A.svg",
            1: "P.svg",
            2: "L.svg",
        };

        function toggleState(el) {
            let id = el.id;
            let attendanceState = el.getAttribute("data-attendance-state");

            attendanceState = (attendanceState +1) % 3;

            el.setAttribute("data-attendance-state", attendanceState);
            el.children[0].setAttribute("src", "http://" +window.location.host +"/assets/images/" +imageLink[attendanceState]);
        }

        // Submit Attendance
        let submit = document.getElementById("attendance_submit");
        submit.addEventListener("click", function(event){
            event.preventDefault();
                        
            Swal.fire({
                title:"Are you Sure?",
                text:"Once submitted, it cannot be changed.",
                icon:"warning",
                showCancelButton:true,
                confirmButtonColor:"#3085d6",
                cancelButtonColor:"#d33",
                confirmButtonText:"Yes, Submit it!"
            }).then((result)=>{
                // console.log(result.isConfirmed);
                if(result.isConfirmed){
                    let student = prepareData();
                    // console.log(student);
                    $.ajaxSetup({                   
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                        }
                    });

                    $.ajax({
                    type: "POST",
                    url: "{{ route('attendance.store') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "attendances": student,
                        "teacherSubjectGroup": "{{ $groupSubjectTeacherId }}"
                    },
                    success: function(data){
                        Toast.fire({
                                    icon: 'success',
                                    title: data.msg
                                });
                        setTimeout(() => {
                            window.location.replace("{{ route('attendance.create',$groupSubjectTeacherId )}}");
                        }, 3000);
                        submit.prop('disabled',true);
                    },
                    error: function(){
                        // console.log("error");
                        Toast.fire({
                                icon: 'error',
                                title: "Sorry Attendance Could not be Submitted. Please Try Again."
                            });
                        },
                    });
                }else if(result.isConfirmed === false){
                    Swal.fire({
                        icon:"info",
                        title:"Cancelled",
                        text:"Attendance Submission Cancelled"
                    });
                }
            })
        });

        // Add More Attendance
        let addAttendance =  document.getElementById('attendance_add');
        addAttendance.addEventListener("click", function(event){
            event.preventDefault();
            count++; 
            if( count <= maxCount){
                console.log('ere');
                // console.log('sdfsd',$('.table_title').find('th:secondlast').prev().attr('colspan',count));
                $('.table_title').find('th:nth-last-child(2)').prev().attr('colspan', count);
                $('table tr').each(function() {
                    let id =  $(this).find('td:nth-last-child(2)').prev().children(".attendance-state").first().attr('id');
                    $($(this).find('td:nth-last-child(2)').prev().clone()).insertBefore($(this).find('td:nth-last-child(2)'));
                    $(this).find('td:nth-last-child(2)').prev().children(".attendance-state").first().attr('id', id +'_'+count);
                });
            }else{
                count--;
                Toast.fire({
                                icon: 'error',
                                title: 'Cannot add more than maximum class allowed per day'
                            })
            }

        });

        // Remove Attendance
        let attendanceRemove = document.getElementById('attendance_remove');
        attendanceRemove.addEventListener("click", function(event){
            event.preventDefault();
            count--;
            if( count >=1){
                $('.table_title').find('th:nth-last-child(2)').prev().attr('colspan', count);
                $('table tr').each(function() {
                    $(this).find('td:nth-last-child(2)').prev().remove();
                });
            }
            else{
                count++;
                Toast.fire({
                                icon: 'error',
                                title: 'At least one attendance should be taken.'
                            })
            }
        })

        // Prepare Data for Taking Attendance
        function prepareData()
        {
            var student = new Array();
            $('table tr').each(function() {
                var studentAttendanceState = {'present': 0, 'absent': 0, 'leave':0};
                let rollNo = $(this).find('td.roll_no').text();
                if(rollNo != ""){

                    let attendanceStates = $(this).find('td.student_attendance_status').each(function(){
                        let attendanceState = $(this).children(".attendance-state").attr("data-attendance-state");
                        
                        if(attendanceState == 1)
                        {
                            studentAttendanceState.present++;
                        }
                        else if(attendanceState == 2)
                        {
                            studentAttendanceState.leave++;
                        }
                        else if(attendanceState == 0)
                        {
                            studentAttendanceState.absent++;
                        }
                    });
                    student.push({'rollNo': rollNo,'attendanceStatus':studentAttendanceState});
                }
                
            });

            return student;
        }
    </script>
@endsection