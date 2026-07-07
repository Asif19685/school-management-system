<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'School Management System'))</title>

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">





    @yield('styles')
</head>
<body>

    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">
                <i class="bi bi-mortarboard-fill me-2"></i> SMS Admin
            </div>

            <div class="sidebar-content">
                <ul class="list-group list-group-flush my-3 px-2" style="list-style: none; padding-left: 0;">
                    <li>
                        <a href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>

                    <li>
                        <div class="text-uppercase px-4 py-2 text-xs text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">Modules</div>
                    </li>

                  <!-- Admissions Module -->
<li>
    <a href="#collapse-admissions"
       data-bs-toggle="collapse"
       role="button"
       aria-expanded="{{ request()->is('student-admissions*') ? 'true' : 'false' }}"
       aria-controls="collapse-admissions"
       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->is('student-admissions*') ? 'active' : '' }}">
        <span>
            <i class="bi bi-person-plus"></i> Admissions
        </span>
        <i class="bi bi-chevron-down toggle-icon ms-2 small"></i>
    </a>
    <div class="collapse {{ request()->is('student-admissions*') ? 'show' : '' }}" id="collapse-admissions">
        <ul class="list-group submenu-list" style="list-style: none; padding-left: 0;">
            <li>
                <a href="#"
                   data-bs-toggle="modal" data-bs-target="#createAdmissionModal"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0">
                    <i class="bi bi-plus-circle-fill me-2" style="font-size: 0.8rem;"></i> Create
                </a>
            </li>
            <li>
                <a href="{{ route('admissions.index') }}"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0 {{ request()->routeIs('admissions.index') ? 'active fw-bold' : '' }}">
                    <i class="bi bi-sliders me-2" style="font-size: 0.8rem;"></i> Admission List
                </a>
            </li>
            <li>
                <a href="{{ route('admissions.index') }}?view=reports"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0 {{ request()->get('view') === 'reports' ? 'active fw-bold' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph me-2" style="font-size: 0.8rem;"></i> Reports
                </a>
            </li>
        </ul>
    </div>
</li>

<!-- Students Module -->
<li>
    <a href="#collapse-students"
       data-bs-toggle="collapse"
       role="button"
       aria-expanded="{{ request()->is('students*') ? 'true' : 'false' }}"
       aria-controls="collapse-students"
       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->is('students*') ? 'active' : '' }}">
        <span>
            <i class="bi bi-people"></i> Students
        </span>
        <i class="bi bi-chevron-down toggle-icon ms-2 small"></i>
    </a>
    <div class="collapse {{ request()->is('students*') ? 'show' : '' }}" id="collapse-students">
        <ul class="list-group submenu-list" style="list-style: none; padding-left: 0;">
            <li>
                <a href="#"
                   data-bs-toggle="modal" data-bs-target="#createStudentModal"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0">
                    <i class="bi bi-plus-circle-fill me-2" style="font-size: 0.8rem;"></i> Create
                </a>
            </li>
            <li>
                <a href="{{ route('students.index') }}"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0 {{ request()->routeIs('students.index') ? 'active fw-bold' : '' }}">
                    <i class="bi bi-sliders me-2" style="font-size: 0.8rem;"></i> Student List
                </a>
            </li>
            <li>
                <a href="{{ route('students.index') }}?view=reports"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0 {{ request()->get('view') === 'reports' ? 'active fw-bold' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph me-2" style="font-size: 0.8rem;"></i> Reports
                </a>
            </li>
        </ul>
    </div>
</li>


<!-- Teachers Module -->
<li>
    <a href="#collapse-teachers"
       data-bs-toggle="collapse"
       role="button"
       aria-expanded="{{ request()->is('teachers*') || request()->is('salaries*') ? 'true' : 'false' }}"
       aria-controls="collapse-teachers"
       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->is('teachers*') || request()->is('salaries*') ? 'active' : '' }}">
        <span>
            <i class="bi bi-person-badge"></i> Teachers
        </span>
        <i class="bi bi-chevron-down toggle-icon ms-2 small"></i>
    </a>
    <div class="collapse {{ request()->is('teachers*') || request()->is('salaries*') ? 'show' : '' }}" id="collapse-teachers">
        <ul class="list-group submenu-list" style="list-style: none; padding-left: 0;">
            <li>
                <a href="{{ route('teachers.create') }}"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0 {{ request()->routeIs('teachers.create') ? 'active fw-bold' : '' }}">
                    <i class="bi bi-plus-circle-fill me-2" style="font-size: 0.8rem;"></i> Create
                </a>
            </li>
            <li>
                <a href="{{ route('teachers.index') }}"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0 {{ request()->routeIs('teachers.index') ? 'active fw-bold' : '' }}">
                    <i class="bi bi-sliders me-2" style="font-size: 0.8rem;"></i> Teacher List
                </a>
            </li>
            <li>
                <a href="{{ route('salaries.index') }}"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0 {{ request()->routeIs('salaries.index') ? 'active fw-bold' : '' }}">
                    <i class="bi bi-cash me-2" style="font-size: 0.8rem;"></i> Salaries
                </a>
            </li>
        </ul>
    </div>
