@extends('layouts.master')

@section('title', 'Teacher Management - School Management System')
@section('header-title', 'Teacher List')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Teacher List</li>
            </ol>
        </nav>
        <a href="{{ route('teachers.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Add Teacher
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="stat-icon me-3 bg-primary-light" style="width: 54px; height: 54px; font-size: 1.5rem;">
                        <i class="bi bi-person-badge text-primary"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold m-0 text-dark">Teachers Directory</h3>
                        <p class="text-muted mb-0">View and manage all teachers in the school.</p>
                    </div>
                </div>

                <hr class="my-4 text-muted opacity-25">

                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="teachersTable" width="100%">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Qualification</th>
                                <th>Joining Date</th>
                                <th>Salary</th>
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

<!-- Edit Teacher Modal -->
<div class="modal fade" id="editTeacherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i> Edit Teacher</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTeacherForm" novalidate>
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_teacher_id">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Teacher Code <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                <input type="text" name="teacher_code" id="edit_teacher_code" class="form-control" placeholder="e.g. TCH-001" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="name" id="edit_name" class="form-control" placeholder="Enter full name" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" id="edit_email" class="form-control" placeholder="Enter email address">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Phone</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                <input type="text" name="phone" id="edit_phone" class="form-control" placeholder="Enter phone number">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Qualification</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-mortarboard"></i></span>
                                <input type="text" name="qualification" id="edit_qualification" class="form-control" placeholder="e.g. M.Ed, B.Sc">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Joining Date</label>
                            <input type="date" name="joining_date" id="edit_joining_date" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Salary (Rs.)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-cash"></i></span>
                                <input type="number" name="salary" id="edit_salary" class="form-control" placeholder="Enter monthly salary" min="0" step="0.01">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Update Teacher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Teacher Modal -->
<div class="modal fade" id="viewTeacherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-eye me-2"></i> Teacher Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="text-muted small text-uppercase fw-bold mb-3"><i class="bi bi-person-badge text-primary me-2"></i> Profile</h6>
                        <div class="row g-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Code</small>
                                <span class="fw-bold text-dark" id="view_teacher_code">-</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Name</small>
                                <span class="fw-bold text-dark" id="view_name">-</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Email</small>
                                <span class="fw-semibold text-dark" id="view_email">-</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Phone</small>
                                <span class="fw-semibold text-dark" id="view_phone">-</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted small text-uppercase fw-bold mb-3"><i class="bi bi-info-circle text-success me-2"></i> Employment Details</h6>
                        <div class="row g-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Qualification</small>
                                <span class="fw-semibold text-dark" id="view_qualification">-</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Joining Date</small>
                                <span class="fw-semibold text-dark" id="view_joining_date">-</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Salary</small>
                                <span class="fw-semibold text-dark" id="view_salary">-</span>
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
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Initialize DataTable
    var table = $('#teachersTable').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ordering: true,
        order: [[1, 'asc']],
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        pageLength: 10,
        ajax: { url: "{{ route('teachers.data') }}", type: 'GET' },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'teacher_code', name: 'teacher_code' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email', defaultContent: '-' },
            { data: 'phone', name: 'phone', defaultContent: '-' },
            { data: 'qualification', name: 'qualification', defaultContent: '-' },
            { data: 'joining_date_formatted', name: 'joining_date' },
            { data: 'salary_formatted', name: 'salary' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        language: {
            processing: '<div class="spinner-border text-primary"></div>',
            zeroRecords: 'No matching teacher records found',
        }
    });

    // View Teacher
    $(document).on('click', '.view-teacher-btn', function() {
        let id = $(this).data('id');
        $.ajax({
            url: "{{ url('teachers') }}/" + id,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    let t = response.teacher;
                    $('#view_teacher_code').text(t.teacher_code || '-');
                    $('#view_name').text(t.name || '-');
                    $('#view_email').text(t.email || '-');
                    $('#view_phone').text(t.phone || '-');
                    $('#view_qualification').text(t.qualification || '-');
                    $('#view_joining_date').text(t.joining_date_formatted || '-');
                    $('#view_salary').text(t.salary ? 'Rs. ' + parseFloat(t.salary).toLocaleString() : '-');
                    $('#viewTeacherModal').modal('show');
                }
            },
            error: function() { Swal.fire('Error!', 'Could not load teacher details.', 'error'); }
        });
    });

    // Edit Teacher - load data
    $(document).on('click', '.edit-teacher-btn', function() {
        let id = $(this).data('id');
        $.ajax({
            url: "{{ url('teachers') }}/" + id,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    let t = response.teacher;
                    $('#edit_teacher_id').val(t.id);
                    $('#edit_teacher_code').val(t.teacher_code);
                    $('#edit_name').val(t.name);
                    $('#edit_email').val(t.email);
                    $('#edit_phone').val(t.phone);
                    $('#edit_qualification').val(t.qualification);
                    $('#edit_joining_date').val(t.joining_date_formatted);
                    $('#edit_salary').val(t.salary);
                    $('#editTeacherModal').modal('show');
                }
            },
            error: function() { Swal.fire('Error!', 'Could not load teacher data.', 'error'); }
        });
    });

    // Edit Form Submit
    $('#editTeacherForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#edit_teacher_id').val();

        $('#editTeacherForm .is-invalid').removeClass('is-invalid');
        $('#editTeacherForm .invalid-feedback').remove();

        Swal.fire({ title: 'Updating...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        $.ajax({
            url: "{{ url('teachers') }}/" + id,
            method: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#editTeacherModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire({ icon: 'success', title: 'Success!', text: response.message, confirmButtonColor: '#4f46e5' });
                }
            },
            error: function(xhr) {
                Swal.close();
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(key => {
                        let input = $('#editTeacherForm [name="' + key + '"]');
                        input.addClass('is-invalid');
                        if (input.parent().hasClass('input-group')) {
                            input.parent().after('<div class="invalid-feedback d-block">' + errors[key][0] + '</div>');
                        } else {
                            input.after('<div class="invalid-feedback d-block">' + errors[key][0] + '</div>');
                        }
                    });
                    Swal.fire({ icon: 'error', title: 'Validation Error!', text: 'Please correct the errors in the form.', confirmButtonColor: '#d33' });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error!', text: 'Something went wrong!', confirmButtonColor: '#d33' });
                }
            }
        });
    });

    // Delete Teacher
    $(document).on('click', '.delete-teacher-btn', function() {
        let id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This teacher record will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('teachers') }}/" + id,
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            table.ajax.reload();
                            Swal.fire('Deleted!', response.message, 'success');
                        }
                    },
                    error: function() { Swal.fire('Error!', 'Something went wrong!', 'error'); }
                });
            }
        });
    });
});
</script>
@endpush
