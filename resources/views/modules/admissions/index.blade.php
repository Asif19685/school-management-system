@extends('layouts.master')

@section('title', 'Student Admissions')
@section('header-title', 'Student Admissions')

@section('content')

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Student Admissions</li>
            </ol>
        </nav>
        <a href="{{ route('admissions.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> New Admission
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="stat-icon me-3 bg-primary-light" style="width: 54px; height: 54px; font-size: 1.5rem;">
                        <i class="bi bi-person-plus"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold m-0 text-dark">Student Admissions</h3>
                        <p class="text-muted mb-0">Manage all student admission records</p>
                    </div>
                </div>

                <hr class="my-4">

                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="studentsTable" width="100%">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Admission No.</th>
                                <th>Student Name</th>
                                <th>Applied Class</th>
                                <th>Approved Class</th>
                                <th>Section</th>
                                <th>Roll No</th>
                                <th>Gender</th>
                                <th>Status</th>
                                <th>Admission Date</th>
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

<!-- Process Modal -->
<div class="modal fade" id="processModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i> Process Admission Application</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="processForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="process_id">
                <div class="modal-body">
                    <div class="bg-light p-3 rounded mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">Admission No:</small>
                                <p class="fw-bold mb-2" id="process_admission_no">-</p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Student Name:</small>
                                <p class="fw-bold mb-2" id="process_student_name">-</p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Previous School:</small>
                                <p class="mb-2" id="Previous_school_name">-</p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Applied Class:</small>
                                <p class="mb-2" id="process_applied_class">-</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="process_status" class="form-select" required>
                                <option value="pending">Pending</option>
                                <option value="approved">✅ Approve</option>
                                <option value="rejected">❌ Reject</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Admission Date</label>
                            <input type="date" name="admission_date" id="process_admission_date" class="form-control">
                        </div>
                    </div>

                    <div class="row" id="approval_fields" style="display: none;">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Approved Class</label>
                            <select name="class_id" id="process_class_id" class="form-select">
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Section</label>
                            <select name="section_id" id="process_section_id" class="form-select">
                                <option value="">Select Section</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Roll Number</label>
                            <input type="text" name="roll_no" id="process_roll_no" class="form-control" placeholder="Auto-generated">
                            <small class="text-muted">Leave empty for auto-generation</small>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Approved By Officer</label>
                            <input type="text" name="approved_by_officer" id="process_officer" class="form-control" placeholder="Officer name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Approved By Head</label>
                            <input type="text" name="approved_by_head" id="process_head" class="form-control" placeholder="Head name">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" id="process_remarks" class="form-control" rows="2" placeholder="Any remarks..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Save & Process
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Modal -->
{{-- <div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-eye me-2"></i> Student Admission Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Section 1: Admission Info -->
                <div class="card mb-3 border-0 bg-light">


     <div class="card-body">
        <h6 class="fw-bold text-info mb-3"><i class="bi bi-person me-2"></i> Student Iamges</h6>
        <div class="row">
            <!-- Add Image Column -->
            <div class="col-md-12 mb-3 text-center">
                <div id="view_student_image_container">
                    <img id="view_student_image" src="" alt="Student Image"
                         class="img-fluid rounded-circle"
                         style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #0dcaf0;">
                </div>
            </div>

                    <div class="card-body">
                        <h6 class="fw-bold text-info mb-3"><i class="bi bi-mortarboard me-2"></i> Admission Information</h6>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Admission No:</small>
                                <span class="fw-bold" id="view_admission_no">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Admission Date:</small>
                                <span id="view_admission_date">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Status:</small>
                                <span id="view_status">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Applied Class:</small>
                                <span id="view_applied_class">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Approved Class:</small>
                                <span id="view_approved_class">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Section:</small>
                                <span id="view_section">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Student Info -->
                <div class="card mb-3 border-0 bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold text-info mb-3"><i class="bi bi-person me-2"></i> Student Personal Details</h6>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Registration No:</small>
                                <span class="fw-bold" id="view_reg_no">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Full Name:</small>
                                <span id="view_student_name">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Gender:</small>
                                <span id="view_gender">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Date of Birth:</small>
                                <span id="view_dob">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Religion:</small>
                                <span id="view_religion">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">B-Form No:</small>
                                <span id="view_b_form">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Previous Class:</small>
                                <span id="view_previous_class">-</span>
                            </div>
                            <div class="col-md-8 mb-2">
                                <small class="text-muted d-block">Previous School Details:</small>
                                <span id="view_previous_school">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Guardian Info -->
                <div class="card mb-3 border-0 bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold text-info mb-3"><i class="bi bi-people me-2"></i> Guardian Information</h6>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Father Name:</small>
                                <span id="view_father_name">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Father CNIC:</small>
                                <span id="view_father_cnic">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Father Occupation:</small>
                                <span id="view_father_occupation">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Mother Name:</small>
                                <span id="view_mother_name">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Phone:</small>
                                <span id="view_guardian_phone">-</span>
                            </div>
                            <div class="col-md-8 mb-2">
                                <small class="text-muted d-block">Complete Address:</small>
                                <span id="view_complete_address">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Approval Signatures & Remarks -->
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold text-info mb-3"><i class="bi bi-check2-circle me-2"></i> Approval & Remarks</h6>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Approved By Officer:</small>
                                <span id="view_approved_by_officer">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Approved By Head:</small>
                                <span id="view_approved_by_head">-</span>
                            </div>
                            <div class="col-md-12 mb-2">
                                <small class="text-muted d-block">Remarks:</small>
                                <span id="view_remarks">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> --}}
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-eye me-2"></i> Student Admission Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <!-- ✅ STUDENT PROFILE PICTURE - Edit Page Style -->
                <div class="card mb-3 border-0 bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold text-info mb-3"><i class="bi bi-camera me-2"></i> Student Profile Picture</h6>
                        <div class="d-flex align-items-center gap-4 flex-wrap">
                            <!-- Circle Preview -->
                            <div class="position-relative" style="width:120px; height:120px;">
                                <img id="view_student_image"

                                    alt="Profile Preview"
                                    class="rounded-circle border border-3 border-info shadow"
                                    style="width:120px; height:120px; object-fit:cover;">
                            </div>
                            <!-- Image Info -->
                            <div>
                                <div id="view_image_status">
                                    <span class="badge bg-secondary">No Photo</span>
                                </div>
                                <small class="text-muted d-block mt-2">Student Profile Photo</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 1: Admission Info -->
                <div class="card mb-3 border-0 bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold text-info mb-3"><i class="bi bi-mortarboard me-2"></i> Admission Information</h6>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Admission No:</small>
                                <span class="fw-bold" id="view_admission_no">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Admission Date:</small>
                                <span id="view_admission_date">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Status:</small>
                                <span id="view_status">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Applied Class:</small>
                                <span id="view_applied_class">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Approved Class:</small>
                                <span id="view_approved_class">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Section:</small>
                                <span id="view_section">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Student Info -->
                <div class="card mb-3 border-0 bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold text-info mb-3"><i class="bi bi-person me-2"></i> Student Personal Details</h6>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Registration No:</small>
                                <span class="fw-bold" id="view_reg_no">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Full Name:</small>
                                <span id="view_student_name">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Gender:</small>
                                <span id="view_gender">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Date of Birth:</small>
                                <span id="view_dob">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Religion:</small>
                                <span id="view_religion">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">B-Form No:</small>
                                <span id="view_b_form">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Previous Class:</small>
                                <span id="view_previous_class">-</span>
                            </div>
                            <div class="col-md-8 mb-2">
                                <small class="text-muted d-block">Previous School Details:</small>
                                <span id="view_previous_school">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Guardian Info -->
                <div class="card mb-3 border-0 bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold text-info mb-3"><i class="bi bi-people me-2"></i> Guardian Information</h6>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Father Name:</small>
                                <span id="view_father_name">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Father CNIC:</small>
                                <span id="view_father_cnic">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Father Occupation:</small>
                                <span id="view_father_occupation">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Mother Name:</small>
                                <span id="view_mother_name">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Phone:</small>
                                <span id="view_guardian_phone">-</span>
                            </div>
                            <div class="col-md-8 mb-2">
                                <small class="text-muted d-block">Complete Address:</small>
                                <span id="view_complete_address">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Approval Signatures & Remarks -->
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold text-info mb-3"><i class="bi bi-check2-circle me-2"></i> Approval & Remarks</h6>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Approved By Officer:</small>
                                <span id="view_approved_by_officer">-</span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Approved By Head:</small>
                                <span id="view_approved_by_head">-</span>
                            </div>
                            <div class="col-md-12 mb-2">
                                <small class="text-muted d-block">Remarks:</small>
                                <span id="view_remarks">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
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
    var table = $('#studentsTable').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ordering: true,
        order: [[1, 'desc']],
        lengthMenu: [[3, 10, 25, 50, 100, -1], [3, 10, 25, 50, 100, "All"]],
        pageLength: 10,
        ajax: {
            url: "{{ route('admissions.data') }}",
            type: 'GET',
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'admission_no', name: 'admission_no' },
            { data: 'student_name', name: 'student_name' },
            { data: 'applied_class', name: 'schoolClass.class_name' },
            { data: 'class', name: 'schoolClass.class_name', orderable: false  },
            { data: 'section', name: 'section.section_name', orderable: false },

            { data: 'roll_no', name: 'section.roll_no' },
            { data: 'gender', name: 'gender', orderable: false },
            { data: 'status', name: 'status', orderable: false },
            { data: 'admission_date', name: 'admission_date' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        language: {
            processing: '<div class="spinner-border text-primary"></div>',
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            zeroRecords: "No data found",
        }
    });

    // Show/hide approval fields based on status
    $(document).on('change', '#process_status', function() {
        if ($(this).val() === 'approved') {
            $('#approval_fields').slideDown();
        } else {
            $('#approval_fields').slideUp();
        }
    });

    // Process function - using url() helper
    window.processAdmission = function(id) {
        $.ajax({
            url: "{{ url('student-admissions') }}/" + id,
            method: 'GET',
            success: function(response) {
                console.log(response);
                $('#process_id').val(response.id);
                $('#process_admission_no').text(response.admission_no || '-');
                $('#process_student_name').text(response.student?.first_name || '-');
                $('#Previous_school_name').text(response.student?.previous_school_details || '-');
                $('#process_applied_class').text(response.applied_class?.class_name || '-');


            // $('#process_applied_class').text(response.applied_class?.class_name || '-');

            // 2. DIRECT ID ki madad se dropdown ke andar se text (Name) nikal lein


                $('#process_admission_date').val(response.admission_date || new Date().toISOString().split('T')[0]);
                $('#process_status').val(response.status);
                $('#process_class_id').val(response.class_id);
                $('#process_section_id').val(response.section_id);
                $('#process_roll_no').val(response.roll_no);
                $('#process_officer').val(response.approved_by_officer);
                $('#process_head').val(response.approved_by_head);
                $('#process_remarks').val(response.remarks);

                if (response.status === 'approved') {
                    $('#approval_fields').show();
                } else {
                    $('#approval_fields').hide();
                }

                $('#processModal').modal('show');
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Could not fetch admission details', 'error');
            }
        });
    };

    // Form submit handler
    $('#processForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#process_id').val();

        $.ajax({
            url: "{{ url('student-admissions') }}/" + id,
            method: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#processModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire('Success!', response.message, 'success');
                }
            },
            error: function(xhr) {
                Swal.fire('Error!', xhr.responseJSON?.message || 'Something went wrong!', 'error');
            }
        });
    });

    // Delete function
    window.deleteAdmission = function(id) {
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
                    url: "{{ url('student-admissions') }}/" + id,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if (response.success) {
                            table.ajax.reload();
                            Swal.fire('Deleted!', response.message, 'success');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Something went wrong!', 'error');
                    }
                });
            }
        });
    };

    // View Admission details function
    // window.viewAdmission = function(id) {
    //     $.ajax({
    //         url: "{{ url('student-admissions') }}/" + id,
    //         method: 'GET',
    //         success: function(response) {
    //             console.log(response);
    //             $('#view_admission_no').text(response.admission_no || '-');

    //             // Format date nicely
    //             let admDate = response.admission_date;
    //             if (admDate) {
    //                 let d = new Date(admDate);
    //                 admDate = d.toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });
    //             }
    //             $('#view_admission_date').text(admDate || '-');

    //             // Status Badge
    //             let statusBadge = '-';
    //             if (response.status) {
    //                 let badgeClass = 'bg-secondary';
    //                 if (response.status === 'approved') badgeClass = 'bg-success';
    //                 else if (response.status === 'pending') badgeClass = 'bg-warning';
    //                 else if (response.status === 'rejected') badgeClass = 'bg-danger';

    //                 statusBadge = `<span class="badge ${badgeClass} text-capitalize">${response.status}</span>`;
    //             }
    //             $('#view_status').html(statusBadge);

    //             $('#view_applied_class').text(response.applied_class?.class_name || '-');
    //             $('#view_approved_class').text(response.school_class?.class_name || '-');
    //             $('#view_section').text(response.section?.section_name || '-');

    //             // Student details
    //             $('#view_reg_no').text(response.student?.registration_no || '-');
    //             let fullName = (response.student?.first_name || '') + ' ' + (response.student?.last_name || '');
    //             $('#view_student_name').text(fullName.trim() || '-');
    //             $('#view_gender').text(response.student?.gender || '-');

    //             let dob = response.student?.dob;
    //             if (dob) {
    //                 let d = new Date(dob);
    //                 dob = d.toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });
    //             }
    //             $('#view_dob').text(dob || '-');
    //             $('#view_religion').text(response.student?.religion || '-');
    //             $('#view_b_form').text(response.student?.b_form_no || '-');
    //             $('#view_previous_class').text(response.student?.previous_class || '-');
    //             $('#view_previous_school').text(response.student?.previous_school_details || '-');

    //             // Guardian details
    //             $('#view_father_name').text(response.student?.guardian?.father_name || '-');
    //             $('#view_father_cnic').text(response.student?.guardian?.father_cnic || '-');
    //             $('#view_father_occupation').text(response.student?.guardian?.father_occupation || '-');
    //             $('#view_mother_name').text(response.student?.guardian?.mother_name || '-');
    //             $('#view_guardian_phone').text(response.student?.guardian?.phone || '-');
    //             $('#view_complete_address').text(response.student?.guardian?.complete_address || '-');

    //             // Approvals & Remarks
    //             $('#view_approved_by_officer').text(response.approved_by_officer || '-');
    //             $('#view_approved_by_head').text(response.approved_by_head || '-');
    //             $('#view_remarks').text(response.remarks || '-');

    //             $('#viewModal').modal('show');
    //         },
    //         error: function(xhr) {
    //             Swal.fire('Error!', 'Could not fetch admission details', 'error');
    //         }
    //     });
    // };
