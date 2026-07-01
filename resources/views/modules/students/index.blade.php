@extends('layouts.master')

@section('title', 'Students Directory - School Management System')
@section('header-title', 'Students Directory')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Students</li>
            </ol>
        </nav>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="stat-icon me-3 bg-primary-light" style="width: 54px; height: 54px; font-size: 1.5rem;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold m-0 text-dark">Students Directory</h3>
                        <p class="text-muted mb-0">View approved student profiles, guardian information, and manage fee submissions.</p>
                    </div>
                </div>

                <hr class="my-4 text-muted opacity-25">

                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="approvedStudentsTable" width="100%">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Admission No.</th>
                                <th>Student Name</th>
                                <th>B-Form No</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Roll No</th>
                                <th>Guardian Name</th>
                                <th>Guardian Phone</th>
                                <th>Fee Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Server-side DataTables population -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Student Detailed Info Modal -->
<div class="modal fade" id="studentDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-lines-fill me-2"></i> Student Full Profile</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <!-- Sidebar: Photo & Key details -->
                    <div class="col-lg-3 text-center mb-4 mb-lg-0 border-end-lg">
                        <div class="p-3 bg-light rounded-3 mb-3">
                            <img id="detail_student_image" src="{{ asset('images/default-avatar.png') }}"
                                 alt="Student Photo" class="img-fluid rounded-circle border border-4 border-white shadow-sm mb-3"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                            <h5 class="fw-bold text-dark mb-1" id="detail_full_name">Student Name</h5>
                            <span class="badge bg-success-light px-3 py-2 rounded-pill fw-semibold" id="detail_class_section">Class 1 - A</span>
                            <div class="mt-3 text-start small">
                                <div class="mb-2"><strong>Admission No:</strong> <span id="detail_admission_no" class="text-muted float-end">-</span></div>
                                <div class="mb-2"><strong>Reg No:</strong> <span id="detail_reg_no" class="text-muted float-end">-</span></div>
                                <div class="mb-2"><strong>Roll No:</strong> <span id="detail_roll_no" class="text-muted float-end">-</span></div>
                                <div><strong>Admission Date:</strong> <span id="detail_admission_date" class="text-muted float-end">-</span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Main details tabs -->
                    <div class="col-lg-9">
                        <ul class="nav nav-tabs border-bottom mb-3" id="profileTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-bold text-secondary" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">
                                    <i class="bi bi-person me-1"></i> Personal Info
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-secondary" id="guardian-tab" data-bs-toggle="tab" data-bs-target="#guardian" type="button" role="tab">
                                    <i class="bi bi-people me-1"></i> Guardian Info
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-secondary" id="fees-tab" data-bs-toggle="tab" data-bs-target="#fees" type="button" role="tab">
                                    <i class="bi bi-cash-stack me-1"></i> Fees & Payments
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="profileTabsContent">
                            <!-- Personal Info Tab -->
                            <div class="tab-pane fade show active" id="personal" role="tabpanel">
                                <div class="row g-3 py-2">
                                    <div class="col-md-6 col-lg-4">
                                        <label class="text-muted d-block small">B-Form / CNIC No</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_b_form">-</span>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <label class="text-muted d-block small">Gender</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_gender">-</span>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <label class="text-muted d-block small">Date of Birth</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_dob">-</span>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <label class="text-muted d-block small">Religion</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_religion">-</span>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <label class="text-muted d-block small">Disability Detail</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_disability">-</span>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <label class="text-muted d-block small">Previous Class</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_prev_class">-</span>
                                    </div>
                                    <div class="col-12">
                                        <label class="text-muted d-block small">Previous School Details</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_prev_school">-</span>
                                    </div>
                                    <div class="col-12">
                                        <label class="text-muted d-block small">Remarks</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_remarks">-</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Guardian Info Tab -->
                            <div class="tab-pane fade" id="guardian" role="tabpanel">
                                <div class="row g-3 py-2">
                                    <div class="col-md-6">
                                        <label class="text-muted d-block small">Father Name</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_father_name">-</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted d-block small">Father CNIC</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_father_cnic">-</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted d-block small">Father Occupation</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_father_occ">-</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted d-block small">Mother Name</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_mother_name">-</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted d-block small">Mother Education</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_mother_edu">-</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted d-block small">Guardian Phone</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_guardian_phone">-</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted d-block small">Emergency Contact</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_emergency_contact">-</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted d-block small">Family Monthly Income</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_family_income">-</span>
                                    </div>
                                    <div class="col-12">
                                        <label class="text-muted d-block small">Complete Address</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_complete_address">-</span>
                                    </div>
                                    <div class="col-12">
                                        <label class="text-muted d-block small">Postal Address</label>
                                        <span class="fw-semibold text-dark fs-6" id="detail_postal_address">-</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Fees & Payments Tab -->
                            <div class="tab-pane fade" id="fees" role="tabpanel">
                                <div class="table-responsive py-2">
                                    <table class="table table-bordered table-sm align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Fee Type</th>
                                                <th>Due Date</th>
                                                <th>Base Amount</th>
                                                <th>Fine</th>
                                                <th>Discount</th>
                                                <th>Total Due</th>
                                                <th>Total Paid</th>
                                                <th>Remaining</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="detail_fee_records">
                                            <tr>
                                                <td colspan="9" class="text-center text-muted">No fee record found.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
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

