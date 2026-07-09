{{-- @extends('layouts.master')

@section('title', 'Fee Management - School Management System')
@section('header-title', 'Fee Management')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Fee Management</li>
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
                    <div class="stat-icon me-3 bg-warning-light" style="width: 54px; height: 54px; font-size: 1.5rem;">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold m-0 text-dark">Fee Management</h3>
                        <p class="text-muted mb-0">Students ki fee collect karein aur status manage karein</p>
                    </div>
                </div>

                <hr class="my-4 text-muted opacity-25">

                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="feesStudentsTable" width="100%">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Admission No.</th>
                                <th>Student Name</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Roll No</th>
                                <th>Fee Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
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
                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3">
                        <img id="fee_student_image" src="{{ asset('images/default-avatar.png') }}"
                             alt="Student Preview" class="rounded-circle border border-2 border-white shadow-sm me-3"
                             style="width: 50px; height: 50px; object-fit: cover;">
                        <div>
                            <h6 class="fw-bold mb-1 text-dark" id="fee_student_name">-</h6>
                            <span class="small text-muted">Record payment for selected fee</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Fee Record <span class="text-danger">*</span></label>
                        <select name="fee_id" id="fee_record_select" class="form-select" required>
                            <option value="">Select fee record...</option>
                        </select>
                    </div>

                    <div class="row g-2 mb-3 bg-light p-3 rounded-3" id="fee_metrics_container" style="display: none;">
                        <div class="col-6 mb-2">
                            <small class="text-muted d-block">Base Amount</small>
                            <span class="fw-semibold" id="metric_base_amount">0.00</span>
                        </div>
                        <div class="col-6 mb-2">
                            <small class="text-muted d-block">Discount</small>
                            <span class="fw-semibold text-success" id="metric_discount_amount">0.00</span>
                        </div>
                        <div class="col-6 mb-2">
                            <small class="text-muted d-block">Fine</small>
                            <span class="fw-semibold text-danger" id="metric_fine_amount">0.00</span>
                        </div>
                        <div class="col-6 mb-2">
                            <small class="text-muted d-block">Total Due</small>
                            <span class="fw-bold text-primary" id="metric_total_due">0.00</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Total Paid</small>
                            <span class="fw-semibold text-success" id="metric_total_paid">0.00</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Remaining</small>
                            <span class="fw-bold text-danger" id="metric_remaining">0.00</span>
                        </div>
                    </div>

                    <div id="fine_amount_input_container" class="mb-3" style="display: none;">
                        <div class="alert alert-warning border-0 py-2 px-3 mb-2 small">
                            This fee is overdue. A late fine can be applied.
                        </div>
                        <label class="form-label fw-semibold text-danger">Fine Amount</label>
                        <input type="number" name="fine_amount" id="fee_fine_amount" class="form-control" min="0" step="any" value="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Paid Amount <span class="text-danger">*</span></label>
                        <input type="number" name="paid_amount" id="fee_paid_amount" class="form-control" min="0.01" step="0.01" required>
                        <small class="text-muted" id="paid_amount_help">Total due auto-filled hoga</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Payment Method</label>
                            <select name="payment_method" id="fee_payment_method" class="form-select" required>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cheque">Cheque</option>
                                <option value="online">Online Payment</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Payment Date</label>
                            <input type="date" name="payment_date" id="fee_payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold">Receipt No.</label>
                        <input type="text" name="receipt_no" id="fee_receipt_no" class="form-control" placeholder="Optional">
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
                                <button class="nav-link fw-bold text-secondary" id="fee-tab" data-bs-toggle="tab" data-bs-target="#fee" type="button" role="tab">
                                    <i class="bi bi-cash-stack me-1"></i> Fee Details
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

                            <!-- Fee Info Tab -->
                            <div class="tab-pane fade" id="fee" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover align-middle mt-2">
                                        <thead class="table-light text-secondary small fw-bold">
                                            <tr>
                                                <th>Fee Month / Type</th>
                                                <th>Base Amount</th>
                                                <th>Discount</th>
                                                <th>Fine</th>
                                                <th>Total Due</th>
                                                <th>Total Paid</th>
                                                <th>Remaining</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="detail_fee_records_body" class="small">
                                            <!-- Dynamic content loaded via JS -->
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
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    var table = $('#feesStudentsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: "{{ route('fees.data') }}", type: 'GET' },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'student_image', orderable: false, searchable: false },
            { data: 'admission_no', name: 'admission_no' },
            { data: 'student_name', name: 'student_name' },
            { data: 'class', name: 'schoolClass.class_name' },
            { data: 'section', name: 'section.section_name' },
            { data: 'roll_no', name: 'roll_no' },
            { data: 'fee_status', orderable: false },
            { data: 'actions', orderable: false, searchable: false }
        ],
        order: [[3, 'asc']],
        pageLength: 10
    });

    var currentFeesData = [];

    $(document).on('click', '.submit-fee-btn', function() {
        let studentId = $(this).data('student-id');
        $('#submitFeeForm')[0].reset();
        $('#fee_metrics_container').hide();
        $('#fine_amount_input_container').hide();
        $('#fee_payment_date').val('{{ date('Y-m-d') }}');

        $.ajax({
            url: "{{ url('fees') }}/" + studentId + "/student-fees",
            method: 'GET',
            success: function(response) {
                if (!response.success) return;

                $('#fee_student_id').val(response.student.id);
                $('#fee_student_name').text(response.student.name);
                $('#fee_student_image').attr('src', response.student.image_url);

                currentFeesData = response.fees;
                let feeSelect = $('#fee_record_select');
                feeSelect.html('<option value="">Select fee record...</option>');

                currentFeesData.forEach(function(fee) {
                    let disabled = fee.status === 'paid' ? 'disabled' : '';
                    feeSelect.append(`<option value="${fee.id}" ${disabled}>
                        ${fee.fee_type} (Due: ${fee.due_date_formatted}) - [${fee.status.toUpperCase()}] [Remaining: ${fee.remaining.toFixed(2)}]
                    </option>`);
                });

                $('#submitFeeModal').modal('show');
            },
            error: function() {
                Swal.fire('Error!', 'Could not load student fees', 'error');
            }
        });
    });

    $('#fee_record_select').on('change', function() {
        let feeId = $(this).val();
        if (!feeId) {
            $('#fee_metrics_container').hide();
            return;
        }

        let fee = currentFeesData.find(f => f.id == feeId);
        if (!fee) return;

        let baseAmount = parseFloat(fee.amount || 0);
        let discount = parseFloat(fee.discount_amount || 0);
        let totalPaid = parseFloat(fee.total_paid || 0);

        $('#metric_base_amount').text(baseAmount.toFixed(2));
        $('#metric_discount_amount').text('-' + discount.toFixed(2));
        $('#metric_total_paid').text(totalPaid.toFixed(2));
        $('#fee_fine_amount').val(fee.fine_amount || 0);

        if (fee.is_late) {
            $('#fine_amount_input_container').show();
        } else {
            $('#fine_amount_input_container').hide();
            $('#fee_fine_amount').val(0);
        }

        recalculateMetrics(baseAmount, discount, totalPaid);
        $('#fee_metrics_container').show();
    });

    function recalculateMetrics(base, discount, paid) {
        let fine = parseFloat($('#fee_fine_amount').val()) || 0;
        let totalDue = (base + fine) - discount;
        let remaining = Math.max(0, totalDue - paid);

        $('#metric_fine_amount').text('+' + fine.toFixed(2));
        $('#metric_total_due').text(totalDue.toFixed(2));
        $('#metric_remaining').text(remaining.toFixed(2));

        let defaultPaid = remaining > 0 ? totalDue.toFixed(2) : '0.01';
        $('#fee_paid_amount').val(defaultPaid);
        $('#fee_paid_amount').attr('max', totalDue.toFixed(2));
        $('#paid_amount_help').text(`Total Due: ${totalDue.toFixed(2)} | Remaining: ${remaining.toFixed(2)}`);
    }

    $('#fee_fine_amount').on('input', function() {
        let feeId = $('#fee_record_select').val();
        if (!feeId) return;
        let fee = currentFeesData.find(f => f.id == feeId);
        if (fee) {
            recalculateMetrics(parseFloat(fee.amount || 0), parseFloat(fee.discount_amount || 0), parseFloat(fee.total_paid || 0));
        }
    });

    $('#submitFeeForm').on('submit', function(e) {
        e.preventDefault();

        let feeId = $('#fee_record_select').val();
        let paidAmount = parseFloat($('#fee_paid_amount').val());
        let studentId = $('#fee_student_id').val();

        if (!feeId) {
            Swal.fire('Error!', 'Please select a fee record.', 'error');
            return;
        }

        if (isNaN(paidAmount) || paidAmount <= 0) {
            Swal.fire('Error!', 'Enter a valid payment amount.', 'error');
            return;
        }

        Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        $.ajax({
            url: "{{ url('fees') }}/" + studentId + "/submit",
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#submitFeeModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire('Success!', response.message, 'success');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let msg = Object.values(errors).flat().join('<br>');
                    Swal.fire('Validation Error!', msg, 'error');
                } else {
                    Swal.fire('Error!', xhr.responseJSON?.message || 'Something went wrong', 'error');
                }
            }
        });
    });

    // View Student profile (click on View Profile button)
    $(document).on('click', '.view-student-btn', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        if (!id) return;
        $.ajax({
            url: "{{ url('fees') }}/" + id + "/student-detail",
            method: 'GET',
            success: function(response) {
                let admission = response.admission;
                let student = admission?.student;
                let guardian = student?.guardian;

                $('#detail_student_image').attr('src', student?.image_url || "{{ asset('images/default-avatar.png') }}");
                $('#detail_full_name').text((student?.first_name || '') + ' ' + (student?.last_name || ''));
                $('#detail_class_section').text((admission?.school_class?.class_name || admission?.schoolClass?.class_name || '-') + ' (' + (admission?.section?.section_name || '-') + ')');
                $('#detail_admission_no').text(admission?.admission_no || '-');
                $('#detail_reg_no').text(student?.registration_no || '-');
                $('#detail_roll_no').text(admission?.roll_no || '-');

                let admDate = admission?.admission_date;
                if (admDate) {
                    admDate = new Date(admDate).toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });
                }
                $('#detail_admission_date').text(admDate || '-');

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
                $('#detail_remarks').text(admission?.remarks || 'None');

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

                // Render Fee details
                let feesHtml = '';
                if (response.fees && response.fees.length > 0) {
                    response.fees.forEach(function(fee) {
                        let statusBadge = '';
                        if (fee.status === 'paid') {
                            statusBadge = '<span class="badge bg-success">Paid</span>';
                        } else if (fee.status === 'partial') {
                            statusBadge = '<span class="badge bg-info text-dark">Partial</span>';
                        } else if (fee.status === 'overdue') {
                            statusBadge = '<span class="badge bg-danger">Overdue</span>';
                        } else {
                            statusBadge = '<span class="badge bg-warning text-dark">Pending</span>';
                        }

                        feesHtml += `<tr>
                            <td><strong>${fee.fee_type}</strong></td>
                            <td>Rs. ${parseFloat(fee.amount).toFixed(2)}</td>
                            <td>Rs. ${parseFloat(fee.discount_amount).toFixed(2)}</td>
                            <td>Rs. ${parseFloat(fee.fine_amount).toFixed(2)}</td>
                            <td class="fw-bold">Rs. ${parseFloat(fee.total_due).toFixed(2)}</td>
                            <td class="text-success fw-semibold">Rs. ${parseFloat(fee.total_paid).toFixed(2)}</td>
                            <td class="text-danger fw-semibold">Rs. ${parseFloat(fee.remaining).toFixed(2)}</td>
                            <td>${statusBadge}</td>
                        </tr>`;
                    });
                } else {
                    feesHtml = '<tr><td colspan="8" class="text-center text-muted py-3">No fee records found for this student.</td></tr>';
                }
                $('#detail_fee_records_body').html(feesHtml);

                $('#studentDetailModal').modal('show');
            },
            error: function() {
                Swal.fire('Error!', 'Could not fetch student details', 'error');
            }
        });
    });
});
</script>
@endpush --}}
@extends('layouts.master')

