@extends('layouts.master')

@section('title', 'Daily Attendance Report - School Management System')
@section('header-title', 'Attendance Report')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Attendance</li>
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
            <h5 class="fw-bold m-0 text-dark">Filter Attendance</h5>
        </div>
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Date</label>
                <input type="date" id="filter_date" class="form-control"
                       value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Class</label>
                <select id="filter_class" class="form-select">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                    @endforeach
                </select>
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
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-primary" id="summary_total">–</div>
            <div class="small text-muted fw-semibold mt-1"><i class="bi bi-people me-1"></i>Total Students</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-success" id="summary_present">–</div>
            <div class="small text-muted fw-semibold mt-1"><i class="bi bi-check-circle me-1"></i>Present</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-danger" id="summary_absent">–</div>
            <div class="small text-muted fw-semibold mt-1"><i class="bi bi-x-circle me-1"></i>Absent</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
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
                <h5 class="fw-bold m-0 text-dark">Daily Attendance Record</h5>
                <p class="text-muted m-0 small" id="report_date_label">Today: {{ date('d M Y') }}</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle" id="attendanceTable" width="100%">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Student Name</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Roll No</th>
                        <th>Attendance Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Server-side DataTables -->
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

    // ── DataTable setup ─────────────────────────────────────────────────────
    var table = $('#attendanceTable').DataTable({
        processing: true,
        serverSide: true,
        ordering:   true,
        order:      [[2, 'asc']],
        pageLength: 25,
        ajax: {
            url:  "{{ route('attendance.data') }}",
            type: 'GET',
            data: function(d) {
                d.date     = $('#filter_date').val();
                d.class_id = $('#filter_class').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'photo',        orderable: false, searchable: false },
            { data: 'student_name', name: 'student_name' },
            { data: 'class',        name: 'schoolClass.class_name' },
            { data: 'section',      name: 'section.section_name' },
            { data: 'roll_no',      name: 'roll_no' },
            { data: 'status',       orderable: false, searchable: false },
        ],
        language: {
            processing:  '<div class="spinner-border text-primary"></div>',
            search:      'Search:',
            zeroRecords: 'No students found for selected filters.',
            info:        'Showing _START_ to _END_ of _TOTAL_ students',
        }
    });

    // ── Summary loader ───────────────────────────────────────────────────────
    function loadSummary() {
        $.ajax({
            url:  "{{ route('attendance.summary') }}",
            type: 'GET',
            data: {
                date:     $('#filter_date').val(),
                class_id: $('#filter_class').val()
            },
            success: function(data) {
                $('#summary_total').text(data.total);
                $('#summary_present').text(data.present);
                $('#summary_absent').text(data.absent);
                $('#summary_not_marked').text(data.notMarked);
            }
        });
    }

    // ── Apply filter button ───────────────────────────────────────────────────
    $('#apply_filter_btn').on('click', function() {
        let dateVal  = $('#filter_date').val();
        let dateDisp = dateVal
            ? new Date(dateVal).toLocaleDateString('en-US', { day: 'numeric', month: 'long', year: 'numeric' })
            : 'Today';
        $('#report_date_label').text('Date: ' + dateDisp);
        table.ajax.reload();
        loadSummary();
    });

    // ── Edit attendance button ────────────────────────────────────────────────
    $(document).on('click', '.edit-attendance-btn', function() {
        let btn = $(this);
        let studentId = btn.data('student-id');
        let current = btn.data('current');
        let dateVal = btn.data('date') || $('#filter_date').val();

        let displayStatus = current === 'none' ? 'Not Marked' : current.charAt(0).toUpperCase() + current.slice(1);

        Swal.fire({
            title: 'Update Attendance',
            html: `
                <div class="text-muted mb-3 small">Date: <strong>${dateVal}</strong> | Current Status: <strong>${displayStatus}</strong></div>
                <div class="d-flex flex-column gap-2">
                    <button id="sa-present-btn" class="btn btn-success py-2"><i class="bi bi-check-circle me-1"></i> Mark Present</button>
                    <button id="sa-absent-btn" class="btn btn-danger py-2"><i class="bi bi-x-circle me-1"></i> Mark Absent</button>
                    <button id="sa-leave-btn" class="btn btn-warning py-2 text-dark"><i class="bi bi-calendar2-x me-1"></i> Mark Leave</button>
                </div>
            `,
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            didOpen: () => {
                const content = Swal.getHtmlContainer();
                
                content.querySelector('#sa-present-btn').addEventListener('click', () => {
                    Swal.close();
                    saveAttendance('present');
                });
                
                content.querySelector('#sa-absent-btn').addEventListener('click', () => {
                    Swal.close();
                    saveAttendance('absent');
                });
                
                content.querySelector('#sa-leave-btn').addEventListener('click', () => {
                    Swal.close();
                    saveAttendance('leave');
                });
            }
        });

        function saveAttendance(status) {
            $.ajax({
                url: "{{ route('attendance.mark') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    student_id: studentId,
                    status: status,
                    date: dateVal
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

    // ── Load summary on page load ─────────────────────────────────────────────
    loadSummary();
});
</script>
@endpush
