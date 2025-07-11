<!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
      <li class="nav-item">
        <a class="nav-link collapsed" href="" data-bs-target="#components-nav" data-bs-toggle="collapse">
          <i class="bi bi-menu-button-wide"></i><span>Dashboard</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
      <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ url('admin/dashboard') }}">
              <i class="bi bi-circle"></i><span>Dashboard</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ url('admin/attendance') }}" data-bs-target="#forms-nav" data-bs-toggle="collapse">
          <i class="bi bi-journal-text"></i><span>Attendance</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
      <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ url('admin/attendance') }}">
              <i class="bi bi-circle"></i><span>Attendance</span>
            </a>
          </li>
           <li>
            <a href="{{ url('admin/attendance/history') }}">
              <i class="bi bi-circle"></i><span>Attendance History</span>
            </a>
          </li>
           <li>
            <a href="{{ url('admin/time') }}">
              <i class="bi bi-circle"></i><span>Time</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ url('admin/students') }}" data-bs-target="#student-nav" data-bs-toggle="collapse">
          <i class="bi bi-journal-text"></i><span>Students</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
         <ul id="student-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ url('admin/students') }}">
              <i class="bi bi-circle"></i><span>Students</span>
            </a>
          </li>
        </ul>
      </li>

       <li class="nav-heading">Pages</li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ url('admin/admins') }}">
          <i class="bi bi-person"></i>
          <span>Admins</span>
        </a>
      </li>
    </ul>

  </aside><!-- End Sidebar-->
