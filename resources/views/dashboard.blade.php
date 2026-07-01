@extends('layouts.master')

@section('title', 'Dashboard - School Management System')
@section('header-title', 'Dashboard')

@section('content')

<div class="row">
    <!-- Welcome Banner -->
    <div class="col-12">
        <div class="welcome-banner d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h2 class="m-0">Welcome, {{ auth()->user()->name }}!</h2>
                <p class="text-white-50 m-0 mt-1" style="font-size: 1.1rem;">
                    Yahan se aap system ke tamam modules ko manage kar sakte hain.
                </p>
            </div>
            <div>
                <span class="badge bg-primary px-3 py-2 fs-6">
                    <i class="bi bi-clock me-1"></i> {{ today()->format('l, d M Y') }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-4 mb-4">
    <!-- Students -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card card-primary">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="stat-label">Total Students</span>
                    <h3 class="stat-value">{{ $dashboardStats['students'] ?? 0 }}</h3>
                </div>
                <div class="stat-icon bg-primary-light">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Teachers -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card card-success">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="stat-label">Total Teachers</span>
                    <h3 class="stat-value">{{ $dashboardStats['teachers'] ?? 0 }}</h3>
                </div>
                <div class="stat-icon bg-success-light">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card card-info">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="stat-label">Total Courses</span>
                    <h3 class="stat-value">{{ $dashboardStats['courses'] ?? 0 }}</h3>
                </div>
                <div class="stat-icon bg-info-light">
                    <i class="bi bi-book-half"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Fee -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card card-warning">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="stat-label">Pending Fee</span>
                    <h3 class="stat-value">Rs {{ number_format($dashboardStats['pending_fees'] ?? 0, 0) }}</h3>
                </div>
                <div class="stat-icon bg-warning-light">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Visitors Today -->
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="stat-card card-secondary">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="stat-label">Visitors Today</span>
                    <h3 class="stat-value">{{ $dashboardStats['visitors_today'] ?? 0 }}</h3>
                </div>
                <div class="stat-icon bg-secondary-light">
                    <i class="bi bi-chat-square-dots-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Present Today -->
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="stat-card card-purple">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="stat-label">Present Today</span>
                    <h3 class="stat-value">{{ $dashboardStats['attendance_today'] ?? 0 }}</h3>
                </div>
                <div class="stat-icon bg-purple-light">
                    <i class="bi bi-calendar2-check-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Unread Notifications -->
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="stat-card card-danger">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <span class="stat-label">Unread Notifications</span>
                    <h3 class="stat-value">{{ $dashboardStats['notifications_unread'] ?? 0 }}</h3>
                </div>
                <div class="stat-icon bg-danger-light">
                    <i class="bi bi-bell-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modules Grid Header -->
<div class="row mb-3">
    <div class="col-12">
        <h4 class="fw-bold text-dark">System Modules</h4>
        <p class="text-muted" style="font-size: 0.9rem;">Select a module to view its workspace and perform actions.</p>
    </div>
</div>

<!-- Modules Grid -->
<div class="row g-4 mb-4">
    @foreach ($modules as $key => $title)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            @if(Route::has($key . '.index'))
                <a href="{{ route($key . '.index') }}" class="module-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon me-3 mb-0
                            @switch($key)
                                @case('admissions') bg-primary-light @break
                                @case('students') bg-primary-light @break
                                @case('employees') bg-success-light @break
                                @case('courses') bg-info-light @break
                                @case('fees') bg-warning-light @break
                                @case('visitors') bg-secondary-light @break
                                @case('attendance') bg-purple-light @break
                                @case('exams') bg-primary-light @break
                                @case('notifications') bg-danger-light @break
                                @case('reports') bg-info-light @break
                                @case('library') bg-warning-light @break
                                @case('roles') bg-primary-light @break
                                @default bg-primary-light
                            @endswitch">
                            @switch($key)
                                @case('admissions') <i class="bi bi-person-plus"></i> @break
                                @case('students') <i class="bi bi-people"></i> @break
                                @case('employees') <i class="bi bi-person-badge"></i> @break
                                @case('courses') <i class="bi bi-book"></i> @break
                                @case('fees') <i class="bi bi-cash-stack"></i> @break
                                @case('visitors') <i class="bi bi-chat-left-text"></i> @break
                                @case('attendance') <i class="bi bi-calendar-check"></i> @break
                                @case('exams') <i class="bi bi-clipboard-data"></i> @break
                                @case('notifications') <i class="bi bi-bell"></i> @break
                                @case('reports') <i class="bi bi-file-earmark-bar-graph"></i> @break
                                @case('library') <i class="bi bi-book-half"></i> @break
                                @case('roles') <i class="bi bi-shield-lock"></i> @break
                                @default <i class="bi bi-grid"></i>
                            @endswitch
                        </div>
                        <h4 class="m-0">{{ $title }}</h4>
                    </div>
                    <p>Open module workspace to manage records.</p>
                </a>
            @else
                <div class="module-card disabled">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon me-3 mb-0 bg-secondary-light">
                            <i class="bi bi-grid"></i>
                        </div>
                        <h4 class="m-0 text-muted">{{ $title }}</h4>
                    </div>
                    <p class="text-muted">Coming soon...</p>
                </div>
            @endif
        </div>
    @endforeach
</div>

<!-- Info Alert/Next Step -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body py-3 px-4 d-flex align-items-center gap-3">
                <i class="bi bi-info-circle-fill text-primary fs-4"></i>
                <div class="text-secondary" style="font-size: 0.9rem;">
                    <strong>Agla step:</strong> har module ke andar CRUD forms/controllers add kar ke full working system banaya ja sakta hai.
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
