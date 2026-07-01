@extends('layouts.master')

@section('title', 'Add Teacher - School Management System')
@section('header-title', 'Add Teacher')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('teachers.index') }}" class="text-decoration-none">Teacher Management</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Teacher</li>
            </ol>
        </nav>
        <a href="{{ route('teachers.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Directory
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="stat-icon me-3 bg-primary-light" style="width: 54px; height: 54px; font-size: 1.5rem;">
                        <i class="bi bi-person-plus-fill text-primary"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold m-0 text-dark">Add New Teacher</h3>
                        <p class="text-muted mb-0">Fill in the fields below to register a new teacher.</p>
                    </div>
                </div>

                <hr class="my-4 text-muted opacity-25">

                <form id="createTeacherForm" novalidate>
                    @csrf
                    <div class="row">
                        <!-- Teacher Code -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Teacher Code <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                <input type="text" name="teacher_code" class="form-control" placeholder="e.g. TCH-001" required>
                            </div>
                        </div>

                        <!-- Full Name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="Enter email address">
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                <input type="text" name="phone" class="form-control" placeholder="Enter phone number">
                            </div>
                        </div>

                        <!-- Qualification -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Qualification</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-mortarboard"></i></span>
                                <input type="text" name="qualification" class="form-control" placeholder="e.g. M.Ed, B.Sc, PhD">
                            </div>
                        </div>

                        <!-- Joining Date -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Joining Date</label>
                            <input type="date" name="joining_date" class="form-control">
                        </div>

                        <!-- Salary -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Monthly Salary (Rs.)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-cash"></i></span>
                                <input type="number" name="salary" class="form-control" placeholder="Enter monthly salary" min="0" step="0.01">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-4">
                        <a href="{{ route('teachers.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Save Teacher
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $('#createTeacherForm').on('submit', function(e) {
        e.preventDefault();

        // Clear previous validation errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        Swal.fire({
            title: 'Saving...',
            text: 'Please wait while we add the teacher.',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $.ajax({
            url: "{{ route('teachers.store') }}",
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        confirmButtonColor: '#4f46e5'
                    }).then(() => {
                        window.location.href = response.redirect;
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(key => {
                        let input = $('[name="' + key + '"]');
                        input.addClass('is-invalid');
                        if (input.parent().hasClass('input-group')) {
                            input.parent().after('<div class="invalid-feedback d-block">' + errors[key][0] + '</div>');
                        } else {
                            input.after('<div class="invalid-feedback d-block">' + errors[key][0] + '</div>');
                        }
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error!',
                        text: 'Please correct the errors in the form.',
                        confirmButtonColor: '#d33'
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error!', text: 'Something went wrong!', confirmButtonColor: '#d33' });
                }
            }
        });
    });
});
</script>
@endpush
