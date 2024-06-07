<table class="_table mx-auto mb-5">
    <thead>
        <tr class="table_title">
            <th></th>
            @forelse ($subjects as $subject)
                <th colspan="4" class="text-center border-end">
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
                <td  class="border-end">{{$student->name}}</td>
                @forelse ($subjects as $subject)
                    <td  class="border-end"> {{ $subject->getTotalPresentDays($student, $startDate ?? null, $endDate ?? null) }}</td>
                    <td  class="border-end"> {{ $subject->getTotalAbsentDays($student, $startDate ?? null, $endDate ?? null)  }}</td>
                    <td  class="border-end"> {{ $subject->getTotalLeaveDays($student, $startDate ?? null, $endDate ?? null)   }}</td>
                    <td  class="border-end"> {{ $subject->getPresentPercentage($student, $startDate ?? null, $endDate ?? null)}}</td>
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
                <td colspan="4" class="border-end">
                    @foreach ( $subject->groups as $group) 
                        Section {{$group->name}}: {{$subject->getTotalClasses($group ,$startDate ?? null, $endDate ?? null)}}
                    @endforeach
                </td>
            @empty
                <td colspan="4" class="border-end">

                </td>
            @endforelse
        </tr>
    </tfoot>
</table>