</li>

<!-- Courses Module -->
<li>
    <a href="#collapse-courses"
       data-bs-toggle="collapse"
       role="button"
       aria-expanded="{{ request()->is('courses*') ? 'true' : 'false' }}"
       aria-controls="collapse-courses"
       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->is('courses*') ? 'active' : '' }}">
        <span>
            <i class="bi bi-book"></i> Courses
        </span>
        <i class="bi bi-chevron-down toggle-icon ms-2 small"></i>
    </a>
    <div class="collapse {{ request()->is('courses*') ? 'show' : '' }}" id="collapse-courses">
        <ul class="list-group submenu-list" style="list-style: none; padding-left: 0;">
            <li>
                <a href="#"
                   data-bs-toggle="modal" data-bs-target="#createCourseModal"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0">
                    <i class="bi bi-plus-circle-fill me-2" style="font-size: 0.8rem;"></i> Create
                </a>
            </li>
            <li>
                <a href="{{ route('courses.index') }}"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0 {{ request()->routeIs('courses.index') ? 'active fw-bold' : '' }}">
                    <i class="bi bi-sliders me-2" style="font-size: 0.8rem;"></i> Course List
                </a>
            </li>
            <li>
                <a href="{{ route('courses.index') }}?view=reports"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0 {{ request()->get('view') === 'reports' ? 'active fw-bold' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph me-2" style="font-size: 0.8rem;"></i> Reports
                </a>
            </li>
        </ul>
    </div>
</li>

<!-- Fees Module -->
<li>
    <a href="#collapse-fees"
       data-bs-toggle="collapse"
       role="button"
       aria-expanded="{{ request()->is('fees*') ? 'true' : 'false' }}"
       aria-controls="collapse-fees"
       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->is('fees*') ? 'active' : '' }}">
        <span>
            <i class="bi bi-cash-stack"></i> Fees
        </span>
        <i class="bi bi-chevron-down toggle-icon ms-2 small"></i>
    </a>
    <div class="collapse {{ request()->is('fees*') ? 'show' : '' }}" id="collapse-fees">
        <ul class="list-group submenu-list" style="list-style: none; padding-left: 0;">
            <li>
                <a href="#"
                   data-bs-toggle="modal" data-bs-target="#createFeeModal"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0">
                    <i class="bi bi-plus-circle-fill me-2" style="font-size: 0.8rem;"></i> Create
                </a>
            </li>
            <li>
                <a href="{{ route('fees.index') }}"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0 {{ request()->routeIs('fees.index') ? 'active fw-bold' : '' }}">
                    <i class="bi bi-sliders me-2" style="font-size: 0.8rem;"></i> Fee List
                </a>
            </li>
            <li>
                <a href="{{ route('fees.index') }}?view=reports"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0 {{ request()->get('view') === 'reports' ? 'active fw-bold' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph me-2" style="font-size: 0.8rem;"></i> Reports
                </a>
            </li>
        </ul>
    </div>
</li>

<!-- Visitors Module -->
<li>
    <a href="#collapse-visitors"
       data-bs-toggle="collapse"
       role="button"
       aria-expanded="{{ request()->is('visitors*') ? 'true' : 'false' }}"
       aria-controls="collapse-visitors"
       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->is('visitors*') ? 'active' : '' }}">
        <span>
            <i class="bi bi-chat-left-text"></i> Visitors
        </span>
        <i class="bi bi-chevron-down toggle-icon ms-2 small"></i>
    </a>
    <div class="collapse {{ request()->is('visitors*') ? 'show' : '' }}" id="collapse-visitors">
        <ul class="list-group submenu-list" style="list-style: none; padding-left: 0;">
            <li>
                <a href="{{ route('visitors.create') }}"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0 {{ request()->routeIs('visitors.create') ? 'active fw-bold' : '' }}">
                    <i class="bi bi-plus-circle-fill me-2" style="font-size: 0.8rem;"></i> Create
                </a>
            </li>
            <li>
                <a href="{{ route('visitors.index') }}"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0 {{ request()->routeIs('visitors.index') ? 'active fw-bold' : '' }}">
                    <i class="bi bi-sliders me-2" style="font-size: 0.8rem;"></i> Visitor List
                </a>
            </li>
            <li>
                <a href="{{ route('visitors.index') }}?view=reports"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0 {{ request()->get('view') === 'reports' ? 'active fw-bold' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph me-2" style="font-size: 0.8rem;"></i> Reports
                </a>
            </li>
        </ul>
    </div>
</li>

<!-- Attendance Module -->
<li>
    <a href="#collapse-attendance"
       data-bs-toggle="collapse"
       role="button"
       aria-expanded="{{ request()->is('attendance*') || request()->is('teacher-attendance*') ? 'true' : 'false' }}"
       aria-controls="collapse-attendance"
       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->is('attendance*') || request()->is('teacher-attendance*') ? 'active' : '' }}">
        <span>
            <i class="bi bi-calendar-check"></i> Attendance
        </span>
        <i class="bi bi-chevron-down toggle-icon ms-2 small"></i>
    </a>
    <div class="collapse {{ request()->is('attendance*') || request()->is('teacher-attendance*') ? 'show' : '' }}" id="collapse-attendance">
        <ul class="list-group submenu-list" style="list-style: none; padding-left: 0;">
            <li>
                <a href="{{ route('attendance.index') }}"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0 {{ request()->routeIs('attendance.index') ? 'active fw-bold' : '' }}">
                    <i class="bi bi-person me-2" style="font-size: 0.8rem;"></i> Student Attendance
                </a>
            </li>
            <li>
                <a href="{{ route('teacher-attendance.index') }}"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0 {{ request()->routeIs('teacher-attendance.index') ? 'active fw-bold' : '' }}">
                    <i class="bi bi-person-badge me-2" style="font-size: 0.8rem;"></i> Teacher Attendance
                </a>
            </li>
        </ul>
    </div>
