@extends('layouts.master')

@section('title', 'Log Visitor - School Management System')
@section('header-title', 'Log Visitor')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('visitors.index') }}" class="text-decoration-none">Visitor Management</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Visitor</li>
            </ol>
        </nav>
        <a href="{{ route('visitors.index') }}" class="btn btn-outline-secondary btn-sm">
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
                        <i class="bi bi-plus-circle-fill text-primary"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold m-0 text-dark"> New Visitor</h3>
                        <p class="text-muted mb-0">Fill in the fields below to record a new visitor entry manually.</p>
                    </div>
                </div>

                <hr class="my-4 text-muted opacity-25">

                <form id="createVisitorForm" novalidate>
                    @csrf
                    <div class="row">
                        <!-- Visitor Name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Visitor Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="name" class="form-control" placeholder="Enter full name" >
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                <input type="text" name="phone" class="form-control" placeholder="Enter phone number">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Visitor Type -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Visitor Type</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                <input type="text" name="visitor_type" class="form-control" placeholder="e.g. Parent, Vendor, Official">
                            </div>
                        </div>

                        <!-- Purpose -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Purpose of Visit</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-question-circle"></i></span>
                                <input type="text" name="purpose" class="form-control" placeholder="e.g. Admission Query, Meeting">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Meeting With -->
                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold">Meeting With</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-workspace"></i></span>
                                <input type="text" name="meeting_with" class="form-control" placeholder="Enter staff name or department">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Time In -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Time In</label>
                            <input type="datetime-local" name="time_in" id="time_in" class="form-control">
                        </div>

                        <!-- Time Out -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Time Out</label>
                            <input type="datetime-local" name="time_out" class="form-control">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-4">
                        <a href="{{ route('visitors.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Save Log Entry
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
    // CSRF token setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Set current datetime to Time In field
    let now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    $('#time_in').val(now.toISOString().slice(0, 16));

    // Handle Form Submit
    $('#createVisitorForm').on('submit', function(e) {
        e.preventDefault();

        // Clear previous validation errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        Swal.fire({
            title: 'Saving...',
            text: 'Please wait while we log the visitor.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: "{{ route('visitors.store') }}",
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
                Swal.close(); // Close the loading modal
                
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    
                    // Display errors under each field
                    Object.keys(errors).forEach(key => {
                        let input = $('[name="' + key + '"]');
                        input.addClass('is-invalid');
                        
                        // Append error message correctly
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
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong!',
                        confirmButtonColor: '#d33'
                    });
                }
            }
        });
    });
});
</script>
@endpush
