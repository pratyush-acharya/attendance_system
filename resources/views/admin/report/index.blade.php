@extends('layouts.admin.app')

@section('title','Attendance Report')

@section('content')
    <div class="below_header">
        <h1>Attendance Report</h1>

    </div>
    <form action="{{ route("report.search")}}">

        <div class="row">
            <div class="col-md-6 mt-5">
                <div class="align-items-center">
                    <label for="batch" class=" col-md-4 form-label">Batch</label>

                    <select id="batch" name="batch" class="col-md-4 form-control form-select  form-select-sm "
                            onchange="evaluateAttendanceFilter()">
                        <option disabled selected>--Choose Batch--</option>
                        @foreach ($batches as $batch)
                            <option value="{{$batch->id}}"> {{$batch->name }} - {{$batch->stream->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6 mt-5">
                <div class="align-items-center">
                    <label for="student" class=" col-md-4 form-label">Student</label>

                    <select id="student" name="student" class="col-md-4 form-control form-select  form-select-sm ">
                        <option disabled selected>--Choose Student--</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6 my-5 ">
                <div class=" align-items-center">
                    <label for="subject" class=" col-md-4 form-label">Subject</label>

                    <select id="subject" name="subject" class="col-md-4 form-control form-select  form-select-sm ">
                        <option disabled selected>--Choose Subject--</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3 my-5">
                <div class="row align-items-center">
                    <label for="start_date" class="col form-label"> Start Date</label>
                    <input id="start_date" name="start_date" type="date" class="col form-control form-control-sm"
                           onchange="evaluateDate()">
                </div>
            </div>
            <div class="col-md-3 my-5">
                <div class="row align-items-center">
                    <label for="end_date" class="col-md-4 form-label"> End Date</label>
                    <input id="end_date" name="end_date" type="date" class="col-md-4 form-control form-control-sm"
                           onchange="evaluateDate()">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7">
                <div class="input-group input-group-sm mb-3">
                    <label for="below_50" class="col-md-4 form-label">Attendance Present Percentage</label>
                    <input type="text" class="form-control" name="attendanceFilter" id="attendanceFilter"
                           placeholder="Enter Present Percentage" onchange="evaluateAttendanceFilter()">
                    <span class="input-group-text" id="basic-addon2">%</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <label for="Attendance Subject Filter All">Apply Attendance Percentage Filter For All Subject</label>
                <input type="checkbox" name="attendanceFilterForAllSubject" id="attendanceFilterForAllSubject" value="1"
                       onchange="applyAttendanceFilter(this.value)">
            </div>
        </div>
        <div class="row w-100">
            <div class="offset-md-6 col-md-6 mb-5 pe-5 d-flex justify-content-end">
                <button class="btn btn-primary px-3 py-2 me-5" id="search_submit" type="submit">Search</button>
                <a href="#" class="btn btn-success px-3 py-2" onclick="downloadReport()">
                    <i class="fas fa-file-download me-2"></i>Download
                </a>
            </div>
        </div>
    </form>
    <form action="{{ route('report.download')}}" method="POST" id="reportDownloadForm">
        @csrf
        <input type="hidden" id="batchDownload" name="batch">
        <input type="hidden" id="studentDownload" name="student">
        <input type="hidden" id="subjectDownload" name="subject">
        <input type="hidden" id="startDateDownload" name="start_date">
        <input type="hidden" id="endDateDownload" name="end_date">
    </form>
    <table class="_table mx-auto mb-5">
        <thead>
        <tr class="table_title">
            <th colspan="3"></th>
            @forelse ($subjects as $subject)
                <th colspan="{{ count($subject->getTeachers())}} * 2" class="text-center border-start border-bottom">
                    {{ $subject->name }}
                </th>
            @empty
                <td colspan="100%" class="text-center">
                    <h5>
                        No Subject Assigned to this batch
                    </h5>
                </td>
            @endforelse
        </tr>
        <tr class="table_title">
            <th colspan="3"></th>
            @forelse ($subjects as $subject)
                @foreach ($subject->getTeachers() as $name)
                   <th colspan="" class="text-center border-start">
                        {{ $name }}
                   </th>
                @endforeach
            @empty
            @endforelse
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="border-end" colspan="3">Students' Name</td>
            @forelse ($subjects as $subject)
                @foreach ($subject->getTeachers() as $name)
                {{-- <td class="border-end">Present</td> --}}
                {{-- <td class="border-end">Absent</td> --}}
                {{-- <td class="border-end">Leave</td>   --}}
                    <td class="border-end">Present Percentage</td>
                @endforeach
            @empty
                <td colspan="{{ count($subject->getTeachers()) }} * 2"></td>
            @endforelse
        </tr>
        @forelse ($students as $student)
            <tr>
                <td class="border-end" colspan="3">{{$student->name}}</td>
                @forelse ($subjects as $subject)
                    @if($student->batch->start_date < date('Y-m-d'))
                        @php
                            $presentPercentage = $subject->getPresentPercentageByTeacher($student, $startDate ?? null, $endDate ?? null);
                            $leavePercentage = $subject->getLeavePercentageByTeacher($student, $startDate ?? null, $endDate ?? null);
                        @endphp
                        @foreach($subject->getTeachers() as $name)
                            <td colspan="" class="border-end {{
                                    $presentPercentage[$loop->iteration - 1] == '-' ?'-': (($presentPercentage[$loop->iteration - 1] < 85 )? 'bg-danger':(($leavePercentage[$loop->iteration - 1]>20) ? 'bg-warning': ''))
                                }}"> {{ $presentPercentage[$loop->iteration - 1] == '-' ? '-' : ($presentPercentage[$loop->iteration - 1].'%')}} </td>
                        @endforeach                        
                    @else
                        <td>-</td>
                    @endif
                @empty
                    <td colspan="{{ count($subject->getTeachers()) }} * 2" class="border-end"></td>
                @endforelse
            </tr>
        @empty
            <tr>
                <td colspan="{{$subjects->count()*2}}" class="text-center row">
                    <p id="attendanceValue"></p>
                </td>
            </tr>
        @endforelse
        </tbody>
        <tfoot>
        <tr>
            <td class="border-end" colspan="3"> Total Classes</td>
            @forelse ($subjects as $subject)
                @forelse($subject->getTeachers() as $j=>$name)
                    <td class="border-end">
                        @foreach ( $subject->groups as $group)
                            Section {{$group->name}}: {{$subject->getTeachersTotalClasses($group ,$startDate ?? null, $endDate ?? null)[$j]}}
                            <br>
                        @endforeach
                    </td>
                @empty
                    <td colspan="{{ count($subject->name) }}"></td>
                @endforelse
            @empty
                <td colspan="4" class="border-end">

                </td>
            @endforelse
        </tr>
        </tfoot>
    </table>