</li>

<!-- Exams Module -->
<li>
    <a href="#collapse-exams"
       data-bs-toggle="collapse"
       role="button"
       aria-expanded="{{ request()->is('exams*') ? 'true' : 'false' }}"
       aria-controls="collapse-exams"
       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->is('exams*') ? 'active' : '' }}">
        <span>
            <i class="bi bi-clipboard-data"></i> Exams
        </span>
        <i class="bi bi-chevron-down toggle-icon ms-2 small"></i>
    </a>
    <div class="collapse {{ request()->is('exams*') ? 'show' : '' }}" id="collapse-exams">
        <ul class="list-group submenu-list" style="list-style: none; padding-left: 0;">
            <li>
                <a href="#"
                   data-bs-toggle="modal" data-bs-target="#createExamModal"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0">
                    <i class="bi bi-plus-circle-fill me-2" style="font-size: 0.8rem;"></i> Create
                </a>
            </li>
            <li>
                <a href="{{ route('exams.index') }}"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0 {{ request()->routeIs('exams.index') ? 'active fw-bold' : '' }}">
                    <i class="bi bi-sliders me-2" style="font-size: 0.8rem;"></i> Exam List
                </a>
            </li>
            <li>
                <a href="{{ route('exams.index') }}?view=reports"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0 {{ request()->get('view') === 'reports' ? 'active fw-bold' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph me-2" style="font-size: 0.8rem;"></i> Reports
                </a>
            </li>
        </ul>
    </div>
</li>

<!-- Notifications Module -->
<li>
    <a href="#collapse-notifications"
       data-bs-toggle="collapse"
       role="button"
       aria-expanded="{{ request()->is('notifications*') ? 'true' : 'false' }}"
       aria-controls="collapse-notifications"
       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->is('notifications*') ? 'active' : '' }}">
        <span>
            <i class="bi bi-bell"></i> Notifications
        </span>
        <i class="bi bi-chevron-down toggle-icon ms-2 small"></i>
    </a>
    <div class="collapse {{ request()->is('notifications*') ? 'show' : '' }}" id="collapse-notifications">
        <ul class="list-group submenu-list" style="list-style: none; padding-left: 0;">
            <li>
                <a href="#"
                   data-bs-toggle="modal" data-bs-target="#createNotificationModal"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0">
                    <i class="bi bi-plus-circle-fill me-2" style="font-size: 0.8rem;"></i> Create
                </a>
            </li>
            <li>
                <a href="{{ route('notifications.index') }}"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0 {{ request()->routeIs('notifications.index') ? 'active fw-bold' : '' }}">
                    <i class="bi bi-sliders me-2" style="font-size: 0.8rem;"></i> Notification List
                </a>
            </li>
            <li>
                <a href="{{ route('notifications.index') }}?view=reports"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0 {{ request()->get('view') === 'reports' ? 'active fw-bold' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph me-2" style="font-size: 0.8rem;"></i> Reports
                </a>
            </li>
        </ul>
    </div>
</li>

<!-- Library Module -->
<li>
    <a href="#collapse-library"
       data-bs-toggle="collapse"
       role="button"
       aria-expanded="{{ request()->is('library*') ? 'true' : 'false' }}"
       aria-controls="collapse-library"
       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->is('library*') ? 'active' : '' }}">
        <span>
            <i class="bi bi-book-half"></i> Library
        </span>
        <i class="bi bi-chevron-down toggle-icon ms-2 small"></i>
    </a>
    <div class="collapse {{ request()->is('library*') ? 'show' : '' }}" id="collapse-library">
        <ul class="list-group submenu-list" style="list-style: none; padding-left: 0;">
            <li>
                <a href="#"
                   data-bs-toggle="modal" data-bs-target="#createBookModal"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0">
                    <i class="bi bi-plus-circle-fill me-2" style="font-size: 0.8rem;"></i> Add Book
                </a>
            </li>
            <li>
                <a href="{{ route('library.index') }}"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0 {{ request()->routeIs('library.index') ? 'active fw-bold' : '' }}">
                    <i class="bi bi-sliders me-2" style="font-size: 0.8rem;"></i> Book List
                </a>
            </li>
            <li>
                <a href="{{ route('library.index') }}?view=reports"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0 {{ request()->get('view') === 'reports' ? 'active fw-bold' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph me-2" style="font-size: 0.8rem;"></i> Reports
                </a>
            </li>
        </ul>
    </div>
</li>

<!-- Roles Module -->
<li>
    <a href="#collapse-roles"
       data-bs-toggle="collapse"
       role="button"
       aria-expanded="{{ request()->is('roles*') ? 'true' : 'false' }}"
       aria-controls="collapse-roles"
       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->is('roles*') ? 'active' : '' }}">
        <span>
            <i class="bi bi-shield-lock"></i> Roles
        </span>
        <i class="bi bi-chevron-down toggle-icon ms-2 small"></i>
    </a>
    <div class="collapse {{ request()->is('roles*') ? 'show' : '' }}" id="collapse-roles">
        <ul class="list-group submenu-list" style="list-style: none; padding-left: 0;">
            <li>
                <a href="#"
                   data-bs-toggle="modal" data-bs-target="#createRoleModal"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0">
                    <i class="bi bi-plus-circle-fill me-2" style="font-size: 0.8rem;"></i> Create Role
                </a>
            </li>
            <li>
                <a href="{{ route('roles.index') }}"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0 {{ request()->routeIs('roles.index') ? 'active fw-bold' : '' }}">
                    <i class="bi bi-sliders me-2" style="font-size: 0.8rem;"></i> Roles List
                </a>
            </li>
            <li>
                <a href="{{ route('roles.index') }}?view=reports"
                   class="list-group-item list-group-item-action d-flex align-items-center py-2 border-0 {{ request()->get('view') === 'reports' ? 'active fw-bold' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph me-2" style="font-size: 0.8rem;"></i> Reports
                </a>
            </li>
        </ul>
    </div>
