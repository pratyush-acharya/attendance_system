@extends('layouts.student.index')
@section('content')

@include('student.midsection')
        <!-- Pie Chart -->
        <div class="col-3 shadow-sm py-2 pie_chart">
            <div class="row align-items-center">
                <div class="col-md-9 p-0">
                    <canvas id="my_piechart" class="p-0" height="300"></canvas>
                </div>
            </div>
            <br>
            <div class="row align-items-center">
                <div class="col-4">
                    <select class="form-select form-select-sm" id="select_piechart_month">
                        @for($i=1;$i<=12;$i++)
                        <option id="piechart_month" value='{{ $i }}'
                             {{ $i == date('m') ? 'selected' : '' }}
                        >{{DateTime::createFromFormat('!m', $i)->format('F');}}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
<form action="{{ route("student.attendance-search")}}">

    <div class="row">

        <div class="col-md-6 my-5 ">
            <div class=" align-items-center">
                <label for="subject" class=" col-md-4 form-label" >Subject</label>

                <select id="subject" name="subject" class="col-md-4 form-control form-select  form-select-sm " >
                   <option  disabled selected>--Choose Subject--</option>
                   <option  value="all_subjects" {{ isset($requestedSubject) ?'selected':''}}>All Subjects</option>
                   @forelse ($allSubjects?? $subjects as $subject)
                       <option value="{{$subject->id}}"
                            {{ (!empty(old('subject')) && old('subject') == $subject->id) ? 'selected': ''}}
                            {{ (isset($requestedSubject) && $subject->id == $requestedSubject && empty(old('subject'))) ? 'selected' : '' }}
                       >{{$subject->code}} {{$subject->name}}</option>
                   @empty

                   @endforelse
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-5 pe-5 d-flex align-items-end">
        <button class="btn btn-primary px-3" id="search_submit">Search</button>
    </div>
</form>
<table class="_table mx-auto mb-5">
    <thead>
        <tr class="table_title">
            <th></th>
            @forelse ($subjects as $subject)
                <th class="text-center border-end" colspan="{{ count($subject->getTeachers()) }}">
                    {{ $subject->name }}
                </th>
            @empty
                <td class="text-center">
                    <h5>
                        No Subject Assigned to this batch
                    </h5>
                </td>
            @endforelse
        </tr>
        <tr>
            <th></th>
            @forelse ($subjects as $subject)
                @foreach ($subject->getTeachers() as $teacherName)
                    <th class="text-center border-end">
                        {{ $teacherName }}
                    </th>
                @endforeach
            @empty
                <td class="text-center">
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
                    {{-- <td class="border-end">Present</td>
                    <td class="border-end">Absent</td>
                    <td class="border-end">Leave</td>   --}}
                    <td colspan="{{ count($subject->getTeachers()) }}" class="border-end">Present Percentage</td>
            @empty
                <td colspan=""></td>
            @endforelse
        </tr>
        <tr>
            <td  class="border-end">{{$student->name}}</td>
            @forelse ($subjects as $subject)
                @if($student->batch->start_date < date('Y-m-d'))
                    @php
                    $presentPercentage = $subject->getPresentPercentageByTeacher($student, $startDate ?? null, $endDate ?? null);
                    $leavePercentage = $subject->getLeavePercentageByTeacher($student, $startDate ?? null, $endDate ?? null);
                    @endphp
                    @foreach($subject->getTeachers() as $name)
                        <td colspan="" class="border-end {{
                            $presentPercentage[$loop->iteration - 1] == '-' ?'-': (($presentPercentage[$loop->iteration - 1] < 85 )? 'bg-danger':(($leavePercentage[$loop->iteration - 1]>20) ? 'bg-warning': ''))
                        }}"> {{ $presentPercentage[$loop->iteration - 1] == '-' ? '-' : (min($presentPercentage[$loop->iteration - 1],100).'%')}} </td>
                    @endforeach
                @else
                    <td>-</td>
                @endif
            @empty
                <td class="border-end"></td>
            @endforelse
        </tr>

    </tbody>
    <tfoot>
        <tr>
            <td class="border-end"> Total Classes</td>
            @forelse ($subjects as $subject)
                @foreach ( $subject->groups as $group)
                    @foreach($subject->getTeachersTotalClasses($group ,$startDate ?? null, $endDate ?? null) as $classCount)
                    <td class="border-end">
                        Section {{$group->name}}: {{ $classCount }}
                    </td>
                    @endforeach
                @endforeach
            @empty
                <td colspan="" class="border-end"></td>
            @endforelse
        </tr>
    </tfoot>
</table>
@endsection
@section('scripts')
<script>

    var chart1;
    var chart2
    var  pieData = JSON.parse(`<?php echo $piechart_data; ?>`);

    $(document).ready(function() {
        piechart(pieData);       //pie-chart
    })


    //Pie Chart Creation
    function piechart(pieData){
        var ctx = $("#my_piechart");

        var data = {
        labels: pieData[1],
        datasets: [
            {
            label: "Users Count",
            data: pieData[0],
            backgroundColor: [
                "#2E8B57",
                "#DC143C",
                "#F4A460",
            ],
            borderColor: [
                "#2E8B57",
                "#DC143C",
                "#F4A460",
            ],
            borderWidth: [1, 1, 1]
            }
        ]
        };

        //options
        var options = {
        responsive: true,
        title: {
            display: true,
            position: "top",
            text: "Monthly Attendance",
            fontSize: 16,
            fontColor: "#111"
        },
        legend: {
            display: true,
            position: "bottom",
            labels: {
            fontColor: "#333",
            fontSize: 9
            }
        },
        };

        //create Pie Chart class object
        chart1 = new Chart(ctx, {
            type: "pie",
            data: data,
            options: options
        });
    }


    // Pie-Chart Filter
    var select_piechart_month = document.getElementById('select_piechart_month');
    var select_piechart_batch = document.getElementById('select_piechart_batch');
    select_piechart_month.addEventListener('change', function handleChange(event) {
        console.log(select_piechart_month.value,'month');
        event.preventDefault();
        // selected_piechart_month = event.target.value;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type:'POST',
            url: "{{ route('student.attendance-piechart') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "month": select_piechart_month.value,
                },
            // dataType:'json',
            success: function(data) {
                console.log(data);
                pieData = JSON.parse(data);
                if (chart1) chart1.destroy();
                piechart(pieData);
            },
            error: function (data) {
                console.log("error",data);
            }
        });
    });

    // Pie-Chart Filter
    var select_piechart_month = document.getElementById('select_piechart_month');
    select_piechart_month.addEventListener('change', function handleChange(event) {
        event.preventDefault();
        selected_piechart_month = event.target.value;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type:'POST',
            url: "{{ route('admin.dashboard.piechart') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "month": selected_piechart_month,
                },
            // dataType:'json',
            success: function(data) {
                pieData = JSON.parse(data);
                if (chart1) chart1.destroy();
                piechart(pieData);
            },
            error: function (data) {
                console.log("error",data);
            }
        });
    });

</script>
@endsection
