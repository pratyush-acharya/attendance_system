{{-- {{dd($mainGroups->first()->filterSubject())}} --}}
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div>
        <p>Hello All,</p>
        <p>Please find the details of today's attendance of Class of {{ $batch->name }} - {{ $batch->stream->name }} (
            Semester {{ $batch->semester }}).</p>
        <p> {{ date('M d , Y') }} </p>
        @if (!empty($mainGroups))
            @foreach ($mainGroups as $mainGroup)
                <table border="1">
                    <thead>
                        <tr style="text-align:center; background-color:#337DBF; ">
                            <td colspan="{{ count($mainGroup->filterSubject()) + 1 }}"
                                style="text-align:center; color:#fff; font-weight: bold;" >
                                Section {{ $mainGroup->name ?? 'N/A' }}
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align:center;color:#11436B"><b>Course/ <br> Course Teacher</b></td>
                            @foreach ($mainGroup->filterSubject() as $groupSubjectTeacher)
                                <td style=" text-align: center;color:#11436B">
                                    <b >{{ ucfirst($groupSubjectTeacher->groupSubject->subject_id) }}
                                        <br>
                                        ({{ ucfirst($groupSubjectTeacher->user->name) }})
                                    </b> <br>
                                    <span style="text-align:center;color:#11436B; font-size:9pt;">
                                        ({{ $groupSubjectTeacher->days }})
                                    </span>
                                </td>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Get Present Students Number --}}
                        <tr>
                            <td class="present-no"  style=" text-align:center; color:#11436B; font-weight: bold; font-size:8.5pt;"> Present No. </td>
                            @foreach ($mainGroup->filterSubject() as $groupSubjectTeacher)
                                @if ($groupSubjectTeacher->checkAttendance($batch) == 0)
                                    <td class="number" style=" text-align:center; color:#11436B; font-size:7.5pt; font-weight: bold;" >0</td>
                                @else
                                    <td class="number" style=" text-align:center; color:#11436B; font-size:7.5pt; font-weight: bold;">
                                        {{ $groupSubjectTeacher->getPresentCount($batch) }}
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                        {{-- Get Absent Count --}}
                        <tr>
                            <td class="absent-no" style=" text-align:center;color:#11436B; font-weight:bold; font-size:8.5pt;"> Absent No. </td>
                            @foreach ($mainGroup->filterSubject() as $groupSubjectTeacher)
                                @if ($groupSubjectTeacher->checkAttendance($batch) == 0)
                                    <td class="number" style=" text-align:center;color:#11436B; font-size:7.5pt; font-weight: bold;">0</td>
                                @else
                                    <td class="number" style=" text-align:center;color:#11436B; font-size:7.5pt; font-weight: bold;">
                                        {{ $groupSubjectTeacher->getAbsentCount($batch) }}
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                        {{-- Get Leave Count --}}
                        <tr>
                            <td class="leave-no" style=" text-align:center;color:#11436B; font-weight: bold; font-size:8.5pt;" > Leave No. </td>
                            @foreach ($mainGroup->filterSubject() as $groupSubjectTeacher)
                                @if ($groupSubjectTeacher->checkAttendance($batch) == 0)
                                    <td class="number" style=" text-align:center;color:#11436B; font-size:7.5pt; font-weight: bold;">0</td>
                                @else
                                    <td class="number" style=" text-align:center;color:#11436B; font-size:7.5pt; font-weight: bold;">
                                        {{ $groupSubjectTeacher->getLeaveCount($batch) }}
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                        {{-- Get Absentees --}}
                        <tr>
                            <td class="absentees" style=" text-align:center;color:#11436B; font-weight: bold; font-size:8.5pt;"> Absentees </td>
                            @foreach ($mainGroup->filterSubject() as $groupSubjectTeacher)
                                @if ($groupSubjectTeacher->checkAttendance($batch) == 0)
                                    <td class="number" style=" text-align:center;color:#11436B; font-size:7.5pt; ">Attendance not Taken Today</td>
                                @else
                                    <td class="number" style=" text-align:center;color:#11436B; font-size:7.5pt; ">
                                        @forelse ($groupSubjectTeacher->getAbsentees($batch) as $student)
                                            {{ $student }} <br>
                                        @empty
                                            No Absentees Today
                                        @endforelse
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                        {{-- No Absentees --}}
                        <tr>
                            <td class="on-leave" style=" text-align:center;color:#11436B; font-weight: bold; font-size:8.5pt;" > On Leave </td>
                            @foreach ($mainGroup->filterSubject() as $groupSubjectTeacher)
                                @if ($groupSubjectTeacher->checkAttendance($batch) == 0)
                                    <td class="number" style=" text-align:center;color:#11436B; font-size:7.5pt;">Attendance not Taken Today</td>
                                @else
                                    <td class="number" style=" text-align:center;color:#11436B; font-size:7.5pt;">
                                        @forelse ($groupSubjectTeacher->getOnLeaves($batch) as $student)
                                            {{ $student }} <br>
                                        @empty
                                            No one is On Leave Today
                                        @endforelse
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    </tbody>
                </table>
                <br>
                <br>
                <br>
            @endforeach
        @endif
        @if (!empty($electiveGroups))
            @foreach ($electiveGroups as $electiveGroup)
                <table border="1">
                    <thead>
                        <tr style="text-align: center; background-color:#337DBF">
                            <td colspan="{{ count($electiveGroup->filterSubject()) + 1 }}"
                                style="text-align:center; color:#fff">

                                Section {{ $electiveGroup->name ?? 'N/A' }}
                            </td>
                        </tr>
                        <tr>
                            <td ><b style="color:#11436B" text-align:center>Course/ <br> Course Teacher</b></td>
                            @foreach ($electiveGroup->filterSubject() as $groupSubjectTeacher)
                                <td>
                                    <b style="color:#11436B">{{ ucfirst($groupSubjectTeacher->groupSubject->subject_id) }}
                                        <br>
                                        ({{ ucfirst($groupSubjectTeacher->user->name) }})
                                    </b> <br>
                                    <span style="color:#11436B; font-size:4.5pt; text-align:center">
                                        ({{ $groupSubjectTeacher->days }})
                                    </span>
                                </td>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Get Present Students Number --}}
                        <tr>
                            <td class="present-no" style=" text-align:center;color:#11436B; font-weight: bold; font-size:8.5pt;"> Present No. </td>
                            @foreach ($electiveGroup->filterSubject() as $groupSubjectTeacher)
                                @if ($groupSubjectTeacher->checkAttendance($batch) == 0)
                                    <td style=" text-align:center;color:#11436B; font-size:7.5pt;" class="number">0</td>
                                @else
                                    <td style=" text-align:center;color:#11436B; font-size:7.5pt;" class="number">
                                        {{ $groupSubjectTeacher->getPresentCount($batch) }}
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                        {{-- Get Absent Count --}}
                        <tr>
                            <td class="absent-no" style=" text-align:center;color:#11436B; font-weight: bold; font-size:8.5pt;"> Absent No. </td>
                            @foreach ($electiveGroup->filterSubject() as $groupSubjectTeacher)
                                @if ($groupSubjectTeacher->checkAttendance($batch) == 0)
                                    <td style=" text-align:center;color:#11436B; font-size:7.5pt;" class="number">0</td>
                                @else
                                    <td style=" text-align:center;color:#11436B; font-size:7.5pt;" class="number">
                                        {{ $groupSubjectTeacher->getAbsentCount($batch) }}
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                        {{-- Get Leave Count --}}
                        <tr>
                            <td class="leave-no" style=" text-align:center;color:#11436B; font-weight: bold; font-size:8.5pt;"> Leave No. </td>
                            @foreach ($electiveGroup->filterSubject() as $groupSubjectTeacher)
                                @if ($groupSubjectTeacher->checkAttendance($batch) == 0)
                                    <td  style="text-align:center;color:#11436B; font-size:7.5pt;" class="number">0</td>
                                @else
                                    <td style="text-align:center;color:#11436B; font-size:7.5pt;" class="number">
                                        {{ $groupSubjectTeacher->getLeaveCount($batch) }}
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                        {{-- Get Absentees --}}
                        <tr>
                            <td class="absentees" style=" text-align:center;color:#11436B; font-weight: bold; font-size:8.5pt;"> Absentees </td>
                            @foreach ($electiveGroup->filterSubject() as $groupSubjectTeacher)
                                @if ($groupSubjectTeacher->checkAttendance($batch) == 0)
                                    <td  style=" text-align:center;color:#11436B; font-size:7.5pt;" class="number">Attendance not Taken Today</td>
                                @else
                                    <td  style=" text-align:center;color:#11436B; font-size:7.5pt;" class="number">
                                        @forelse ($groupSubjectTeacher->getAbsentees($batch) as $student)
                                            {{ $student }} <br>
                                        @empty
                                            No Absentees Today
                                        @endforelse
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                        {{-- No Absentees --}}
                        <tr>
                            <td class="on-leave" style=" text-align:center;color:#11436B; font-weight: bold; font-size:8.5pt;">
                                On Leave </td>
                            @foreach ($electiveGroup->filterSubject() as $groupSubjectTeacher)
                                @if ($groupSubjectTeacher->checkAttendance($batch) == 0)
                                    <td class="number" style=" text-align:center;color:#11436B; font-size:7.5pt;" >Attendance not Taken Today</td>
                                @else
                                    <td class="number" style=" text-align:center;color:#11436B; font-size:7.5pt;" >
                                        @forelse ($groupSubjectTeacher->getOnLeaves($batch) as $student)
                                            {{ $student }} <br>
                                        @empty
                                            No one is On Leave Today
                                        @endforelse
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    </tbody>
                </table>
                <br>
                <br>
                <br>
            @endforeach
        @endif

        <p>Regards,</p>
        <br>
        <div id="signature">
            --<br>
            <span style="color:#0b5394;"><b>AMS SYSTEM</b></span><br>
            Deerwalk Institute of Technology<br>
            Sifal, Kathmandu<br>
            Nepal<br>
            <a href="deerwalk.edu.np">deerwalk.edu.np</a>
            <br>
            <p style="color:888888; font-family: ui-monospace;">
                DISCLAIMER:<br>
                This is an automatically generated email - please do not reply to it. If you have any queries please
                contact
                Admistration.
            </p>
        </div>
    </div>
</body>

</html>