<!-- Submit Fee Modal -->
<div class="modal fade" id="submitFeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-cash-coin me-2"></i> Submit Student Fee</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="submitFeeForm">
                @csrf
                <input type="hidden" id="fee_student_id" name="student_id">
                <div class="modal-body p-4">
                    <!-- Student Summary Card -->
                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3">
                        <img id="fee_student_image" src="{{ asset('images/default-avatar.png') }}"
                             alt="Student Preview" class="rounded-circle border border-2 border-white shadow-sm me-3"
                             style="width: 50px; height: 50px; object-fit: cover;">
                        <div>
                            <h6 class="fw-bold mb-1 text-dark" id="fee_student_name">-</h6>
                            <span class="small text-muted" id="fee_student_subtext">Record payment for admission fee</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Fee Record <span class="text-danger">*</span></label>
                        <select name="fee_id" id="fee_record_select" class="form-select" required>
                            <option value="">Select fee record...</option>
                        </select>
                    </div>

                    <!-- Fee Summary Metrics -->
                    <div class="row g-2 mb-3 bg-light p-3 rounded-3" id="fee_metrics_container" style="display: none;">
                        <div class="col-6 mb-2">
                            <small class="text-muted d-block">Base Amount</small>
                            <span class="fw-semibold" id="metric_base_amount">$0.00</span>
                        </div>
                        <div class="col-6 mb-2">
                            <small class="text-muted d-block">Discount Amount</small>
                            <span class="fw-semibold text-success" id="metric_discount_amount">$0.00</span>
                        </div>
                        <div class="col-6 mb-2">
                            <small class="text-muted d-block">Fine Amount</small>
                            <span class="fw-semibold text-danger" id="metric_fine_amount">$0.00</span>
                        </div>
                        <div class="col-6 mb-2">
                            <small class="text-muted d-block">Total Due</small>
                            <span class="fw-bold text-primary" id="metric_total_due">$0.00</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Total Paid</small>
                            <span class="fw-semibold text-success" id="metric_total_paid">$0.00</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Remaining Balance</small>
                            <span class="fw-bold text-danger" id="metric_remaining">$0.00</span>
                        </div>
                    </div>

                    <!-- Late Fee Warning & Input -->
                    <div id="fine_amount_input_container" class="mb-3" style="display: none;">
                        <div class="alert alert-warning border-0 py-2 px-3 mb-2 small d-flex align-items-center gap-2">
                            <i class="bi bi-exclamation-triangle-fill fs-6 text-warning"></i>
                            <div>This fee is overdue. A late fee fine can be applied.</div>
                        </div>
                        <label class="form-label fw-semibold text-danger">Fine Amount</label>
                        <div class="input-group">
                            <span class="input-group-text bg-danger-light text-danger"><i class="bi bi-plus-circle"></i></span>
                            <input type="number" name="fine_amount" id="fee_fine_amount" class="form-control border-danger" placeholder="Enter fine amount" min="0" step="any" value="0">
                        </div>
                    </div>

                    <div class="mb-3">
    <label class="form-label fw-semibold">Paid Amount <span class="text-danger">*</span></label>
    <div class="input-group">
        <span class="input-group-text"><i class="bi bi-cash"></i></span>
        <input type="number" name="paid_amount" id="fee_paid_amount"
               class="form-control" placeholder="Enter amount"
               min="0.01" step="0.01" required>
    </div>
    <small class="text-muted" id="paid_amount_help">Total Due amount will be auto-filled</small>
