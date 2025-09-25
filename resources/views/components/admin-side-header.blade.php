<style>
/* Remove underline from sidebar links */
#sidebar a {
  text-decoration: none !important;
}

/* Keep the text color consistent */
#sidebar a span {
  color: inherit;
}
</style>

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        {{-- Dashboard (all roles) --}}
        <li class="nav-item">
            <a class="nav-link collapsed" href="" data-bs-target="#dashboard-nav" data-bs-toggle="collapse">
                <i class="bi bi-menu-button-wide"></i><span>Dashboard</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="dashboard-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ url('admin/dashboard') }}">
                        <i class="bi bi-circle"></i><span>Dashboard</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Attendance (Mark Attendance accessible to all) --}}
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ url('admin/attendance') }}" data-bs-target="#attendance-nav" data-bs-toggle="collapse">
                <i class="bi bi-journal-text"></i><span>Attendance</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="attendance-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                {{-- All users can mark attendance --}}
                <li>
                    <a href="{{ url('admin/attendance') }}">
                        <i class="bi bi-circle"></i><span>Mark Attendance</span>
                    </a>
                </li>

                {{-- Only ADMIN & SUPER can see Attendance History & Schedule --}}
                @if(auth()->user()->role == 'SUPER' || auth()->user()->role == 'ADMIN')
                    <li>
                        <a href="{{ url('admin/attendance/history') }}">
                            <i class="bi bi-circle"></i><span>Attendance History</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('admin/time') }}">
                            <i class="bi bi-circle"></i><span>Schedule</span>
                        </a>
                    </li>
                @endif
            </ul>
        </li>

        {{-- Students (all roles) --}}
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ url('admin/students') }}" data-bs-target="#students-nav" data-bs-toggle="collapse">
                <i class="bi bi-journal-text"></i><span>Students</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="students-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ url('admin/students') }}">
                        <i class="bi bi-circle"></i><span>Students</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Only ADMIN & SUPER can see Admins page --}}
        @if(auth()->user()->role == 'SUPER' || auth()->user()->role == 'ADMIN')
            <li class="nav-heading">Pages</li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ url('admin/admins') }}">
                    <i class="bi bi-person"></i>
                    <span>Admins</span>
                </a>
            </li>
        @endif

    </ul>

</aside><!-- End Sidebar -->
