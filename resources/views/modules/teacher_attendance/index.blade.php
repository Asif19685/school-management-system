@extends('layouts.master')

@section('title', 'Teacher Daily Attendance - School Management System')
@section('header-title', 'Teacher Attendance')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Teacher Attendance</li>
            </ol>
        </nav>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>
</div>

{{-- ── Filters ──────────────────────────────────────────────────────────── --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex align-items-center mb-3">
            <div class="stat-icon me-3 bg-purple-light" style="width:48px;height:48px;font-size:1.3rem;">
                <i class="bi bi-funnel"></i>
            </div>
            <h5 class="fw-bold m-0 text-dark">Filter Teacher Attendance</h5>
        </div>
        <div class="row g-3 align-items-end">
            <div class="col-md-8">
                <label class="form-label fw-semibold small">Date</label>
                <input type="date" id="filter_date" class="form-control"
                       value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-4">
                <button id="apply_filter_btn" class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i> Apply Filter
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ── Summary Cards ─────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-primary" id="summary_total">–</div>
            <div class="small text-muted fw-semibold mt-1"><i class="bi bi-people me-1"></i>Total Teachers</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-success" id="summary_present">–</div>
            <div class="small text-muted fw-semibold mt-1"><i class="bi bi-check-circle me-1"></i>Present</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-danger" id="summary_absent">–</div>
            <div class="small text-muted fw-semibold mt-1"><i class="bi bi-x-circle me-1"></i>Absent</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-info" id="summary_halfday">–</div>
            <div class="small text-muted fw-semibold mt-1"><i class="bi bi-clock me-1"></i>Half-Day</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-warning" id="summary_leave">–</div>
            <div class="small text-muted fw-semibold mt-1"><i class="bi bi-calendar2-x me-1"></i>Leave</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-secondary" id="summary_not_marked">–</div>
            <div class="small text-muted fw-semibold mt-1"><i class="bi bi-dash-circle me-1"></i>Not Marked</div>
        </div>
    </div>
</div>

{{-- ── Attendance Table ──────────────────────────────────────────────────── --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex align-items-center mb-4">
            <div class="stat-icon me-3 bg-primary-light" style="width:48px;height:48px;font-size:1.3rem;">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div>
                <h5 class="fw-bold m-0 text-dark">Daily Teacher Attendance Record</h5>
                <p class="text-muted m-0 small" id="report_date_label">Today: {{ date('d M Y') }}</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle" id="attendanceTable" width="100%">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Code</th>
                        <th>Teacher Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Date </th>
                        <th>Check-In/Out</th>
                        <th>Attendance Status</th>

                    </tr>
                </thead>
                <tbody>
                    <!-- DataTables will populate -->
                </tbody>
            </table>
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

    // ── DataTable Setup ─────────────────────────────────────────────────────
    var table = $('#attendanceTable').DataTable({
        processing: true,
        serverSide: true,
        ordering:   true,
        order:      [[3, 'asc']],
        pageLength: 25,
        ajax: {
            url:  "{{ route('teacher-attendance.data') }}",
            type: 'GET',
            data: function(d) {
                d.date = $('#filter_date').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'photo',        orderable: false, searchable: false },
            { data: 'teacher_code', name: 'teacher_code' },
            { data: 'name',         name: 'name' },
            { data: 'email',        name: 'email' },
            { data: 'phone',        name: 'phone' },
          { data: 'attendance_date', name: 'attendance_date', orderable: true },
            { data: 'check_in_out', name: 'check_in_out', orderable: false, searchable: false },
            { data: 'status',       orderable: false, searchable: false },

        ],
        language: {
            processing:  '<div class="spinner-border text-primary"></div>',
            search:      'Search:',
            zeroRecords: 'No teachers found for selected filters.',
            info:        'Showing _START_ to _END_ of _TOTAL_ teachers',
        }
    });

    // ── Summary Loader ───────────────────────────────────────────────────────
    function loadSummary() {
        $.ajax({
            url:  "{{ route('teacher-attendance.summary') }}",
            type: 'GET',
            data: {
                date: $('#filter_date').val()
            },
            success: function(data) {
                $('#summary_total').text(data.total);
                $('#summary_present').text(data.present);
                $('#summary_absent').text(data.absent);
                $('#summary_halfday').text(data.halfDay);
                $('#summary_leave').text(data.leave);
                $('#summary_not_marked').text(data.notMarked);
            }
        });
    }

    // ── Apply Filter Button ───────────────────────────────────────────────────
    $('#apply_filter_btn').on('click', function() {
        let dateVal  = $('#filter_date').val();
        let dateDisp = dateVal
            ? new Date(dateVal).toLocaleDateString('en-US', { day: 'numeric', month: 'long', year: 'numeric' })
            : 'Today';
        $('#report_date_label').text('Date: ' + dateDisp);
        table.ajax.reload();
        loadSummary();
    });

    // ── Edit Attendance Button ────────────────────────────────────────────────
    $(document).on('click', '.edit-attendance-btn', function() {
        let btn = $(this);
        let teacherId = btn.data('teacher-id');
        let current = btn.data('current');
        let dateVal = btn.data('date') || $('#filter_date').val();

        let initialCheckIn = btn.data('check-in') || '08:00';
        let initialCheckOut = btn.data('check-out') || '14:00';

        let displayStatus = current === 'none' ? 'Not Marked' : current;

        Swal.fire({
            title: 'Update Teacher Attendance',
            html: `
                <div class="text-muted mb-3 small">Date: <strong>${dateVal}</strong> | Current Status: <strong>${displayStatus}</strong></div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-start d-block">Select Attendance Status</label>
                    <select id="swal_status" class="form-select">
                        <option value="Present" ${current === 'Present' ? 'selected' : ''}>✅ Present</option>
                        <option value="Absent" ${current === 'Absent' ? 'selected' : ''}>❌ Absent</option>
                        <option value="Leave" ${current === 'Leave' ? 'selected' : ''}>⚠️ Leave</option>
                        <option value="Half-Day" ${current === 'Half-Day' ? 'selected' : ''}>⏰ Half-Day</option>
                    </select>
                </div>

                <div id="swal_time_fields" style="display: ${['Present', 'Half-Day', 'none'].includes(current) || current === 'none' ? 'block' : 'none'};">
                    <div class="row g-2">
                        <div class="col-6 text-start">
                            <label class="form-label small text-muted">Check-In Time</label>
                            <input type="time" id="swal_check_in" class="form-control" value="${initialCheckIn}">
                        </div>
                        <div class="col-6 text-start">
                            <label class="form-label small text-muted">Check-Out Time</label>
                            <input type="time" id="swal_check_out" class="form-control" value="${initialCheckOut}">
                        </div>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-save me-1"></i> Save',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#3085d6',
            preConfirm: () => {
                let status = document.getElementById('swal_status').value;
                let checkIn = document.getElementById('swal_check_in').value;
                let checkOut = document.getElementById('swal_check_out').value;
                return { status, checkIn, checkOut };
            },
            didOpen: () => {
                // Toggle time fields dynamically based on status choice
                document.getElementById('swal_status').addEventListener('change', function() {
                    let fields = document.getElementById('swal_time_fields');
                    if (['Present', 'Half-Day'].includes(this.value)) {
                        $(fields).slideDown();
                    } else {
                        $(fields).slideUp();
                    }
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                saveAttendance(result.value.status, result.value.checkIn, result.value.checkOut);
            }
        });

        function saveAttendance(status, checkIn, checkOut) {
            $.ajax({
                url: "{{ route('teacher-attendance.mark') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    teacher_id: teacherId,
                    status: status,
                    date: dateVal,
                    check_in: checkIn,
                    check_out: checkOut
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        table.ajax.reload(null, false);
                        loadSummary();
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error!', xhr.responseJSON?.message || 'Could not update attendance', 'error');
                }
            });
        }
    });

    // ── Load Summary on Page Load ─────────────────────────────────────────────
    loadSummary();
});
</script>
@endpush
