<div class="w-100 pt-3">
    <div class="wrapper-nav">
        <nav class="nav nav-tabs list mt-2 d-flex justify-content-between" id="myTab" role="tablist">
        <!-- <nav class="nav nav-tabs list mt-2" id="myTab" role="tablist"> -->
            <a class="nav-item nav-link nav_item below_header_btn" href="{{route('user.list')}}" role="tab"
                aria-controls="public" aria-expanded="true">Users</a>
            <!-- <a class="nav-item nav-link nav_item below_header_btn active" data-toggle="tab" href="#tab1"
                            role="tab" aria-controls="public" aria-expanded="true">Tab1</a> -->
            <a class="nav-item nav-link nav_item below_header_btn" href="{{route('stream.list')}}" role="tab">Streams</a>
            <a class="nav-item nav-link nav_item below_header_btn" href="{{route('batch.list')}}" role="tab">Batch</a>
            <a class="nav-item nav-link nav_item below_header_btn" href="{{route('student.list')}}" role="tab">Students</a>
            <a class="nav-item nav-link nav_item below_header_btn" href="{{route('subject.list')}}" role="tab">Subject</a>
            <a class="nav-item nav-link nav_item below_header_btn" href="{{route('group.list')}}" role="tab">Section</a>
            <a class="nav-item nav-link nav_item below_header_btn" href="{{route('subject_group_assign.list')}}" role="tab">Section Subject</a>
            <a class="nav-item nav-link nav_item below_header_btn" href="{{route('student-group-assign.list')}}" role="tab">Section Student</a>
            <a class="nav-item nav-link nav_item below_header_btn" href="{{route('teacher_subject_group_assign.list')}}" role="tab">Teacher Section Subject</a>
            <a class="nav-item nav-link nav_item below_header_btn" href="{{route('holiday.list')}}" role="tab">Holiday</a>
            <a class="nav-item nav-link nav_item below_header_btn" href="{{route('feedbackEmail.list')}}" role="tab">Feedback Email</a>
            
        </nav>
    </div>
</div>
