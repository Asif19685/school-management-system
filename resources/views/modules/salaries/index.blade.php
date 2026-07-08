@extends('layouts.master')

@section('title', 'Teacher Salaries & Payroll - School Management System')
@section('header-title', 'Teacher Salaries')

@section('content')

{{-- Print Styles --}}
<style>
@media print {
    body * { visibility: hidden; }
    #printSlipArea, #printSlipArea * { visibility: visible; }
    #printSlipArea {
        position: fixed; top: 0; left: 0; width: 100%;
        padding: 30px; background: white; z-index: 99999;
    }
}
</style>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('teachers.index') }}" class="text-decoration-none">Teachers</a></li>
                <li class="breadcrumb-item active" aria-current="page">Salaries</li>
            </ol>
        </nav>
        <button class="btn btn-success btn-sm" id="generatePayrollBtn">
            <i class="bi bi-calculator me-1"></i> Generate Payroll
        </button>
    </div>
</div>

{{-- ── Filter Bar ────────────────────────────────────────────────────────── --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex align-items-center mb-3">
            <div class="stat-icon me-3 bg-purple-light" style="width:48px;height:48px;font-size:1.3rem;">
                <i class="bi bi-funnel"></i>
            </div>
            <h5 class="fw-bold m-0 text-dark">Filter Payroll by Month</h5>
        </div>
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold small">Month</label>
                <select id="filter_month_year" class="form-select">
                    <option value="{{ date('m-Y') }}">{{ date('F Y') }} (Current)</option>
                    @foreach($availableMonths as $my)
                        <option value="{{ $my }}">{{ \Carbon\Carbon::createFromFormat('m-Y', $my)->format('F Y') }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button id="apply_filter_btn" class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i> Load Payroll
                </button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="status_filter" value="all">


{{-- ── Stats Cards ─────────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3 stat-card" data-filter="all" style="cursor: pointer;">
            <div class="fs-2 fw-bold text-primary" id="stat_total">–</div>
            <div class="small text-muted fw-semibold mt-1"><i class="bi bi-people me-1"></i>Total Teachers</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3 stat-card" data-filter="paid" style="cursor: pointer;">
            <div class="fs-2 fw-bold text-success" id="stat_paid">–</div>
            <div class="small text-muted fw-semibold mt-1"><i class="bi bi-check-circle me-1"></i>Paid</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3 stat-card" data-filter="pending" style="cursor: pointer;">
            <div class="fs-2 fw-bold text-warning" id="stat_pending">–</div>
            <div class="small text-muted fw-semibold mt-1"><i class="bi bi-clock me-1"></i>Pending</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3 stat-card" data-filter="all" style="cursor: pointer;">
            <div class="fs-1 fw-bold text-info" id="stat_total_net" style="font-size: 1.4rem !important;">–</div>
            <div class="small text-muted fw-semibold mt-1"><i class="bi bi-cash-stack me-1"></i>Total Net Salary</div>
        </div>
    </div>
</div>

{{-- ── Salary Table ─────────────────────────────────────────────────────────── --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex align-items-center mb-4">
            <div class="stat-icon me-3 bg-primary-light" style="width:48px;height:48px;font-size:1.3rem;">
                <i class="bi bi-cash"></i>
            </div>
            <div>
                <h5 class="fw-bold m-0 text-dark">Payroll Records</h5>
                <p class="text-muted m-0 small" id="payroll_month_label">Month: {{ date('F Y') }}</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle" id="salariesTable" width="100%">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Teacher Name</th>
                        <th>Base Salary</th>
                        <th>Attendance (P/A/H)</th>
                        <th>Deductions</th>
                        <th>Net Salary</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

{{-- ── Edit Salary Modal ───────────────────────────────────────────────────── --}}
<div class="modal fade" id="editSalaryModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i> Edit Salary Record</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editSalaryForm">
                <div class="modal-body">
                    <div class="bg-light p-3 rounded mb-3">
                        <p class="fw-bold mb-0" id="edit_teacher_name_label">-</p>
                        <small class="text-muted" id="edit_month_label"></small>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Base Salary <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rs.</span>
                                <input type="number" name="base_salary" id="edit_base_salary" class="form-control" min="0" step="0.01" readonly required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Deductions</label>
                            <div class="input-group">
                                <span class="input-group-text">Rs.</span>
                                <input type="number" name="deductions" id="edit_deductions" class="form-control" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Net Salary <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rs.</span>
                                <input type="number" name="net_salary" id="edit_net_salary" class="form-control" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="edit_status" class="form-select">
                                <option value="Pending">Pending</option>
                                <option value="Paid">Paid</option>
                            </select>
                        </div>
                    </div>
                    <small class="text-muted"><i class="bi bi-info-circle me-1"></i>You can manually adjust Net Salary = Base Salary − Deductions.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── Print Payslip Modal ─────────────────────────────────────────────────── --}}
<div class="modal fade" id="printSlipModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="bi bi-printer me-2"></i> Salary Slip</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div id="printSlipArea" class="p-4">

                    {{-- Slip Header --}}
                    <div class="text-center mb-3 border-bottom pb-3">
                        <h4 class="fw-bold mb-0"><i class="bi bi-mortarboard-fill me-2 text-primary"></i>School Management System</h4>
                        <p class="text-muted small mb-0">Official Salary Slip</p>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <p class="mb-1 small text-muted">Teacher Name:</p>
                            <p class="fw-bold fs-5 mb-0" id="slip_teacher_name">-</p>
                        </div>
                        <div class="text-end">
                            <p class="mb-1 small text-muted">Teacher Code:</p>
                            <p class="fw-bold mb-0" id="slip_teacher_code">-</p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <p class="mb-1 small text-muted">Pay Month:</p>
                            <p class="fw-semibold mb-0" id="slip_month">-</p>
                        </div>
                        <div class="text-end">
                            <p class="mb-1 small text-muted">Status:</p>
                            <p class="fw-semibold mb-0" id="slip_status">-</p>
                        </div>
                    </div>

                    {{-- Attendance Summary --}}
                    <div class="card border-0 bg-light mb-3">
                        <div class="card-body py-2 px-3">
                            <p class="fw-bold small text-uppercase text-muted mb-2">Attendance Summary</p>
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="fs-5 fw-bold text-success" id="slip_present">-</div>
                                    <small class="text-muted">Present</small>
                                </div>
                                <div class="col-4">
                                    <div class="fs-5 fw-bold text-danger" id="slip_absent">-</div>
                                    <small class="text-muted">Absent</small>
                                </div>
                                <div class="col-4">
                                    <div class="fs-5 fw-bold text-info" id="slip_half">-</div>
                                    <small class="text-muted">Half-Day</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Salary Breakdown --}}
                    <table class="table table-sm table-bordered">
                        <tbody>
                            <tr>
                                <td class="fw-semibold text-muted">Base Salary</td>
                                <td class="text-end fw-bold">Rs. <span id="slip_base">-</span></td>
                            </tr>
                            <tr class="table-danger">
                                <td class="fw-semibold text-muted">Total Deductions</td>
                                <td class="text-end fw-bold text-danger">Rs. <span id="slip_deductions">-</span></td>
                            </tr>
                            <tr class="table-success">
                                <td class="fw-bold">Net Payable Salary</td>
                                <td class="text-end fw-bold fs-5">Rs. <span id="slip_net">-</span></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="text-center text-muted small mt-3 border-top pt-2">
                        <p class="mb-0">Generated on: {{ date('d M Y, h:i A') }}</p>
                        <p class="mb-0">This is a computer-generated slip and does not require a signature.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-print-none">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-dark" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Print
                </button>
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
$(document).ready(function () {

    let currentMonthYear = $('#filter_month_year').val();
    let currentStatusFilter = 'all';
    let totalTeachersCount = {{ $totalTeachers }}; // ✅ Fixed total teachers count

    // ── DataTable Setup ─────────────────────────────────────────────────
    var table = $('#salariesTable').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        order: [[2, 'asc']],
        pageLength: 25,
        ajax: {
            url: "{{ route('salaries.data') }}",
            type: 'GET',
            data: function (d) {
                d.month_year = currentMonthYear;
                d.status_filter = currentStatusFilter;
            }
        },
        columns: [
            { data: 'DT_RowIndex',           orderable: false, searchable: false },
            { data: 'teacher_code',          name: 'teacher_code' },
            { data: 'teacher_name',          name: 'teacher_name' },
            { data: 'base_salary_formatted', orderable: false, searchable: false },
            { data: 'attendance_summary',    orderable: false, searchable: false },
            { data: 'deductions_formatted',  orderable: false, searchable: false },
            { data: 'net_salary_formatted',  orderable: false, searchable: false },
            { data: 'status',                orderable: false, searchable: false },
            { data: 'actions',               orderable: false, searchable: false },
        ],
        drawCallback: function () {
            updateStats();
        },
        language: {
            processing:  '<div class="spinner-border text-primary"></div>',
            zeroRecords: 'No payroll records found. Click "Generate Payroll" to create records for this month.',
        }
    });

    // ── Statistics Cards Click Event ──────────────────────────────────
    $(document).on('click', '.stat-card', function() {
        var filterType = $(this).data('filter');

        // Remove active class from all cards
        $('.stat-card').removeClass('active-card border-primary bg-light');

        // Add active class to clicked card
        $(this).addClass('active-card border-primary bg-light');

        // Update status filter
        currentStatusFilter = filterType;
        $('#status_filter').val(filterType);

        // Reload table with new filter
        table.ajax.reload();
    });

    // ── Apply Filter (Month) ──────────────────────────────────────────
    $('#apply_filter_btn').on('click', function () {
        currentMonthYear = $('#filter_month_year').val();
        let label = $('#filter_month_year option:selected').text();
        $('#payroll_month_label').text('Month: ' + label);
        table.ajax.reload();
    });

    // ── Update Stats from Table Data ──────────────────────────────────
    function updateStats() {
        // ✅ Total teachers - ALWAYS fixed (never changes with filter)
        $('#stat_total').text(totalTeachersCount);

        // ✅ Paid, Pending, Total Net - from ALL records (without filter)
        $.ajax({
            url: "{{ route('salaries.data') }}",
            data: {
                month_year: currentMonthYear,
                status_filter: 'all', // ✅ Always 'all' for stats
                length: -1,
                start: 0
            },
            success: function (res) {
                let paid = 0, pending = 0, totalNet = 0;
                if (res.data && res.data.length > 0) {
                    res.data.forEach(row => {
                        if (row.status && row.status.includes('Paid')) {
                            paid++;
                        } else {
                            pending++;
                        }
                        let netValue = parseFloat((row.net_salary_formatted || '0').replace(/[^0-9.]/g, '')) || 0;
                        totalNet += netValue;
                    });
                }
                $('#stat_paid').text(paid);
                $('#stat_pending').text(pending);
                $('#stat_total_net').text('Rs. ' + totalNet.toLocaleString('en-US', { minimumFractionDigits: 0 }));
            },
            error: function() {
                $('#stat_paid').text(0);
                $('#stat_pending').text(0);
                $('#stat_total_net').text('Rs. 0');
            }
        });
    }

    // ── Generate Payroll Button ───────────────────────────────────────
    $('#generatePayrollBtn').on('click', function () {
        Swal.fire({
            title: 'Generate Monthly Payroll',
            html: `
                <p class="text-muted small mb-3">Select the month to generate or update payroll for all teachers. Existing Paid records will retain their status.</p>
                <div class="text-start">
                    <label class="form-label fw-bold small">Select Month</label>
                    <input type="month" id="swal_month_input" class="form-control" value="{{ date('Y-m') }}">
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-calculator me-1"></i> Generate',
            confirmButtonColor: '#198754',
            preConfirm: () => {
                let val = document.getElementById('swal_month_input').value;
                if (!val) {
                    Swal.showValidationMessage('Please select a month!');
                    return false;
                }
                return val;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Generating payroll...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                $.ajax({
                    url: "{{ route('salaries.generate') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        month_year: result.value
                    },
                    success: function (response) {
                        Swal.close();
                        if (response.success) {
                            let newMonthYear = response.month_year;
                            currentMonthYear = newMonthYear;

                            if ($('#filter_month_year option[value="' + newMonthYear + '"]').length === 0) {
                                $('#filter_month_year').prepend('<option value="' + newMonthYear + '">' + newMonthYear + '</option>');
                            }
                            $('#filter_month_year').val(newMonthYear);

                            table.ajax.reload();

                            Swal.fire({
                                toast: true, position: 'top-end', icon: 'success',
                                title: response.message, showConfirmButton: false, timer: 3000
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function (xhr) {
                        Swal.fire('Error!', xhr.responseJSON?.message || 'Could not generate payroll.', 'error');
                    }
                });
            }
        });
    });

    // ── Pay Salary Button ─────────────────────────────────────────────
    $(document).on('click', '.pay-salary-btn', function () {
        let id = $(this).data('id');
        Swal.fire({
            title: 'Confirm Payment?',
            text: 'This will mark the salary as Paid.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            confirmButtonText: 'Yes, Pay Now!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/salaries/' + id + '/pay',
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        if (response.success) {
                            table.ajax.reload(null, false);
                            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: response.message, showConfirmButton: false, timer: 2000 });
                        }
                    },
                    error: function () {
                        Swal.fire('Error!', 'Could not process payment.', 'error');
                    }
                });
            }
        });
    });

    // ── Edit Salary Button ────────────────────────────────────────────
    let currentEditId = null;

    $(document).on('click', '.edit-salary-btn', function () {
        currentEditId = $(this).data('id');
        $('#edit_teacher_name_label').text($(this).data('teacher'));
        $('#edit_month_label').text('Month: ' + currentMonthYear);

        let baseSalary = $(this).data('base');
        let deductions = $(this).data('deductions');
        let netSalary = $(this).data('net');

        $('#edit_base_salary').val(baseSalary);
        $('#edit_deductions').val(deductions);
        $('#edit_net_salary').val(netSalary);
        $('#edit_status').val($(this).data('status'));
        $('#editSalaryModal').modal('show');
    });

    // Auto-calculate net salary
    $(document).on('input', '#edit_deductions', function() {
        calculateNetSalaryEdit();
    });

    function calculateNetSalaryEdit() {
        let baseSalary = parseFloat($('#edit_base_salary').val()) || 0;
        let deductions = parseFloat($('#edit_deductions').val()) || 0;
        let netSalary = Math.max(0, baseSalary - deductions);
        $('#edit_net_salary').val(netSalary.toFixed(2));
    }

    $('#editSalaryForm').on('submit', function (e) {
        e.preventDefault();
        calculateNetSalaryEdit();
        $.ajax({
            url: '/salaries/' + currentEditId,
            method: 'PUT',
            data: $(this).serialize() + '&_token={{ csrf_token() }}',
            success: function (response) {
                if (response.success) {
                    $('#editSalaryModal').modal('hide');
                    table.ajax.reload(null, false);
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: response.message, showConfirmButton: false, timer: 2000 });
                }
            },
            error: function (xhr) {
                Swal.fire('Error!', xhr.responseJSON?.message || 'Could not save changes.', 'error');
            }
        });
    });

    // ── Print Payslip Button ──────────────────────────────────────────
    $(document).on('click', '.print-slip-btn', function () {
        let btn = $(this);
        $('#slip_teacher_name').text(btn.data('teacher'));
        $('#slip_teacher_code').text(btn.data('code'));
        $('#slip_month').text(btn.data('month'));
        $('#slip_base').text('Rs. ' + btn.data('base'));
        $('#slip_deductions').text('Rs. ' + btn.data('deductions'));
        $('#slip_net').text('Rs. ' + btn.data('net'));
        $('#slip_present').text(btn.data('present'));
        $('#slip_absent').text(btn.data('absent'));
        $('#slip_half').text(btn.data('half'));

        let st = btn.data('status');
        let badgeHtml = st === 'Paid'
            ? '<span class="badge bg-success">Paid</span>'
            : '<span class="badge bg-warning text-dark">Pending</span>';
        $('#slip_status').html(badgeHtml);

        $('#printSlipModal').modal('show');
    });

    // ── Initial Stats Load ────────────────────────────────────────────
    // Trigger initial stats update after table loads
    setTimeout(function() {
        updateStats();
    }, 500);
});
</script>
@endpush
{{-- @push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {

    let currentMonthYear = $('#filter_month_year').val();
    let currentStatusFilter = 'all';
    // ── DataTable Setup ─────────────────────────────────────────────────
    var table = $('#salariesTable').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        order: [[2, 'asc']],
        pageLength: 25,
        ajax: {
            url: "{{ route('salaries.data') }}",
            type: 'GET',
            data: function (d) {
                d.month_year = currentMonthYear;
                d.status_filter = currentStatusFilter;
            }
        },
        columns: [
            { data: 'DT_RowIndex',           orderable: false, searchable: false },
            { data: 'teacher_code',          name: 'teacher_code' },
            { data: 'teacher_name',          name: 'teacher_name' },
            { data: 'base_salary_formatted', orderable: false, searchable: false },
            { data: 'attendance_summary',    orderable: false, searchable: false },
            { data: 'deductions_formatted',  orderable: false, searchable: false },
            { data: 'net_salary_formatted',  orderable: false, searchable: false },
            { data: 'status',                orderable: false, searchable: false },
            { data: 'actions',               orderable: false, searchable: false },
        ],
        drawCallback: function () {
            updateStats();
        },
        language: {
            processing:  '<div class="spinner-border text-primary"></div>',
            zeroRecords: 'No payroll records found. Click "Generate Payroll" to create records for this month.',
        }
    });

$(document).on('click', '.stat-card', function() {
    var filterType = $(this).data('filter');
    console.log('Clicked card with filter:', filterType); // Debug

    $('.stat-card').removeClass('active-card border-primary bg-light');
    $(this).addClass('active-card border-primary bg-light');

    currentStatusFilter = filterType;
    $('#status_filter').val(filterType);
    console.log('Current filter set to:', currentStatusFilter); // Debug

    // Check what data is being sent
    console.log('Table reloading with filter:', currentStatusFilter);
    table.ajax.reload();
});

    // ── Apply Filter ──────────────────────────────────────────────────────
    $('#apply_filter_btn').on('click', function () {
        currentMonthYear = $('#filter_month_year').val();
        let label = $('#filter_month_year option:selected').text();
        $('#payroll_month_label').text('Month: ' + label);
        table.ajax.reload();
    });

    // ── Update Stats from Table Data ─────────────────────────────────────
    function updateStats() {
        // Pull from the DataTable rows currently displayed (works for all server-side data too)
        let info = table.page.info();
        $('#stat_total').text(info.recordsTotal);

        // Extra AJAX for aggregate stats:
        $.ajax({
            url: "{{ route('salaries.data') }}",
            data: { month_year: currentMonthYear, length: -1, start: 0 },
            success: function (res) {
                let paid = 0, pending = 0, totalNet = 0;
                (res.data || []).forEach(row => {
                    if (row.status && row.status.includes('Paid')) paid++;
                    else pending++;
                    totalNet += parseFloat((row.net_salary_formatted || '0').replace(/[^0-9.]/g, '')) || 0;
                });
                $('#stat_paid').text(paid);
                $('#stat_pending').text(pending);
                $('#stat_total_net').text('Rs. ' + totalNet.toLocaleString('en-US', { minimumFractionDigits: 0 }));
            }
        });
    }

    // ── Generate Payroll Button ───────────────────────────────────────────
    $('#generatePayrollBtn').on('click', function () {
        Swal.fire({
            title: 'Generate Monthly Payroll',
            html: `
                <p class="text-muted small mb-3">Select the month to generate or update payroll for all teachers. Existing Paid records will retain their status.</p>
                <div class="text-start">
                    <label class="form-label fw-bold small">Select Month</label>
                    <input type="month" id="swal_month_input" class="form-control" value="{{ date('Y-m') }}">
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-calculator me-1"></i> Generate',
            confirmButtonColor: '#198754',
            preConfirm: () => {
                let val = document.getElementById('swal_month_input').value;
                if (!val) {
                    Swal.showValidationMessage('Please select a month!');
                    return false;
                }
                return val;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Generating payroll...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                $.ajax({
                    url: "{{ route('salaries.generate') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        month_year: result.value
                    },
                    success: function (response) {
                        Swal.close();
                        if (response.success) {
                            // Auto-switch the filter dropdown and reload table
                            let newMonthYear = response.month_year;
                            currentMonthYear = newMonthYear;

                            // Add option if not present
                            if ($('#filter_month_year option[value="' + newMonthYear + '"]').length === 0) {
                                $('#filter_month_year').prepend('<option value="' + newMonthYear + '">' + newMonthYear + '</option>');
                            }
                            $('#filter_month_year').val(newMonthYear);

                            table.ajax.reload();

                            Swal.fire({
                                toast: true, position: 'top-end', icon: 'success',
                                title: response.message, showConfirmButton: false, timer: 3000
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function (xhr) {
                        Swal.fire('Error!', xhr.responseJSON?.message || 'Could not generate payroll.', 'error');
                    }
                });
            }
        });
    });

    // ── Pay Salary Button ────────────────────────────────────────────────
    $(document).on('click', '.pay-salary-btn', function () {
        let id = $(this).data('id');
        Swal.fire({
            title: 'Confirm Payment?',
            text: 'This will mark the salary as Paid.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            confirmButtonText: 'Yes, Pay Now!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/salaries/' + id + '/pay',
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        if (response.success) {
                            table.ajax.reload(null, false);
                            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: response.message, showConfirmButton: false, timer: 2000 });
                        }
                    },
                    error: function () {
                        Swal.fire('Error!', 'Could not process payment.', 'error');
                    }
                });
            }
        });
    });

    // ── Edit Salary Button ───────────────────────────────────────────────
    let currentEditId = null;

    $(document).on('click', '.edit-salary-btn', function () {
    currentEditId = $(this).data('id');
    $('#edit_teacher_name_label').text($(this).data('teacher'));
    $('#edit_month_label').text('Month: ' + currentMonthYear);

    let baseSalary = $(this).data('base');
    let deductions = $(this).data('deductions');
    let netSalary = $(this).data('net');

    $('#edit_base_salary').val(baseSalary);
    $('#edit_deductions').val(deductions);
    $('#edit_net_salary').val(netSalary);
    $('#edit_status').val($(this).data('status'));
    $('#editSalaryModal').modal('show');
});