window.viewAdmission = function(id) {
    $.ajax({
        url: "{{ url('student-admissions') }}/" + id,
        method: 'GET',
        success: function(response) {
            console.log(response);


            let imageUrl = response.student?.image_url;
            $('#view_student_image').attr('src', imageUrl);

            // Image status badge update karein
            if (response.student?.student_image) {
                $('#view_image_status').html('<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Photo Available</span>');
            } else {
                $('#view_image_status').html('<span class="badge bg-secondary">No Photo</span>');
            }

            // Admission Info
            $('#view_admission_no').text(response.admission_no || '-');

            // Format date nicely
            let admDate = response.admission_date;
            if (admDate) {
                let d = new Date(admDate);
                admDate = d.toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });
            }
            $('#view_admission_date').text(admDate || '-');

            // Status Badge
            let statusBadge = '-';
            if (response.status) {
                let badgeClass = 'bg-secondary';
                if (response.status === 'approved') badgeClass = 'bg-success';
                else if (response.status === 'pending') badgeClass = 'bg-warning';
                else if (response.status === 'rejected') badgeClass = 'bg-danger';

                statusBadge = `<span class="badge ${badgeClass} text-capitalize">${response.status}</span>`;
            }
            $('#view_status').html(statusBadge);

            $('#view_applied_class').text(response.applied_class?.class_name || '-');
            $('#view_approved_class').text(response.school_class?.class_name || '-');
            $('#view_section').text(response.section?.section_name || '-');

            // Student details
            $('#view_reg_no').text(response.student?.registration_no || '-');
            let fullName = (response.student?.first_name || '') + ' ' + (response.student?.last_name || '');
            $('#view_student_name').text(fullName.trim() || '-');
            $('#view_gender').text(response.student?.gender || '-');

            let dob = response.student?.dob;
            if (dob) {
                let d = new Date(dob);
                dob = d.toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });
            }
            $('#view_dob').text(dob || '-');
            $('#view_religion').text(response.student?.religion || '-');
            $('#view_b_form').text(response.student?.b_form_no || '-');
            $('#view_previous_class').text(response.student?.previous_class || '-');
            $('#view_previous_school').text(response.student?.previous_school_details || '-');

            // Guardian details
            $('#view_father_name').text(response.student?.guardian?.father_name || '-');
            $('#view_father_cnic').text(response.student?.guardian?.father_cnic || '-');
            $('#view_father_occupation').text(response.student?.guardian?.father_occupation || '-');
            $('#view_mother_name').text(response.student?.guardian?.mother_name || '-');
            $('#view_guardian_phone').text(response.student?.guardian?.phone || '-');
            $('#view_complete_address').text(response.student?.guardian?.complete_address || '-');

            // Approvals & Remarks
            $('#view_approved_by_officer').text(response.approved_by_officer || '-');
            $('#view_approved_by_head').text(response.approved_by_head || '-');
            $('#view_remarks').text(response.remarks || '-');

            // Image error handling - agar image load na ho to default show karein
            $('#view_student_image').off('error').on('error', function() {
                $(this).attr('src', "{{ asset('images/default-avatar.png') }}");
                $('#view_image_status').html('<span class="badge bg-warning">Image Load Failed</span>');
            });

            $('#viewModal').modal('show');
        },
        error: function(xhr) {
            console.error('Error:', xhr);
            Swal.fire('Error!', 'Could not fetch admission details', 'error');
        }
    });
};
});
</script>

<style>
.stat-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
}
.bg-primary-light {
    background-color: rgba(13, 110, 253, 0.1);
}
</style>
@endpush