</div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Payment Method <span class="text-danger">*</span></label>
                            <select name="payment_method" id="fee_payment_method" class="form-select" required>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cheque">Cheque</option>
                                <option value="online">Online Payment</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" id="fee_payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold">Receipt / Reference No.</label>
                        <input type="text" name="receipt_no" id="fee_receipt_no" class="form-control" placeholder="Optional receipt/bank reference ID">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Submit Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Load libraries dynamically same as Admissions -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Setup DataTable
    var table = $('#approvedStudentsTable').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ordering: true,
        order: [[3, 'asc']],
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        pageLength: 10,
        ajax: {
            url: "{{ route('students.data') }}",
            type: 'GET',
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'student_image', name: 'student_image', orderable: false, searchable: false },
            { data: 'admission_no', name: 'admission_no' },
            { data: 'student_name', name: 'student_name' },
            { data: 'b_form_no', name: 'b_form_no' },
            { data: 'class', name: 'schoolClass.class_name' },
            { data: 'section', name: 'section.section_name' },
            { data: 'roll_no', name: 'roll_no' },
            { data: 'guardian_name', name: 'guardian_name' },
            { data: 'guardian_phone', name: 'guardian_phone' },
            { data: 'fee_status', name: 'fee_status', orderable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        language: {
            processing: '<div class="spinner-border text-primary"></div>',
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            zeroRecords: "No matching student found",
        }
    });

    // View Student profile
    $(document).on('click', '.view-student-btn', function() {
        let id = $(this).data('id');
        $.ajax({
            url: "{{ url('students') }}/" + id + "/show",
            method: 'GET',
            success: function(response) {
                let student = response.student;
                let guardian = student?.guardian;
                let fees = student?.fees || [];

                // Side pane
                $('#detail_student_image').attr('src', student?.image_url || "{{ asset('images/default-avatar.png') }}");
                $('#detail_full_name').text((student?.first_name || '') + ' ' + (student?.last_name || ''));
                $('#detail_class_section').text((response.school_class?.class_name || '-') + ' (' + (response.section?.section_name || '-') + ')');
                $('#detail_admission_no').text(response.admission_no || '-');
                $('#detail_reg_no').text(student?.registration_no || '-');
                $('#detail_roll_no').text(response.roll_no || '-');

                let admDate = response.admission_date;
                if (admDate) {
                    admDate = new Date(admDate).toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });
                }
                $('#detail_admission_date').text(admDate || '-');

                // Personal tab
                $('#detail_b_form').text(student?.b_form_no || '-');
                $('#detail_gender').text(student?.gender ? student.gender.charAt(0).toUpperCase() + student.gender.slice(1) : '-');

                let dob = student?.dob;
                if (dob) {
                    dob = new Date(dob).toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });
                }
                $('#detail_dob').text(dob || '-');
                $('#detail_religion').text(student?.religion || '-');
                $('#detail_disability').text(student?.disability?.name || student?.additional_disability || 'None');
                $('#detail_prev_class').text(student?.previous_class || '-');
                $('#detail_prev_school').text(student?.previous_school_details || '-');
                $('#detail_remarks').text(response.remarks || 'None');

                // Guardian tab
                $('#detail_father_name').text(guardian?.father_name || '-');
                $('#detail_father_cnic').text(guardian?.father_cnic || '-');
                $('#detail_father_occ').text(guardian?.father_occupation || '-');
                $('#detail_mother_name').text(guardian?.mother_name || '-');
                $('#detail_mother_edu').text(guardian?.mother_education || '-');
                $('#detail_guardian_phone').text(guardian?.phone || '-');
                $('#detail_emergency_contact').text(guardian?.emergency_contact || '-');
                $('#detail_family_income').text(guardian?.family_monthly_income || '-');
                $('#detail_complete_address').text(guardian?.complete_address || '-');
                $('#detail_postal_address').text(guardian?.postal_address || '-');

                // Fees Tab
                let feeRows = '';
                if (fees.length > 0) {
                    fees.forEach(function(fee) {
                        let amount = parseFloat(fee.amount || 0);
                        let fine = parseFloat(fee.fine_amount || 0);
                        let discount = parseFloat(fee.discount_amount || 0);
                        let totalDue = (amount + fine) - discount;

                        let totalPaid = 0;
                        if (fee.payments) {
                            fee.payments.forEach(p => totalPaid += parseFloat(p.paid_amount || 0));
                        }

                        let remaining = Math.max(0, totalDue - totalPaid);
                        let statusClass = 'bg-secondary';
                        if (fee.status === 'paid') statusClass = 'bg-success';
                        else if (fee.status === 'pending') statusClass = 'bg-warning text-dark';
                        else if (fee.status === 'partial') statusClass = 'bg-info text-dark';
                        else if (fee.status === 'overdue') statusClass = 'bg-danger';

                        let dueDateStr = fee.due_date ? new Date(fee.due_date).toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' }) : 'N/A';

                        feeRows += `
                            <tr>
                                <td><span class="fw-semibold">${fee.fee_type || 'General Fee'}</span></td>
                                <td>${dueDateStr}</td>
                                <td>${amount.toFixed(2)}</td>
                                <td class="text-danger">+${fine.toFixed(2)}</td>
                                <td class="text-success">-${discount.toFixed(2)}</td>
                                <td class="fw-bold">${totalDue.toFixed(2)}</td>
                                <td class="text-success fw-semibold">${totalPaid.toFixed(2)}</td>
                                <td class="text-danger fw-bold">${remaining.toFixed(2)}</td>
                                <td><span class="badge ${statusClass} text-uppercase">${fee.status || 'pending'}</span></td>
                            </tr>
                        `;
                    });
                } else {
                    feeRows = `<tr><td colspan="9" class="text-center text-muted">No fee records found for this student.</td></tr>`;
                }
                $('#detail_fee_records').html(feeRows);

                // Show modal
                $('#studentDetailModal').modal('show');
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Could not fetch student details', 'error');
            }
        });
    });

    // Submit Fee Modal
    var currentFeesData = [];
    $(document).on('click', '.submit-fee-btn', function() {
        let studentId = $(this).data('student-id');

        // Reset form
        $('#submitFeeForm')[0].reset();
        $('#fee_metrics_container').hide();
        $('#fine_amount_input_container').hide();
        $('#fee_paid_amount').val('');
        $('#fee_paid_amount').prop('disabled', false);
        $('#fee_paid_amount').attr('max', '');
        $('#fee_paid_amount').attr('min', '0.01');

        $.ajax({
            url: "{{ url('students') }}/" + studentId + "/fees",
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    let student = response.student;
                    currentFeesData = response.fees;

                    // Set header info
                    $('#fee_student_id').val(student.id);
                    $('#fee_student_name').text(student.name);
                    $('#fee_student_image').attr('src', student.image_url);

                    // Populate fee select
                    let feeSelect = $('#fee_record_select');
                    feeSelect.html('<option value="">Select fee record...</option>');

                    currentFeesData.forEach(function(fee) {
                        let statusText = fee.status.toUpperCase();
                        let disabled = fee.status === 'paid' ? 'disabled' : '';
                        feeSelect.append(`
                            <option value="${fee.id}" ${disabled}>
                                ${fee.fee_type} (Due: ${fee.due_date_formatted}) - [${statusText}] [Remaining: ${fee.remaining.toFixed(2)}]
                            </option>
                        `);
                    });

                    $('#submitFeeModal').modal('show');
                }
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Could not load student fees info', 'error');
            }
        });
    });

    // On select fee record dropdown
    $('#fee_record_select').on('change', function() {
        let feeId = $(this).val();
        if (!feeId) {
            $('#fee_metrics_container').hide();
            $('#fine_amount_input_container').hide();
            $('#fee_paid_amount').val('');
            return;
        }

        let fee = currentFeesData.find(f => f.id == feeId);
        if (fee) {
            // Set base values
            let baseAmount = parseFloat(fee.amount || 0);
            let discount = parseFloat(fee.discount_amount || 0);
            let totalPaid = parseFloat(fee.total_paid || 0);
            let existingFine = parseFloat(fee.fine_amount || 0);

            $('#metric_base_amount').text(baseAmount.toFixed(2));
            $('#metric_discount_amount').text('-' + discount.toFixed(2));
            $('#metric_total_paid').text(totalPaid.toFixed(2));

            // Set fine amount input value
            $('#fee_fine_amount').val(existingFine);

            // Handle late fee
            if (fee.is_late) {
                $('#fine_amount_input_container').slideDown();
            } else {
                $('#fine_amount_input_container').hide();
                $('#fee_fine_amount').val(0);
            }

            // Recalculate
            recalculateMetrics(baseAmount, discount, totalPaid);

            $('#fee_metrics_container').slideDown();
        }
    });

    // ✅ Updated Helper - Total Due ko Paid Amount mein show karein
    function recalculateMetrics(base, discount, paid) {
        let fine = parseFloat($('#fee_fine_amount').val()) || 0;
        let totalDue = (base + fine) - discount;
        let remaining = Math.max(0, totalDue - paid);

        $('#metric_fine_amount').text('+' + fine.toFixed(2));
        $('#metric_total_due').text(totalDue.toFixed(2));
        $('#metric_remaining').text(remaining.toFixed(2));

        // ✅ FIX: Total Due ko Paid Amount mein set karein (remaining ke bajaye)
        // Agar remaining > 0 hai to totalDue show karein, warna 0.01
        let defaultPaid = remaining > 0 ? totalDue.toFixed(2) : '0.01';
        $('#fee_paid_amount').val(defaultPaid);
        $('#fee_paid_amount').attr('max', totalDue.toFixed(2));
        $('#fee_paid_amount').attr('min', '0.01');

        // ✅ Update help text
        if (remaining <= 0) {
            $('#paid_amount_help').text('Fee is fully paid. Enter minimum amount 0.01');
            $('#fee_paid_amount').prop('disabled', false);
        } else {
            $('#paid_amount_help').text(`Total Due: ${totalDue.toFixed(2)} | Remaining: ${remaining.toFixed(2)}`);
            $('#fee_paid_amount').prop('disabled', false);
        }
    }

    // Trigger recalculation when fine is typed
    $('#fee_fine_amount').on('input', function() {
        let feeId = $('#fee_record_select').val();
        if (!feeId) return;

        let fee = currentFeesData.find(f => f.id == feeId);
        if (fee) {
            let baseAmount = parseFloat(fee.amount || 0);
            let discount = parseFloat(fee.discount_amount || 0);
            let totalPaid = parseFloat(fee.total_paid || 0);
            recalculateMetrics(baseAmount, discount, totalPaid);
        }
    });

    // ✅ Updated Form submit payment logic
    $('#submitFeeForm').on('submit', function(e) {
        e.preventDefault();

        // ✅ Get paid amount
        let paidAmount = parseFloat($('#fee_paid_amount').val());

        // ✅ Check if fee record is selected
        let feeId = $('#fee_record_select').val();
        if (!feeId) {
            Swal.fire({
                icon: 'error',
                title: 'No Fee Selected!',
                text: 'Please select a fee record first.',
                confirmButtonColor: '#d33'
            });
            return false;
        }

        // ✅ Validate amount
        if (isNaN(paidAmount) || paidAmount <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Amount!',
                text: 'Please enter a valid payment amount greater than 0.',
                confirmButtonColor: '#d33'
            });
            return false;
        }

        // ✅ Check max limit (Total Due)
        let maxAmount = parseFloat($('#fee_paid_amount').attr('max')) || 0;
        if (maxAmount > 0 && paidAmount > maxAmount) {
            Swal.fire({
                icon: 'error',
                title: 'Amount Exceeds Total Due!',
                text: `Payment amount cannot exceed total due of ${maxAmount.toFixed(2)}`,
                confirmButtonColor: '#d33'
            });
            return false;
        }

        let studentId = $('#fee_student_id').val();

        // ✅ Show loading
        Swal.fire({
            title: 'Processing...',
            text: 'Please wait while we submit the payment.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: "{{ url('students') }}/" + studentId + "/fees/submit",
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#submitFeeModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        confirmButtonColor: '#28a745'
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorMsg = '';
                    Object.keys(errors).forEach(key => {
                        errorMsg += errors[key][0] + '<br>';
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error!',
                        html: errorMsg,
                        confirmButtonColor: '#d33'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Something went wrong!',
                        confirmButtonColor: '#d33'
                    });
                }
            }
        });
    });
});
</script>
@endpush
