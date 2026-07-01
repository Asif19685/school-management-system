@extends('layouts.master')

@section('title', $moduleTitle . ' - School Management System')
@section('header-title', $moduleTitle)

@section('content')
<!-- Breadcrumbs / Action Header -->
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $moduleTitle }}</li>
            </nav>
        </nav>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm p-4 mb-4">
            <div class="d-flex align-items-center mb-3">
                <div class="stat-icon me-3 mb-0 
                    @switch($moduleKey)
                        @case('students') bg-primary-light @break
                        @case('teachers') bg-success-light @break
                        @case('courses') bg-info-light @break
                        @case('fees') bg-warning-light @break
                        @case('visitors') bg-secondary-light @break
                        @case('attendance') bg-purple-light @break
                        @case('notifications') bg-danger-light @break
                        @default bg-primary-light
                    @endswitch" style="width: 54px; height: 54px; font-size: 1.5rem;">
                    @switch($moduleKey)
                        @case('students') <i class="bi bi-people"></i> @break
                        @case('teachers') <i class="bi bi-person-badge"></i> @break
                        @case('courses') <i class="bi bi-book"></i> @break
                        @case('fees') <i class="bi bi-cash-stack"></i> @break
                        @case('visitors') <i class="bi bi-chat-left-text"></i> @break
                        @case('attendance') <i class="bi bi-calendar-check"></i> @break
                        @case('notifications') <i class="bi bi-bell"></i> @break
                        @default <i class="bi bi-grid"></i>
                    @endswitch
                </div>
                <div>
                    <h3 class="fw-bold m-0 text-dark">{{ $moduleTitle }} Module</h3>
                    <p class="text-muted m-0 mt-1" style="font-size: 0.9rem;">
                        Scaffold ready. Implement CRUD forms, controllers, and reports here.
                    </p>
                </div>
            </div>

            <hr class="my-4 text-muted opacity-25">

            <!-- Actions grid -->
            <h5 class="fw-bold text-secondary mb-3">Quick Actions</h5>
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <div class="card border-0 bg-light p-3 h-100 rounded-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-plus-circle-fill text-primary fs-4 me-2"></i>
                            <h6 class="fw-bold m-0 text-dark">Create</h6>
                        </div>
                        <p class="text-muted small mb-0">Nayi entries add karein yahan se.</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card border-0 bg-light p-3 h-100 rounded-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-sliders text-success fs-4 me-2"></i>
                            <h6 class="fw-bold m-0 text-dark">Manage</h6>
                        </div>
                        <p class="text-muted small mb-0">Records list aur update karein yahan se.</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card border-0 bg-light p-3 h-100 rounded-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-file-earmark-bar-graph text-info fs-4 me-2"></i>
                            <h6 class="fw-bold m-0 text-dark">Reports</h6>
                        </div>
                        <p class="text-muted small mb-0">Module-wise analytics export karein.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