@section('title', 'Fee Management - School Management System')
@section('header-title', 'Fee Management')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Fee Management</li>
            </ol>
        </nav>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>
</div>

{{-- ── Hidden Input for Active Filter ─────────────────────────────────────── --}}
<input type="hidden" id="active_status_filter" value="all">

{{-- ── Summary Cards ─────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <!-- Total Students Card -->
    <div class="col-6 col-md-4">
        <div class="card border-0 shadow-sm text-center p-3 status-card" data-status="all" style="cursor: pointer;">
            <div class="fs-2 fw-bold text-primary" id="summary_totalStudents">–</div>
            <div class="small text-muted fw-semibold mt-1"><i class="bi bi-people me-1"></i>Total Students</div>
        </div>
    </div>

    <!-- Paid Card -->
    <div class="col-6 col-md-4">
        <div class="card border-0 shadow-sm text-center p-3 status-card" data-status="paid" style="cursor: pointer;">
            <div class="fs-2 fw-bold text-success" id="summary_paid">–</div>
            <div class="small text-muted fw-semibold mt-1"><i class="bi bi-check-circle me-1"></i>Paid</div>
        </div>
    </div>

    <!-- Pending Card -->
    <div class="col-6 col-md-4">
        <div class="card border-0 shadow-sm text-center p-3 status-card" data-status="pending" style="cursor: pointer;">
            <div class="fs-2 fw-bold text-warning" id="summary_pending">–</div>
            <div class="small text-muted fw-semibold mt-1"><i class="bi bi-exclamation-circle me-1"></i>Pending</div>
        </div>
    </div>

    <!-- Partial Card -->
    {{-- <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center p-3 status-card" data-status="partial" style="cursor: pointer;">
            <div class="fs-2 fw-bold text-info" id="summary_partial">–</div>
            <div class="small text-muted fw-semibold mt-1"><i class="bi bi-dash-circle me-1"></i>Partial</div>
        </div>
    </div>

    <!-- Overdue Card -->
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center p-3 status-card" data-status="overdue" style="cursor: pointer;">
            <div class="fs-2 fw-bold text-danger" id="summary_overdue">–</div>
            <div class="small text-muted fw-semibold mt-1"><i class="bi bi-calendar-x me-1"></i>Overdue</div>
        </div>
    </div> --}}

    <!-- No Fee Card -->
    {{-- <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center p-3 status-card" data-status="no_fee" style="cursor: pointer;">
            <div class="fs-2 fw-bold text-secondary" id="summary_noFee">–</div>
            <div class="small text-muted fw-semibold mt-1"><i class="bi bi-x-circle me-1"></i>No Fee</div>
        </div>
    </div> --}}
</div>

{{-- ── Active Filter Label ─────────────────────────────────────────────────── --}}
<div class="row mb-3 d-none" id="active_filter_row">
    <div class="col-12">
        <span class="badge bg-info text-white p-2">
            <i class="bi bi-funnel me-1"></i> Filter: <span id="filter_status_text">All Students</span>
        </span>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3 bg-warning-light" style="width: 54px; height: 54px; font-size: 1.5rem;">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold m-0 text-dark">Fee Management</h3>
                            <p class="text-muted mb-0">Students ki fee collect karein aur status manage karein</p>
                        </div>
                    </div>
                    <!-- Premium Month Filter Panel -->
                    <div class="p-3 bg-light rounded-3 border border-light shadow-sm" style="min-width: 320px;">
                        <div class="row g-2 align-items-end">
                            <div class="col-7">
                                <label for="filter_month" class="form-label small fw-bold mb-1 text-secondary">
                                    <i class="bi bi-calendar-event me-1"></i>Select Month
                                </label>
                                <input type="month" id="filter_month" class="form-control form-control-sm border-0 shadow-sm" value="{{ now()->format('Y-m') }}">
                            </div>
                            <div class="col-5">
                                <button type="button" id="apply_filter_btn" class="btn btn-primary btn-sm w-100 shadow-sm d-flex align-items-center justify-content-center gap-1">
                                    <i class="bi bi-funnel-fill"></i> Apply
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4 text-muted opacity-25">

                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="feesStudentsTable" width="100%">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Admission No.</th>
                                <th>Student Name</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Roll No</th>
                                <th>Fee Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
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
                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3">
                        <img id="fee_student_image" src="{{ asset('images/default-avatar.png') }}"
                             alt="Student Preview" class="rounded-circle border border-2 border-white shadow-sm me-3"
                             style="width: 50px; height: 50px; object-fit: cover;">
                        <div>
                            <h6 class="fw-bold mb-1 text-dark" id="fee_student_name">-</h6>
                            <span class="small text-muted">Record payment for selected fee</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Fee Record <span class="text-danger">*</span></label>
                        <select name="fee_id" id="fee_record_select" class="form-select" required>
                            <option value="">Select fee record...</option>
                        </select>
                    </div>

                    <div class="row g-2 mb-3 bg-light p-3 rounded-3" id="fee_metrics_container" style="display: none;">
                        <div class="col-6 mb-2">
                            <small class="text-muted d-block">Base Amount</small>
                            <span class="fw-semibold" id="metric_base_amount">0.00</span>
                        </div>
                        <div class="col-6 mb-2">
                            <small class="text-muted d-block">Discount</small>
                            <span class="fw-semibold text-success" id="metric_discount_amount">0.00</span>
                        </div>
                        <div class="col-6 mb-2">
                            <small class="text-muted d-block">Fine</small>
                            <span class="fw-semibold text-danger" id="metric_fine_amount">0.00</span>
                        </div>
                        <div class="col-6 mb-2">
                            <small class="text-muted d-block">Total Due</small>
                            <span class="fw-bold text-primary" id="metric_total_due">0.00</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Total Paid</small>
                            <span class="fw-semibold text-success" id="metric_total_paid">0.00</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Remaining</small>
                            <span class="fw-bold text-danger" id="metric_remaining">0.00</span>
                        </div>
                    </div>

                    <div id="fine_amount_input_container" class="mb-3" style="display: none;">
                        <div class="alert alert-warning border-0 py-2 px-3 mb-2 small">
                            This fee is overdue. A late fine can be applied.
                        </div>
                        <label class="form-label fw-semibold text-danger">Fine Amount</label>
                        <input type="number" name="fine_amount" id="fee_fine_amount" class="form-control" min="0" step="any" value="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Paid Amount <span class="text-danger">*</span></label>
                        <input type="number" name="paid_amount" id="fee_paid_amount" class="form-control" min="0.01" step="0.01" required>
                        <small class="text-muted" id="paid_amount_help">Total due auto-filled hoga</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Payment Method</label>
                            <select name="payment_method" id="fee_payment_method" class="form-select" required>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cheque">Cheque</option>
                                <option value="online">Online Payment</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Payment Date</label>
                            <input type="date" name="payment_date" id="fee_payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold">Receipt No.</label>
                        <input type="text" name="receipt_no" id="fee_receipt_no" class="form-control" placeholder="Optional">
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

<!-- Include Student Detail Modal -->
@include('modules.partials.student-detail-modal')

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/student-detail.js') }}"></script>

<script>
$(document).ready(function() {
    var table = $('#feesStudentsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('fees.data') }}",
            type: 'GET',
            data: function(d) {
                d.status_filter = $('#active_status_filter').val() || 'all';
                d.filter_month = $('#filter_month').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'student_image', orderable: false, searchable: false },
            { data: 'admission_no', name: 'admission_no' },
            { data: 'student_name', name: 'student_name' },
            { data: 'class', name: 'schoolClass.class_name' },
            { data: 'section', name: 'section.section_name' },
            { data: 'roll_no', name: 'roll_no' },
            { data: 'fee_status', orderable: false },
            { data: 'actions', orderable: false, searchable: false }
        ],
        order: [[3, 'asc']],
        pageLength: 10
    });

    // ── Summary loader ───────────────────────────────────────────────────────
    function loadSummary() {
        $.ajax({
            url: "{{ route('fees.summary') }}",
            type: 'GET',
            data: {
                filter_month: $('#filter_month').val()
            },
            success: function(data) {
                $('#summary_totalStudents').text(data.totalStudents || 0);
                $('#summary_paid').text(data.paid || 0);
                $('#summary_pending').text(data.pending || 0);
                $('#summary_partial').text(data.partial || 0);
                $('#summary_overdue').text(data.overdue || 0);
                $('#summary_noFee').text(data.noFee || 0);
            },
            error: function() {
                console.log('Error loading summary');
            }
        });
    }

    // ── Apply Month Filter Button Click ──────────────────────────────────────
    $('#apply_filter_btn').on('click', function() {
        table.ajax.reload();
        loadSummary();
    });

    // ── Status Card Click Filter ─────────────────────────────────────────────
    $('.status-card').on('click', function() {
        let status = $(this).data('status');
        let statusText = $(this).find('.small.text-muted').text().trim();

        // Remove active class from all cards
        $('.status-card').removeClass('border border-primary bg-light shadow-lg');

        // Add active class to clicked card
        $(this).addClass('border border-primary bg-light shadow-lg');

        // Store active filter
        $('#active_status_filter').val(status);

        // Update filter label text
        if (status === 'all') {
            $('#active_filter_row').addClass('d-none');
        } else {
            $('#filter_status_text').text(statusText);
            $('#active_filter_row').removeClass('d-none');
        }

        // Reload table with filter
        table.ajax.reload();
    });

    // Load initial summary
    loadSummary();

    var currentFeesData = [];

    // Submit Fee Button
    $(document).on('click', '.submit-fee-btn', function() {
        let studentId = $(this).data('student-id');
        $('#submitFeeForm')[0].reset();
        $('#fee_metrics_container').hide();
        $('#fine_amount_input_container').hide();
        $('#fee_payment_date').val('{{ date('Y-m-d') }}');

        $.ajax({
            url: "{{ url('fees') }}/" + studentId + "/student-fees",
            method: 'GET',
            success: function(response) {
                if (!response.success) return;

                $('#fee_student_id').val(response.student.id);
                $('#fee_student_name').text(response.student.name);
                $('#fee_student_image').attr('src', response.student.image_url);

                currentFeesData = response.fees;
                let feeSelect = $('#fee_record_select');
                feeSelect.html('<option value="">Select fee record...</option>');

                currentFeesData.forEach(function(fee) {
                    let disabled = fee.status === 'paid' ? 'disabled' : '';
                    feeSelect.append(`<option value="${fee.id}" ${disabled}>
                        ${fee.fee_type} (Due: ${fee.due_date_formatted}) - [${fee.status.toUpperCase()}] [Remaining: ${fee.remaining.toFixed(2)}]
                    </option>`);
                });

                $('#submitFeeModal').modal('show');
            },
            error: function() {
                Swal.fire('Error!', 'Could not load student fees', 'error');
            }
        });
    });

    // Fee Record Select Change
    $('#fee_record_select').on('change', function() {
        let feeId = $(this).val();
        if (!feeId) {
            $('#fee_metrics_container').hide();
            return;
        }

        let fee = currentFeesData.find(f => f.id == feeId);
        if (!fee) return;

        let baseAmount = parseFloat(fee.amount || 0);
        let discount = parseFloat(fee.discount_amount || 0);
        let totalPaid = parseFloat(fee.total_paid || 0);

        $('#metric_base_amount').text(baseAmount.toFixed(2));
        $('#metric_discount_amount').text('-' + discount.toFixed(2));
        $('#metric_total_paid').text(totalPaid.toFixed(2));
        $('#fee_fine_amount').val(fee.fine_amount || 0);

        if (fee.is_late) {
            $('#fine_amount_input_container').show();
        } else {
            $('#fine_amount_input_container').hide();
            $('#fee_fine_amount').val(0);
        }

        recalculateMetrics(baseAmount, discount, totalPaid);
        $('#fee_metrics_container').show();
    });

    function recalculateMetrics(base, discount, paid) {
        let fine = parseFloat($('#fee_fine_amount').val()) || 0;
        let totalDue = (base + fine) - discount;
        let remaining = Math.max(0, totalDue - paid);

        $('#metric_fine_amount').text('+' + fine.toFixed(2));
        $('#metric_total_due').text(totalDue.toFixed(2));
        $('#metric_remaining').text(remaining.toFixed(2));

        let defaultPaid = remaining > 0 ? totalDue.toFixed(2) : '0.01';
        $('#fee_paid_amount').val(defaultPaid);
        $('#fee_paid_amount').attr('max', totalDue.toFixed(2));
        $('#paid_amount_help').text(`Total Due: ${totalDue.toFixed(2)} | Remaining: ${remaining.toFixed(2)}`);
    }

    $('#fee_fine_amount').on('input', function() {
        let feeId = $('#fee_record_select').val();
        if (!feeId) return;
        let fee = currentFeesData.find(f => f.id == feeId);
        if (fee) {
            recalculateMetrics(parseFloat(fee.amount || 0), parseFloat(fee.discount_amount || 0), parseFloat(fee.total_paid || 0));
        }
    });

    // Submit Fee Form
    $('#submitFeeForm').on('submit', function(e) {
        e.preventDefault();

        let feeId = $('#fee_record_select').val();
        let paidAmount = parseFloat($('#fee_paid_amount').val());
        let studentId = $('#fee_student_id').val();

        if (!feeId) {
            Swal.fire('Error!', 'Please select a fee record.', 'error');
            return;
        }

        if (isNaN(paidAmount) || paidAmount <= 0) {
            Swal.fire('Error!', 'Enter a valid payment amount.', 'error');
            return;
        }

        Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        $.ajax({
            url: "{{ url('fees') }}/" + studentId + "/submit",
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#submitFeeModal').modal('hide');
                    table.ajax.reload();
                    loadSummary();
                    Swal.fire('Success!', response.message, 'success');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let msg = Object.values(errors).flat().join('<br>');
                    Swal.fire('Validation Error!', msg, 'error');
                } else {
                    Swal.fire('Error!', xhr.responseJSON?.message || 'Something went wrong', 'error');
                }
            }
        });
    });

    // View Student profile (Fee Management - with Fee tab)
    $(document).on('click', '.view-student-btn', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        if (!id) return;

        // Show fee tab
        toggleFeeTab(true);

        // Load student detail
        loadStudentDetail(id, true);
    });
});
</script>
@endpush
