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

                <!-- ── Filter Bar ──────────────────────────────────────────── -->
                <div class="p-3 bg-light rounded-3 border border-light shadow-sm mb-3">
                    <div class="row g-2 align-items-end">
                        <!-- Class Filter -->
                        <div class="col-md-3 col-sm-6">
                            <label for="class_filter" class="form-label small fw-bold mb-1 text-secondary">
                                <i class="bi bi-mortarboard me-1"></i>Filter by Class
                            </label>
                            <select id="class_filter" class="form-select form-select-sm border-0 shadow-sm">
                                <option value="all">-- All Classes --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- From Date Filter -->
                        <div class="col-md-3 col-sm-6">
                            <label for="from_date" class="form-label small fw-bold mb-1 text-secondary">
                                <i class="bi bi-calendar-date me-1"></i>From Date
                            </label>
                            <input type="date" id="from_date" class="form-control form-control-sm border-0 shadow-sm">
                        </div>
                        <!-- To Date Filter -->
                        <div class="col-md-3 col-sm-6">
                            <label for="to_date" class="form-label small fw-bold mb-1 text-secondary">
                                <i class="bi bi-calendar-date me-1"></i>To Date
                            </label>
                            <input type="date" id="to_date" class="form-control form-control-sm border-0 shadow-sm">
                        </div>
                        <!-- Buttons -->
                        <div class="col-md-2 col-6">
                            <button type="button" id="apply_filter_btn" class="btn btn-primary btn-sm w-100 shadow-sm">
                                <i class="bi bi-funnel-fill me-1"></i>Apply
                            </button>
                        </div>
                        <div class="col-md-1 col-6">
                            <button type="button" id="clear_filter_btn" class="btn btn-outline-secondary btn-sm w-100" title="Clear All Filters">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Active filter badges -->
                    <div id="active_filters_row" class="mt-2 d-none d-flex flex-wrap gap-2">
                        <span id="active_class_badge" class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill small d-none">
                            <i class="bi bi-mortarboard me-1"></i>Class: <strong id="active_class_name"></strong>
                            <i class="bi bi-x ms-1" style="cursor:pointer;" onclick="clearClassFilter()"></i>
                        </span>
                        <span id="active_date_badge" class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill small d-none">
                            <i class="bi bi-calendar-date me-1"></i>Date: <strong id="active_date_name"></strong>
                            <i class="bi bi-x ms-1" style="cursor:pointer;" onclick="clearDateFilter()"></i>
                        </span>
                    </div>
                </div>

                <hr class="my-3">


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
                <button type="button" class="btn btn-outline-primary me-auto" onclick="printAdmissionRecord()">
                    <i class="bi bi-printer me-1"></i> Print Record
                </button>
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
            data: function(d) {
                d.class_filter = $('#class_filter').val();
                d.from_date    = $('#from_date').val();
                d.to_date      = $('#to_date').val();
            }
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

    // ── Apply / Clear Filter Buttons ───────────────────────────────────────────
    function updateActiveBadges() {
        var classVal = $('#class_filter').val();
        var fromDate = $('#from_date').val();
        var toDate   = $('#to_date').val();
        var anyActive = false;

        if (classVal !== 'all') {
            $('#active_class_name').text($('#class_filter option:selected').text().trim());
            $('#active_class_badge').removeClass('d-none');
            anyActive = true;
        } else {
            $('#active_class_badge').addClass('d-none');
        }

        if (fromDate || toDate) {
            var formatted = '';
            if (fromDate) {
                var d1 = new Date(fromDate);
                formatted += d1.toLocaleDateString('en-US', { day: '2-digit', month: 'short', year: 'numeric' });
            } else {
                formatted += 'Start';
            }
            formatted += ' to ';
            if (toDate) {
                var d2 = new Date(toDate);
                formatted += d2.toLocaleDateString('en-US', { day: '2-digit', month: 'short', year: 'numeric' });
            } else {
                formatted += 'End';
            }
            $('#active_date_name').text(formatted);
            $('#active_date_badge').removeClass('d-none');
            anyActive = true;
        } else {
            $('#active_date_badge').addClass('d-none');
        }

        if (anyActive) {
            $('#active_filters_row').removeClass('d-none');
        } else {
            $('#active_filters_row').addClass('d-none');
        }
    }

    $('#apply_filter_btn').on('click', function() {
        updateActiveBadges();
        table.ajax.reload();
    });

    $('#clear_filter_btn').on('click', function() {
        $('#class_filter').val('all');
        $('#from_date').val('');
        $('#to_date').val('');
        updateActiveBadges();
        table.ajax.reload();
    });

    window.clearClassFilter = function() {
        $('#class_filter').val('all');
        updateActiveBadges();
        table.ajax.reload();
    };

    window.clearDateFilter = function() {
        $('#from_date').val('');
        $('#to_date').val('');
        updateActiveBadges();
        table.ajax.reload();
    };

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

