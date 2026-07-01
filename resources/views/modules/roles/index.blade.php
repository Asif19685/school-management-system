@extends('layouts.master')

@section('title', 'Roles & Permissions - School Management System')
@section('header-title', 'Roles & Permissions')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Roles & Permissions</li>
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
                <div class="stat-icon me-3 mb-0 bg-primary-light" style="width: 54px; height: 54px; font-size: 1.5rem;">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <div>
                    <h3 class="fw-bold m-0 text-dark">Roles & Permissions</h3>
                    <p class="text-muted m-0 mt-1" style="font-size: 0.9rem;">
                        Direct file path: <code>resources/views/modules/roles/index.blade.php</code>
                    </p>
                </div>
            </div>

            <hr class="my-4 text-muted opacity-25">

            <div class="alert alert-info border-0 rounded-3 d-flex align-items-center gap-3">
                <i class="bi bi-code-square fs-4"></i>
                <div>
                    Aap is view file aur is ke controller class (<code>RolesController</code>) ke andar CRUD forms, tables, aur logic add kar ke Roles & Permissions system bana sakte hain.
                </div>
            </div>

            <h5 class="fw-bold text-secondary mb-3 mt-4">Module CRUD Actions</h5>
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <div class="card border-0 bg-light p-3 h-100 rounded-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-plus-circle-fill text-primary fs-4 me-2"></i>
                            <h6 class="fw-bold m-0 text-dark">Create Role</h6>
                        </div>
                        <p class="text-muted small mb-0">Naye system roles like Admin, Teacher, Operator define karein.</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card border-0 bg-light p-3 h-100 rounded-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-sliders text-success fs-4 me-2"></i>
                            <h6 class="fw-bold m-0 text-dark">Assign Permissions</h6>
                        </div>
                        <p class="text-muted small mb-0">Configure detailed rules, route filters, and checkboxes configurations.</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card border-0 bg-light p-3 h-100 rounded-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-file-earmark-bar-graph text-info fs-4 me-2"></i>
                            <h6 class="fw-bold m-0 text-dark">User Assignments</h6>
                        </div>
                        <p class="text-muted small mb-0">Change, check, or reset active user account privileges.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
