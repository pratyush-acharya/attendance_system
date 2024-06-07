<!-- Navbar start -->
<header class="header" id="header">
    <div class="header_toggle" onclick=" onNavToggleClick(event)"><i id="header-toggle" class="bx bx-menu"></i> </div>
    {{-- <div class="header_img"> <img src="https://osdeibi.dev/assets/img/faces/logoround.png" alt=""> </div> --}}
</header>
<div class="l-navbar" id="nav-bar">
    <nav class="leftnav">
        <div class="nav_list"> 

            <a href="/home" class="nav_link">
                <i class='bx bxs-dashboard bx-sm'></i> <span class="nav_name">Dashboard</span>
            </a>

            @if(Auth::user()->hasRole('superadmin'))
            <a href="{{route('attendance.list')}}" class="nav_link">
                <i class='bx bx-spreadsheet bx-sm' aria-hidden="true"></i>
                <span class="nav_name">Attendance</span>
            </a>
            @endif

            
            {{-- <a href="{{route('attendance-permission.list')}}" class="nav_link">
                 <i class='bx bx-shield-alt-2 bx-sm' aria-hidden="true"></i>
                <span class="nav_name">Permission</span>
            </a> --}}

            <a href="{{route('user.list')}}" class="nav_link">
                <i class='bx bx-file bx-sm' aria-hidden="true" ></i>
                <span class="nav_name">Forms</span>
            </a>

             <a href="{{ route('feedback.list') }}" class="nav_link">
                 <i class='bx bx-comment-dots bx-sm' aria-hidden="true"></i>
                <span class="nav_name">Feedback</span>
            </a>
            
            <a href="{{ route('report.list')}}" class="nav_link">
                <i class='bx bx-bar-chart-alt-2 bx-sm'></i> <span class="nav_name">Report</span>
            </a>

            <a href="{{route('change-password')}}" class="nav_link">
                <i class="bx bx-key bx-sm" aria-hidden="true"></i>
                <span class="nav_name">Change Password</span>
            </a>

            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="btn nav_link "><i class='bx bx-log-out bx-sm'></i><span class="nav_name">Logout</span></button>
            </form> 

        </div>
    </nav>
</div>
<!-- Navbar end -->