<script>
function printAdmissionRecord() {
    // Collect all data from the view modal spans
    var studentImage = $('#view_student_image').attr('src') || '';
    var admissionNo   = $('#view_admission_no').text();
    var admissionDate = $('#view_admission_date').text();
    var status        = $('#view_status').html();
    var appliedClass  = $('#view_applied_class').text();
    var approvedClass = $('#view_approved_class').text();
    var section       = $('#view_section').text();

    var regNo          = $('#view_reg_no').text();
    var studentName    = $('#view_student_name').text();
    var gender         = $('#view_gender').text();
    var dob            = $('#view_dob').text();
    var religion       = $('#view_religion').text();
    var bForm          = $('#view_b_form').text();
    var previousClass  = $('#view_previous_class').text();
    var previousSchool = $('#view_previous_school').text();

    var fatherName       = $('#view_father_name').text();
    var fatherCnic       = $('#view_father_cnic').text();
    var fatherOccupation = $('#view_father_occupation').text();
    var motherName       = $('#view_mother_name').text();
    var guardianPhone    = $('#view_guardian_phone').text();
    var completeAddress  = $('#view_complete_address').text();

    var approvedByOfficer = $('#view_approved_by_officer').text();
    var approvedByHead    = $('#view_approved_by_head').text();
    var remarks           = $('#view_remarks').text();

    var printDate = new Date().toLocaleDateString('en-US', { day: 'numeric', month: 'long', year: 'numeric' });

    var printWindow = window.open('', '_blank', 'width=900,height=700');
    printWindow.document.write(`
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admission Record - ${admissionNo}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 11pt;
            color: #222;
            background: #fff;
        }
        @page {
            size: A4 portrait;
            margin: 15mm 15mm 20mm 15mm;
        }
        .page { width: 100%; }

        /* Header / School Banner */
        .print-header {
            text-align: center;
            border-bottom: 3px double #0d6efd;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }
        .print-header h1 {
            font-size: 18pt;
            color: #0d6efd;
            letter-spacing: 1px;
        }
        .print-header p {
            font-size: 9pt;
            color: #555;
            margin-top: 2px;
        }
        .print-header .doc-title {
            font-size: 13pt;
            font-weight: bold;
            color: #333;
            margin-top: 6px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Student Photo + Admission No row */
        .top-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            gap: 12px;
        }
        .student-photo {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 3px solid #0d6efd;
            object-fit: cover;
            flex-shrink: 0;
        }
        .admission-badge {
            text-align: right;
        }
        .admission-badge .adm-no {
            font-size: 14pt;
            font-weight: bold;
            color: #0d6efd;
        }
        .admission-badge .adm-date {
            font-size: 9pt;
            color: #555;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 9pt;
            font-weight: bold;
            margin-top: 4px;
        }
        .status-approved { background: #d1fae5; color: #065f46; border: 1px solid #34d399; }
        .status-pending  { background: #fef3c7; color: #92400e; border: 1px solid #fbbf24; }
        .status-rejected { background: #fee2e2; color: #991b1b; border: 1px solid #f87171; }

        /* Section cards */
        .section {
            border: 1px solid #dde3ee;
            border-radius: 8px;
            margin-bottom: 12px;
            overflow: hidden;
            page-break-inside: avoid;
        }
        .section-header {
            background: #f0f5ff;
            padding: 6px 12px;
            font-weight: bold;
            font-size: 10pt;
            color: #1d4ed8;
            border-bottom: 1px solid #dde3ee;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .section-body {
            padding: 10px 12px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 8px 12px;
        }
        .info-grid.two-col {
            grid-template-columns: 1fr 1fr;
        }
        .info-item label {
            font-size: 8pt;
            color: #6b7280;
            display: block;
            margin-bottom: 1px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .info-item span {
            font-size: 10pt;
            font-weight: 600;
            color: #111827;
        }

        /* Signature area */
        .signature-row {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px dashed #ccc;
        }
        .sig-box {
            text-align: center;
            min-width: 130px;
        }
        .sig-box .sig-line {
            border-bottom: 1px solid #333;
            margin-bottom: 4px;
            height: 35px;
        }
        .sig-box .sig-label {
            font-size: 8.5pt;
            color: #555;
        }
        .sig-box .sig-name {
            font-size: 9.5pt;
            font-weight: bold;
            color: #111;
        }

        /* Footer */
        .print-footer {
            text-align: center;
            margin-top: 14px;
            font-size: 8pt;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
        }

        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
<div class="page">

    <!-- School Header -->
    <div class="print-header">
        <h1>&#127979; School Management System</h1>
        <p>Official Student Admission Record</p>
        <div class="doc-title">Admission Form</div>
    </div>

    <!-- Photo + Admission No -->
    <div class="top-row">
        <div style="display:flex; align-items:center; gap:14px;">
            <img src="${studentImage}" alt="Student Photo" class="student-photo" onerror="this.style.display='none'">
            <div>
                <div style="font-size:14pt; font-weight:bold; color:#111;">${studentName}</div>
                <div style="font-size:9pt; color:#555; margin-top:3px;">Registration No: ${regNo}</div>
            </div>
        </div>
        <div class="admission-badge">
            <div class="adm-no">Adm# ${admissionNo}</div>
            <div class="adm-date">Date: ${admissionDate}</div>
            <span class="status-badge status-${status.includes('success') ? 'approved' : status.includes('warning') ? 'pending' : status.includes('danger') ? 'rejected' : 'pending'}">
                ${status.replace(/<[^>]+>/g, '').trim()}
            </span>
        </div>
    </div>

    <!-- Admission Information -->
    <div class="section">
        <div class="section-header">&#127891; Admission Information</div>
        <div class="section-body">
            <div class="info-grid">
                <div class="info-item"><label>Applied Class</label><span>${appliedClass}</span></div>
                <div class="info-item"><label>Approved Class</label><span>${approvedClass}</span></div>
                <div class="info-item"><label>Section</label><span>${section}</span></div>
            </div>
        </div>
    </div>

    <!-- Personal Details -->
    <div class="section">
        <div class="section-header">&#128100; Personal Details</div>
        <div class="section-body">
            <div class="info-grid">
                <div class="info-item"><label>Full Name</label><span>${studentName}</span></div>
                <div class="info-item"><label>Gender</label><span>${gender}</span></div>
                <div class="info-item"><label>Date of Birth</label><span>${dob}</span></div>
                <div class="info-item"><label>Religion</label><span>${religion}</span></div>
                <div class="info-item"><label>B-Form / CNIC No</label><span>${bForm}</span></div>
                <div class="info-item"><label>Previous Class</label><span>${previousClass}</span></div>
            </div>
            <div class="info-grid two-col" style="margin-top:8px;">
                <div class="info-item" style="grid-column:1/-1;"><label>Previous School</label><span>${previousSchool}</span></div>
            </div>
        </div>
    </div>

    <!-- Guardian Information -->
    <div class="section">
        <div class="section-header">&#128106; Guardian Information</div>
        <div class="section-body">
            <div class="info-grid">
                <div class="info-item"><label>Father Name</label><span>${fatherName}</span></div>
                <div class="info-item"><label>Father CNIC</label><span>${fatherCnic}</span></div>
                <div class="info-item"><label>Father Occupation</label><span>${fatherOccupation}</span></div>
                <div class="info-item"><label>Mother Name</label><span>${motherName}</span></div>
                <div class="info-item"><label>Guardian Phone</label><span>${guardianPhone}</span></div>
            </div>
            <div style="margin-top:8px;">
                <div class="info-item"><label>Complete Address</label><span>${completeAddress}</span></div>
            </div>
        </div>
    </div>

    <!-- Remarks -->
    <div class="section">
        <div class="section-header">&#128203; Remarks</div>
        <div class="section-body">
            <div class="info-item"><span style="font-weight:normal; color:#374151;">${remarks}</span></div>
        </div>
    </div>

    <!-- Signatures -->
    <div class="signature-row">
        <div class="sig-box">
            <div class="sig-line"></div>
            <div class="sig-name">${approvedByOfficer || '____________________'}</div>
            <div class="sig-label">Approved By Officer</div>
        </div>
        <div class="sig-box">
            <div class="sig-line"></div>
            <div class="sig-label">Parent / Guardian Signature</div>
        </div>
        <div class="sig-box">
            <div class="sig-line"></div>
            <div class="sig-name">${approvedByHead || '____________________'}</div>
            <div class="sig-label">Approved By Head</div>
        </div>
    </div>

    <!-- Print Footer -->
    <div class="print-footer">
        Printed on ${printDate} &bull; School Management System &bull; This is a computer-generated document.
    </div>

</div>
</body>
</html>`);
    printWindow.document.close();
    printWindow.focus();
    setTimeout(function() {
        printWindow.print();
    }, 500);
}
</script>

@endpush
