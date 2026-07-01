@extends('layouts.master')

@section('title', 'Employee Management - School Management System')
@section('header-title', 'Employee Management')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Employee Management</li>
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
                <div class="stat-icon me-3 mb-0 bg-success-light" style="width: 54px; height: 54px; font-size: 1.5rem;">
                    <i class="bi bi-person-badge"></i>
                </div>
                <div>
                    <h3 class="fw-bold m-0 text-dark">Employee Management</h3>
                    <p class="text-muted m-0 mt-1" style="font-size: 0.9rem;">
                        Direct file path: <code>resources/views/modules/employees/index.blade.php</code>
                    </p>
                </div>
            </div>

            <hr class="my-4 text-muted opacity-25">

            <div class="alert alert-info border-0 rounded-3 d-flex align-items-center gap-3">
                <i class="bi bi-code-square fs-4"></i>
                <div>
                    Aap is view file aur is ke controller class (<code>EmployeesController</code>) ke andar CRUD forms, tables, aur logic add kar ke Employee Management system bana sakte hain.
                </div>
            </div>

            <h5 class="fw-bold text-secondary mb-3 mt-4">Module CRUD Actions</h5>
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <div class="card border-0 bg-light p-3 h-100 rounded-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-plus-circle-fill text-primary fs-4 me-2"></i>
                            <h6 class="fw-bold m-0 text-dark">Add Employee</h6>
                        </div>
                        <p class="text-muted small mb-0">Naye teachers/employees lists aur salary metadata fill karein.</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card border-0 bg-light p-3 h-100 rounded-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-sliders text-success fs-4 me-2"></i>
                            <h6 class="fw-bold m-0 text-dark">Manage Employees</h6>
                        </div>
                        <p class="text-muted small mb-0">Employee details, positions, and history updates.</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card border-0 bg-light p-3 h-100 rounded-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-file-earmark-bar-graph text-info fs-4 me-2"></i>
                            <h6 class="fw-bold m-0 text-dark">Payroll / Reports</h6>
                        </div>
                        <p class="text-muted small mb-0">Monthly payroll history and custom listings.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
