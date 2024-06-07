<table class="_table mx-auto">
    <tr class="table_title">
        <th class="border-end">Roll</th>
        <th class="border-end">Name</th>
        <th colspan="{{ $total_classes_of_day}}" class="text-center border-end">Status</th>
    </tr>
    <tr >
        <th colspan="2" class="border-end"></th>
        <th class="border-end text-center" colspan="{{ $total_classes_of_day}}"> {{date('M/d')}}</th>
        <th colspan="1"></th>
    </tr>
    @foreach ($attendances as $attendance)
    <tr>
        <td class="border-end roll_no">{{ $attendance->student->roll_no }}</td>
        <td class="border-end">{{ $attendance->student->name }}</td>

        @for($i=1; $i <= $total_classes_of_day ; $i++)
            @if($attendance->present > 0)
                @for($p = $attendance->present; $p > 0 ; $p--)
                    <td class="border-end student_attendance_status">
                        <div onclick="toggleState(this)" class="attendance-state" id="attendance_{{$attendance->student->roll_no}}" data-attendance-state= "1">
                            <img class="attendance_img" src="{{ asset('assets/images/P.svg')}}" id="r_{{$attendance->student->roll_no}}">
                        </div>
                    </td>
                @endfor
                @php
                    $attendance->present = 0;
                @endphp
            @elseif($attendance->leave > 0)
                @for($l = $attendance->leave; $l > 0 ; $l--)
                    <td class="border-end student_attendance_status">
                        <div onclick="toggleState(this)" class="attendance-state" id="attendance_{{$attendance->student->roll_no}}" data-attendance-state= "2">
                            <img class="attendance_img" src="{{ asset('assets/images/L.svg')}}" id="r_{{$attendance->student->roll_no}}">
                        </div>
                    </td>
                @endfor
                @php
                    $attendance->leave = 0;
                @endphp
            @elseif($attendance->absent > 0)
                @for($a = $attendance->absent; $a > 0; $a--)
                    <td class="border-end student_attendance_status">
                        <div onclick="toggleState(this)" class="attendance-state" id="attendance_{{$attendance->student->roll_no}}" data-attendance-state= "0">
                            <img class="attendance_img" src="{{ asset('assets/images/A.svg')}}" id="r_{{$attendance->student->roll_no}}">
                        </div>
                    </td>
                @endfor
                @php
                    $attendance->absent = 0;
                @endphp
            @endif

        @endfor
    </tr>
    @endforeach
</table>
