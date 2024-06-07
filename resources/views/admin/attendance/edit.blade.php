@extends('layouts.admin.app')

@section('title','Edit Attendance Page')

@section('content')
<div class="below_header">
    <h1>Daily Attendance :{{ $subject->code }} - {{ $subject->name }} | 
        {{ $subject->groups->first()->batch->name}} {{ $subject->groups->first()->batch->stream->name}}
         - Sec {{ $subject->groups->first()->name }}</h1>
</div>

<div class="table_container mt-5">
    <form method="POST" action="{{ route('attendance.update',$groupSubjectTeacherId) }}">
        @csrf
        @method('PUT')
        @include('admin.attendance._form')
        <div class="row justify-content-center">
            <div class="justify-content-center text-end my-3 me-5">
                <button class="btn btn-primary my-2 me-5" id="attendance_submit">Submit</button>
            </div>
        </div>
    </form>
    
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

        let submit = document.getElementById("attendance_submit");

        submit.addEventListener("click", function(event){
            event.preventDefault();
            let student = prepareData();
            console.log(student);
            $.ajax({
                type: "PUT",
                url: "{{ route('attendance.update') }}",
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
                        window.location.replace("{{route('attendance.list')}}");
                    }, 3000);
                    submit.prop('disabled',true);
                },

                error: function(data){
                    Toast.fire({
                                icon: 'error',
                                title: data.msg
                            })
                }
            });
        });


        let addAttendance =  document.getElementById('attendance_add');

        addAttendance.addEventListener("click", function(event){
            event.preventDefault();
            count++; 
            if( count <= maxCount){
                $('.table_title').find('th:last').prev().attr('colspan', count);
                $('table tr').each(function() {
                    let id =  $(this).find('td:last').prev().children(".attendance-state").first().attr('id');
                    $($(this).find('td:last').prev().clone()).insertBefore($(this).find('td:last'));
                    $(this).find('td:last').prev().children(".attendance-state").first().attr('id', id +'_'+count);
                });
            }else{
                count--;
                Toast.fire({
                                icon: 'error',
                                title: 'Cannot add more than maximum class allowed per day'
                            })
            }

        });

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