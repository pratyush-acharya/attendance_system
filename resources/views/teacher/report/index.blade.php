@extends('layouts.teacher.app')

@section('title','Attendance Report')

@if ($batches)
    @section('content')
        <div class="below_header">
            <h1>Attendance Report</h1>

        </div>
        <form action="{{ route("report.teacherSearch")}}">
            
        <div class="row">
            <div class="col-md-6 mt-5">
                <div class="align-items-center">
                    <label for="batch" class=" col-md-4 form-label" >Batch</label>
                    
                    <select id="batch" name="batch" class="col-md-4 form-control form-select  form-select-sm " >
                    <option  disabled selected>--Choose Batch--</option>
                    @foreach ($batches as $batch)
                        <option value="{{$batch->id}}"> {{$batch->name }} - {{$batch->stream->name}}</option>
                    @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6 mt-5">
                <div class="align-items-center">
                    <label for="student" class=" col-md-4 form-label" >Student</label>
                    
                    <select id="student" name="student" class="col-md-4 form-control form-select  form-select-sm " >
                    <option  disabled selected>--Choose Student--</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6 my-5 ">
                <div class=" align-items-center">
                    <label for="subject" class=" col-md-4 form-label" >Subject</label>
                    
                    <select id="subject" name="subject" class="col-md-4 form-control form-select  form-select-sm " >
                    <option  disabled selected>--Choose Subject--</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3 my-5">
                <div class="row align-items-center">
                    <label for="start_date" class="col-md-4 form-label"> Start Date</label>
                    <input id="start_date" name="start_date" type="date" class="col-md-4 form-control" onchange="evaluateDate()">
                </div>
            </div>
            <div class="col-md-3 my-5">
                <div class="row align-items-center">
                    <label for="end_date" class="col-md-4 form-label"> End Date</label>
                    <input id="end_date" name="end_date" type="date" class="col-md-4 form-control" onchange="evaluateDate()">
                </div>
            </div>
        </div>
        <div class="offset-md-6 col-md-6 mb-5 pe-5 d-flex justify-content-end">
            <button class="btn btn-primary px-3 py-2" id="search_submit">Search</button>
        </div>
        </form>
        <form action="{{ route('report.teacherDownload')}}" method="POST">
            @csrf
            <input type="hidden" id="batchDownload" name="batch">
            <input type="hidden" id="studentDownload" name="student">
            <input type="hidden" id="subjectDownload" name="subject">
            <input type="hidden" id="startDateDownload" name="start_date">
            <input type="hidden" id="endDateDownload" name="end_date">
            <div class="offset-md-6 col-md-6 mb-5 pe-5 d-flex justify-content-end">
                <button class="btn btn-success px-3 py-2" id="download_submit">
                    <i class="fas fa-file-download me-2"></i>Download
                </button>
            </div>
        </form>
        <table class="_table mx-auto mb-5">
            <thead>
                <tr class="table_title">
                    <th></th>
                    @forelse ($subjects as $subject)
                        <th colspan="2" class="text-center border-end">
                            {{ $subject->name }}
                            <br>
                            {{ $subject->getTeacher()}}
                            <br>
                            ({{ $subject->getClassDays()}})
                        </th>
                    @empty
                        <td colspan="3" class="text-center"> 
                            <h5>
                                No Subject Assigned to this batch
                            </h5>
                        </td>
                                
                    @endforelse
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border-end">Students' Name</td>
                    @forelse ($subjects as $subject)
                            {{-- <td class="border-end">Present</td> --}}
                            {{-- <td class="border-end">Absent</td> --}}
                            {{-- <td class="border-end">Leave</td>   --}}
                            <td colspan="2" class="border-end">Present Percentage</td>
                    @empty
                        <td colspan="4"></td>                  
                    @endforelse
                </tr>
                @foreach ($students as $student)
                    <tr>
                        <td  class="border-end">{{$student->name}}</td>
                        @forelse ($subjects as $subject)
                            {{-- <td  class="border-end"> {{ $subject->getTotalPresentDays($student, $startDate ?? null, $endDate ?? null) }}</td> --}}
                            {{-- <td  class="border-end"> {{ $subject->getTotalAbsentDays($student, $startDate ?? null, $endDate ?? null)  }}</td> --}}
                            {{-- <td  class="border-end"> {{ $subject->getTotalLeaveDays($student, $startDate ?? null, $endDate ?? null)   }}</td> --}}
                            @php
                                $presentPercentage = $subject->getPresentPercentage($student, $startDate ?? null, $endDate ?? null);
                                $leavePercentage = $subject->getLeavePercentage($student, $startDate ?? null, $endDate ?? null);
                            @endphp
                        <td  colspan="2" class="border-end {{
                            $presentPercentage == '-' ?'-': (($presentPercentage < 85 )? 'bg-danger':(($leavePercentage>20) ? 'bg-warning': ''))
                        }}"> {{ $presentPercentage == '-' ? '-' : (min($presentPercentage,100).'%')}}</td>    
                        @empty
                            <td colspan="3"  class="border-end"></td>
                        @endforelse
                    </tr>
                    
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="border-end"> Total Classes</td>
                    @forelse ($subjects as $subject)
                    <td colspan="2" class="border-end">
                        @foreach ( $subject->groups as $group) 
                            Section {{$group->name}}: {{$subject->getTotalClasses($group ,$startDate ?? null, $endDate ?? null)}}
                        @endforeach
                    </td>
                    @empty
                        <td colspan="2" class="border-end">

                        </td>
                    @endforelse
                </tr>
            </tfoot>
        </table>

    @endsection
