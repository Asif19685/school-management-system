@extends('layouts.master')

@section('title', 'Visitor Management - School Management System')
@section('header-title', 'Visitor List')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Visitor List</li>
            </ol>
        </nav>
        <a href="{{ route('visitors.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> create Visitor
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="stat-icon me-3 bg-primary-light" style="width: 54px; height: 54px; font-size: 1.5rem;">
                        <i class="bi bi-chat-left-text"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold m-0 text-dark">Visitor Logs</h3>
                        <p class="text-muted mb-0">Record and track all visitors entering the school premises.</p>
                    </div>
                </div>

                <hr class="my-4 text-muted opacity-25">

                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="visitorsTable" width="100%">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Visitor Name</th>
                                <th>Phone</th>
                                <th>Visitor Type</th>
                                <th>Purpose</th>
                                <th>Meeting With</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- DataTables will populate -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Visitor Modal -->
<div class="modal fade" id="editVisitorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i> Edit Visitor Log</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editVisitorForm" novalidate>
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_visitor_id" name="id">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Visitor Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" id="edit_name" class="form-control" placeholder="Enter visitor name" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-phone"></i></span>
                            <input type="text" name="phone" id="edit_phone" class="form-control" placeholder="Enter phone number">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Visitor Type</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                <input type="text" name="visitor_type" id="edit_visitor_type" class="form-control" placeholder="e.g. Parent, Vendor">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Purpose</label>
                            <input type="text" name="purpose" id="edit_purpose" class="form-control" placeholder="e.g. Inquiry, Meeting">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Meeting With</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-workspace"></i></span>
                            <input type="text" name="meeting_with" id="edit_meeting_with" class="form-control" placeholder="e.g. Principal, Admin Officer">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Time In</label>
                            <input type="datetime-local" name="time_in" id="edit_time_in" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Time Out</label>
                            <input type="datetime-local" name="time_out" id="edit_time_out" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Update Log
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Visitor Modal -->
<div class="modal fade" id="viewVisitorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-eye me-2"></i> Visitor Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="text-muted small text-uppercase fw-bold mb-3"><i class="bi bi-person-badge text-primary me-2"></i> Visitor Profile</h6>
                        <div class="row g-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Name</small>
                                <span class="fw-bold text-dark fs-6" id="view_name">-</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Phone Number</small>
                                <span class="fw-semibold text-dark fs-6" id="view_phone">-</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Visitor Type</small>
                                <span class="badge bg-secondary-light text-secondary px-2 py-1" id="view_visitor_type">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="text-muted small text-uppercase fw-bold mb-3"><i class="bi bi-info-circle text-success me-2"></i> Visit Information</h6>
                        <div class="row g-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Purpose</small>
                                <span class="fw-semibold text-dark" id="view_purpose">-</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Meeting With</small>
                                <span class="fw-semibold text-dark" id="view_meeting_with">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted small text-uppercase fw-bold mb-3"><i class="bi bi-clock text-info me-2"></i> Log Details</h6>
                        <div class="row g-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Time In</small>
                                <span class="fw-semibold text-dark" id="view_time_in">-</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Time Out</small>
                                <span class="fw-semibold text-dark" id="view_time_out">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // CSRF token setup for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize DataTable
    var table = $('#visitorsTable').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ordering: true,
        order: [[6, 'desc']], // Order by Time In by default
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        pageLength: 10,
        ajax: {
            url: "{{ route('visitors.data') }}",
            type: 'GET',
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'phone', name: 'phone' },
            { data: 'visitor_type', name: 'visitor_type' },
            { data: 'purpose', name: 'purpose' },
            { data: 'meeting_with', name: 'meeting_with' },
            { data: 'time_in_formatted', name: 'time_in' },
            { data: 'time_out_formatted', name: 'time_out' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        language: {
            processing: '<div class="spinner-border text-primary"></div>',
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            zeroRecords: "No matching visitor records found",
        }
    });

    // Handle Edit Button Click
    $(document).on('click', '.edit-visitor-btn', function() {
        let id = $(this).data('id');

        $.ajax({
            url: "{{ url('visitors') }}/" + id,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    let visitor = response.visitor;
                    $('#edit_visitor_id').val(visitor.id);
                    $('#edit_name').val(visitor.name);
                    $('#edit_phone').val(visitor.phone);
                    $('#edit_visitor_type').val(visitor.visitor_type);
                    $('#edit_purpose').val(visitor.purpose);
                    $('#edit_meeting_with').val(visitor.meeting_with);
                    $('#edit_time_in').val(visitor.time_in_formatted);
                    $('#edit_time_out').val(visitor.time_out_formatted);

                    $('#editVisitorModal').modal('show');
                }
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Could not load visitor data.', 'error');
            }
        });
    });

    // Handle Edit Form Submit
    $('#editVisitorForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#edit_visitor_id').val();

        // Clear previous validation errors
        $('#editVisitorForm .is-invalid').removeClass('is-invalid');
        $('#editVisitorForm .invalid-feedback').remove();

        Swal.fire({
            title: 'Updating...',
            text: 'Please wait while we update the log.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: "{{ url('visitors') }}/" + id,
            method: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#editVisitorModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        confirmButtonColor: '#4f46e5'
                    });
                }
            },
            error: function(xhr) {
                Swal.close(); // Close the loading modal

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    
                    // Display errors under each field inside the edit form
                    Object.keys(errors).forEach(key => {
                        let input = $('#editVisitorForm [name="' + key + '"]');
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

    // Handle View Button Click
    $(document).on('click', '.view-visitor-btn', function() {
        let id = $(this).data('id');

        $.ajax({
            url: "{{ url('visitors') }}/" + id,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    let visitor = response.visitor;
                    $('#view_name').text(visitor.name || '-');
                    $('#view_phone').text(visitor.phone || '-');
                    $('#view_visitor_type').text(visitor.visitor_type || 'N/A');
                    $('#view_purpose').text(visitor.purpose || '-');
                    $('#view_meeting_with').text(visitor.meeting_with || '-');

                    // Format dates for display
                    let timeInVal = visitor.time_in ? new Date(visitor.time_in).toLocaleString('en-US', { dateStyle: 'medium', timeStyle: 'short' }) : '-';
                    let timeOutVal = visitor.time_out ? new Date(visitor.time_out).toLocaleString('en-US', { dateStyle: 'medium', timeStyle: 'short' }) : '-';

                    $('#view_time_in').text(timeInVal);
                    $('#view_time_out').text(timeOutVal);

                    $('#viewVisitorModal').modal('show');
                }
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Could not load visitor details.', 'error');
            }
        });
    });

    // Handle Delete Button Click
    $(document).on('click', '.delete-visitor-btn', function() {
        let id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('visitors') }}/" + id,
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            table.ajax.reload();
                            Swal.fire(
                                'Deleted!',
                                response.message,
                                'success'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Something went wrong!', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endpush
