{{-- <table class="_table mx-auto mb-5">
    <thead>
        <tr class="table_title">
            <th></th>
            @forelse ($subjects as $subject)
                <th colspan="4" class="text-center border-end">
                    {{ $subject->name }}
                    <br>
                    {{ $subject->getTeacher() }}
                    <br>
                    ({{ $subject->getClassDays() }})
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
                <td class="border-end">Present</td>
                <td class="border-end">Absent</td>
                <td class="border-end">Leave</td>
                <td class="border-end">Present Percentage</td>
            @empty
                <td colspan="4"></td>
            @endforelse
        </tr>
        @foreach ($students as $student)
            <tr>
                <td class="border-end">{{ $student->name }}</td>
                @forelse ($subjects as $subject)
                    <td class="border-end">
                        {{ $subject->getTotalPresentDays($student, $startDate ?? null, $endDate ?? null) }}</td>
                    <td class="border-end">
                        {{ $subject->getTotalAbsentDays($student, $startDate ?? null, $endDate ?? null) }}</td>
                    <td class="border-end">
                        {{ $subject->getTotalLeaveDays($student, $startDate ?? null, $endDate ?? null) }}</td>
                    <td class="border-end">
                        {{ $subject->getPresentPercentageByTeacher($student, $startDate ?? null, $endDate ?? null) }}
                    </td>
                @empty
                    <td colspan="3" class="border-end"></td>
                @endforelse
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td class="border-end" colspan="3"> Total Classes</td>
            @forelse ($subjects as $subject)
                @forelse($subject->getTeachers() as $j=>$name)
                    <td class="border-end">
                        @foreach ($subject->groups as $group)
                            Section {{ $group->name }}:
                            {{ $subject->getTeachersTotalClasses($group, $startDate ?? null, $endDate ?? null)[$j] }}
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
</table> --}}

<table class="_table mx-auto mb-5">
    <thead>
    <tr class="table_title">
        <th colspan="3"></th>
        @forelse ($subjects as $subject)
            <th colspan="{{ count($subject->getTeachers()) * 4}}" class="text-center border-start border-bottom">
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
               <th colspan="4" class="text-center border-start">
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
            <td class="border-end">Present</td>
            <td class="border-end">Absent</td>
            <td class="border-end">Leave</td>  
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
                        $present = $subject->getStudentAttendanceCountPerTeacher($student, $startDate ?? null, $endDate ?? null, $status='present');
                        $leave = $subject->getStudentAttendanceCountPerTeacher($student, $startDate ?? null, $endDate ?? null, $status='leave');
                        $absent = $subject->getStudentAttendanceCountPerTeacher($student, $startDate ?? null, $endDate ?? null, $status='absent');
                        $presentPercentage = $subject->getPresentPercentageByTeacher($student, $startDate ?? null, $endDate ?? null);
                    @endphp
                    @foreach($subject->getTeachers() as $name)
                        <td>
                            {{ $present[$loop->iteration -1] == '-' ? '-' : $present[$loop->iteration - 1] }}
                        </td>
                        <td>
                            {{ $absent[$loop->iteration -1] == '-' ? '-' : $absent[$loop->iteration - 1] }}
                        </td>
                        <td>
                            {{ $leave[$loop->iteration -1] == '-' ? '-' : $leave[$loop->iteration - 1] }}
                        </td>
                        <td> 
                            {{ $presentPercentage[$loop->iteration - 1] == '-' ? '-' : ($presentPercentage[$loop->iteration - 1].'%')}} 
                        </td>
                    @endforeach                        
                @else
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
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
                <td colspan="4" class="border-end">
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