@else
    @section("content")
            <h5 class="text-center">
                <div class="shadow p-3 mb-5 bg-body rounded">
                    No Batch Assigned to Teacher
                </div>
            </h5>
    @endsection
@endif
@section('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type='text/javascript'>
    $(document).ready( function(){
        $('#subject').select2();
        $('#batch').select2();
        $('#student').select2();

    });
</script>
{{-- Script To Update subject,student and dates on change --}}
<script>
    $(document).ready(function () {
        //get the parameters from the browser url
        var batch = getUrlParameter('batch');
        var student = getUrlParameter('student');
        var subject = getUrlParameter('subject');
        var startDate = getUrlParameter('start_date');
        var endDate = getUrlParameter('end_date');
        //set the end and start date max to today
        let endDateInput = document.getElementById('end_date');
        let startDateInput = document.getElementById('start_date');
        endDateInput.max = new Date().toISOString().split("T")[0];
        startDateInput.max = new Date().toISOString().split("T")[0];
        //set the hidden inputs of download form
        document.getElementById('batchDownload').value = batch;
        document.getElementById('studentDownload').value = student;
        document.getElementById('subjectDownload').value = subject;
        document.getElementById('startDateDownload').value = startDate;
        document.getElementById('endDateDownload').value = endDate;

        if(batch)
        {
            $('#batch').val(batch).trigger('change');
            batchChange(batch).then(()=>{
                if(student)
                {
                    $('#student').val(student).trigger('change');  
                }

                if(subject)
                {
                    $('#subject').val(subject).trigger('change');  
                }

                if(startDate)
                {
                    $('#start_date').val(startDate);
                }
                if(endDate)
                {
                    $('#end_date').val(endDate);
                }
            });            
        }
    });

    $('#batch').change(function(){
        //first run ajax
        batchChange($(this).val());
        //then set the hidden value
        $('#batchDownload').val($(this).val());
    });

    $('#student').change(function(){
        //set the hidden value
        $('#studentDownload').val($(this).val());
    });

    $('#subject').change(function(){
        //set the hiddent value
        $('#subjectDownload').val($(this).val());
    })

    function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                }
            }
            return false;        
    };

    async function fetchBatch(batchValue){
            let result;
            try{
                result = await $.ajax({
                    url: "{{ route('report.teacherBatchSearch') }}",
                    data: {
                        batch: batchValue
                    },
                    datatype: 'json',
                 });
            }
            catch(error){
                console.log(error);
            }
            return result;
        }
        async function batchChange(batchValue)
        {
            $('#subject').empty();
            $('#student').empty();
            var $subject = $('#subject');
            var $student = $('#student');

            await fetchBatch(batchValue)
            .then((data)=>{
                if(jQuery.isEmptyObject(data.subjects)){
                        $subject.html('<option selected disabled>---No Subject assigned to the Batch---</option>')
                    }else{
                        $subject.html('<option selected disabled>---Choose Subject---</option>');
                        $.each(data.subjects, function (id, value) {
                            $subject.append('<option value="' + id + '">' + value + '</option>');
                        });
                    }

                    if(jQuery.isEmptyObject(data.students)){
                        $student.html('<option selected disabled>---No Student assigned to the Batch---</option>')

                    }else{
                        $student.html('<option selected disabled>---Choose Student---</option>');
                        $.each(data.students, function (id, value) {
                            $student.append('<option value="' + id + '">' + value + '</option>');
                        });
                    }
                    $('#start_date').attr({"min": data.start_date});

            });

            $('#subject').val("");
            $('#student').val("");
        }
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
    let endDateInput = document.getElementById('end_date');
    let startDateInput = document.getElementById('start_date');
    let submitBtn = document.getElementById('search_submit');

    function evaluateDate()
    {
        let endDateInputVal = endDateInput.value;
        let startDateInputVal = startDateInput.value;
        //set the hiddent input values
        document.getElementById('startDateDownload').value = startDateInputVal;
        document.getElementById('endDateDownload').value = endDateInputVal;

        if(endDateInputVal < startDateInputVal && endDateInputVal){
            Toast.fire({
                    icon: 'error',
                    title: 'End date should be later than start date'
                })
            
            submitBtn.disabled = true;
        }else{
            submitBtn.disabled = false;  
        }
    }
</script>
@endsection