// Auto-calculate net salary when deductions or base salary changes
$(document).on('input', '#edit_deductions, #edit_base_salary', function() {
    calculateNetSalaryEdit();
});

function calculateNetSalaryEdit() {
    let baseSalary = parseFloat($('#edit_base_salary').val()) || 0;
    let deductions = parseFloat($('#edit_deductions').val()) || 0;
    let netSalary = baseSalary - deductions;

    // Ensure net salary is not negative
    if (netSalary < 0) {
        netSalary = 0;
    }

    $('#edit_net_salary').val(netSalary.toFixed(2));
}
    // $(document).on('click', '.edit-salary-btn', function () {
    //     currentEditId = $(this).data('id');
    //     $('#edit_teacher_name_label').text($(this).data('teacher'));
    //     $('#edit_month_label').text('Month: ' + currentMonthYear);
    //      let baseSalary = $(this).data('base');
    // let deductions = $(this).data('deductions');
    // let netSalary = $(this).data('net');


    //     $('#edit_base_salary').val($(this).data('base'));
    //     $('#edit_deductions').val($(this).data('deductions'));
    //     $('#edit_net_salary').val($(this).data('net'));
    //     $('#edit_status').val($(this).data('status'));
    //     $('#editSalaryModal').modal('show');
    // });

    $('#editSalaryForm').on('submit', function (e) {
        e.preventDefault();

         calculateNetSalaryEdit();
        $.ajax({
            url: '/salaries/' + currentEditId,
            method: 'PUT',
            data: $(this).serialize() + '&_token={{ csrf_token() }}',
            success: function (response) {
                if (response.success) {
                    $('#editSalaryModal').modal('hide');
                    table.ajax.reload(null, false);
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: response.message, showConfirmButton: false, timer: 2000 });
                }
            },
            error: function (xhr) {
                Swal.fire('Error!', xhr.responseJSON?.message || 'Could not save changes.', 'error');
            }
        });
    });

    // ── Print Payslip Button ─────────────────────────────────────────────
    $(document).on('click', '.print-slip-btn', function () {
        let btn = $(this);
        $('#slip_teacher_name').text(btn.data('teacher'));
        $('#slip_teacher_code').text(btn.data('code'));
        $('#slip_month').text(btn.data('month'));
        $('#slip_status').text(btn.data('status'));
        $('#slip_base').text(btn.data('base'));
        $('#slip_deductions').text(btn.data('deductions'));
        $('#slip_net').text(btn.data('net'));
        $('#slip_present').text(btn.data('present'));
        $('#slip_absent').text(btn.data('absent'));
        $('#slip_half').text(btn.data('half'));

        // Status badge for print
        let st = btn.data('status');
        let badgeHtml = st === 'Paid'
            ? '<span class="badge bg-success">Paid</span>'
            : '<span class="badge bg-warning text-dark">Pending</span>';
        $('#slip_status').html(badgeHtml);

        $('#printSlipModal').modal('show');
    });
});
</script>
@endpush --}}