</li>
                </ul>
            </div>

            <div class="sidebar-footer">
                <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action text-white-50 border-0 p-2 d-inline-block w-auto me-3" title="Profile">
                    <i class="bi bi-person-circle fs-5"></i>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-white-50 p-2 border-0 align-baseline" title="Log Out">
                        <i class="bi bi-box-arrow-right fs-5"></i>
                    </button>
                </form>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <button class="btn btn-outline-secondary border-0 btn-sm me-3" id="menu-toggle">
                        <i class="bi bi-justify fs-4"></i>
                    </button>

                    <h5 class="m-0 fw-bold d-none d-sm-block text-secondary">
                        @yield('header-title', 'School Management System')
                    </h5>

                    <div class="ms-auto d-flex align-items-center">
                        <!-- Notifications (Optional Icon) -->
                        <div class="position-relative me-4">
                            <i class="bi bi-bell fs-5 text-secondary"></i>
                            {{-- @if(isset($dashboardStats['notifications_unread']) && $dashboardStats['notifications_unread'] > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                    {{ $dashboardStats['notifications_unread'] }}
                                </span>
                            @endif --}}
                        </div>

                        <!-- User Profile Dropdown -->
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center fw-semibold text-dark" href="#" role="button" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=4f46e5&color=fff" alt="User Avatar" class="rounded-circle me-2" style="width: 32px; height: 32px;">
                                <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="dropdownUser">
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person me-2 text-muted"></i> My Profile
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Log Out
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap 5 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/main.js') }}"></script>


    @stack('scripts')
</body>
</html>