@endsection
@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type='text/javascript'>
        $(document).ready(function () {
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
            var attendanceFilter = getUrlParameter('attendanceFilter');
            var attendanceFilterForAllSubject = getUrlParameter('attendanceFilterForAllSubject');
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

            if (batch) {
                $('#batch').val(batch).trigger('change');
                batchChange(batch).then(() => {
                    if (student) {
                        $('#student').val(student).trigger('change');
                    }

                    if (subject) {
                        $('#subject').val(subject).trigger('change');
                    }

                    if (startDate) {
                        $('#start_date').val(startDate);
                    }
                    if (endDate) {
                        $('#end_date').val(endDate);
                    }

                    if (attendanceFilter) {
                        $('#attendanceFilter').val(attendanceFilter);
                        $('#attendanceValue').text('No student has attendance below ' + attendanceFilter + '%.');
                    }

                    if (attendanceFilterForAllSubject) {
                        $('#attendanceFilterForAllSubject').prop("checked", true);
                    }
                });
            }
        });

        $('#batch').change(function () {
            //first run ajax
            batchChange($(this).val());
            //then set the hidden value
            $('#batchDownload').val($(this).val());
        });

        $('#student').change(function () {
            //set the hidden value
            $('#studentDownload').val($(this).val());
        });

        $('#subject').change(function () {
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

        async function fetchBatch(batchValue) {
            let result;
            try {
                result = await $.ajax({
                    url: "{{ route('report.batchSearch') }}",
                    data: {
                        batch: batchValue
                    },
                    datatype: 'json',
                });
            } catch (error) {
                console.log(error);
            }
            return result;
        }

        async function batchChange(batchValue) {
            $('#subject').empty();
            $('#student').empty();
            var $subject = $('#subject');
            var $student = $('#student');

            await fetchBatch(batchValue)
                .then((data) => {
                    if (jQuery.isEmptyObject(data.subjects)) {
                        $subject.html('<option selected disabled>---No Subject assigned to the Batch---</option>')
                    } else {
                        $subject.html('<option selected disabled>---Choose Subject---</option>');
                        $.each(data.subjects, function (id, value) {
                            $subject.append('<option value="' + id + '">' + value + '</option>');
                        });
                    }

                    if (jQuery.isEmptyObject(data.students)) {
                        $student.html('<option selected disabled>---No Student assigned to the Batch---</option>')

                    } else {
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

        function evaluateDate() {
            let endDateInputVal = endDateInput.value;
            let startDateInputVal = startDateInput.value;
            //set the hiddent input values
            document.getElementById('startDateDownload').value = startDateInputVal;
            document.getElementById('endDateDownload').value = endDateInputVal;

            if (endDateInputVal < startDateInputVal && endDateInputVal) {
                Toast.fire({
                    icon: 'error',
                    title: 'End date should be later than start date'
                })

                submitBtn.disabled = true;
            } else {
                submitBtn.disabled = false;
            }
        }

        function evaluateAttendanceFilter() {
            let attendanceFilter = document.getElementById('attendanceFilter');
            let batch = document.getElementById('batch');
            attendanceFilterVal = attendanceFilter.value;
            batchVal = batch.selectedIndex;
            console.log(attendanceFilterVal);
            if (batchVal <= 0 && attendanceFilterVal) {
                Toast.fire({
                    icon: 'error',
                    title: 'Please select a batch to filter students.'
                });

                submitBtn.disabled = true;
            } else {
                submitBtn.disabled = false;
            }
        }

        function downloadReport() {
            $('#reportDownloadForm').submit();
        }
    </script>
@endsection
